<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ItemImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

use function Flasher\Prime\flash;

class Edit extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter
    public $search = '';
    public $perPage = 10;

    // Selected invoice
    public $selectedInvoiceId = null;
    public $editInvoiceNumber;
    public $editPhone;
    public $editAddress;
    public $editTruckNumber;
    public $editUserId = 1;
    public $editTodayDate;

    // Modals
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $invoiceToDelete = null;

    // Edit orders and product selection
    public $editOrders = [];
    public $selectedProductIndex = 0;
    public $newOrder = [
        'link' => '',
        'name' => '',
        'temp_images' => [],
        'images' => [],
        'quantity' => 1,
        'date_order' => 1,
        'delivery_date' => ''
    ];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $defaultUser = User::first();
        if ($defaultUser) {
            $this->editUserId = $defaultUser->id;
        } else {
            $this->editUserId = 1;
        }

        $this->editTodayDate = now()->format('Y-m-d');
        $this->calculateDeliveryDate();
    }

    #[On('invoiceUpdated')]
    public function refreshInvoices()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Open edit modal
    public function openEditModal($invoiceId)
    {
        $this->selectedInvoiceId = $invoiceId;
        $this->selectedProductIndex = 0;
        $invoice = Invoice::with(['items.images'])->find($invoiceId);

        if ($invoice) {
            $this->editInvoiceNumber = $invoice->invoice_number;
            $this->editPhone = $invoice->phone;
            $this->editAddress = $invoice->address;
            $this->editTruckNumber = $invoice->truck_number;
            $this->editUserId = $invoice->user_id ?? User::first()->id;
            $this->editTodayDate = $invoice->today_date ? Carbon::parse($invoice->today_date)->format('Y-m-d') : now()->format('Y-m-d');

            $this->editOrders = [];
            foreach ($invoice->items as $item) {
                $this->editOrders[] = [
                    'id' => $item->id,
                    'link' => $item->link ?? '',
                    'name' => $item->name,
                    'existing_images' => $item->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'path' => $image->image_path,
                            'url' => Storage::disk('public')->exists($image->image_path)
                                ? asset('storage/' . $image->image_path)
                                : asset('images/no-image.png')
                        ];
                    })->toArray(),
                    'new_images' => [],
                    'temp_images' => [],
                    'quantity' => $item->quantity,
                    'date_order' => $item->date_order,
                    'delivery_date' => $item->delivery_date ? Carbon::parse($item->delivery_date)->format('Y-m-d') : ''
                ];
            }

            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedInvoiceId = null;
        $this->selectedProductIndex = 0;
        $this->resetEditFields();
    }

    public function resetEditFields()
    {
        $this->editInvoiceNumber = null;
        $this->editPhone = '';
        $this->editAddress = '';
        $this->editTruckNumber = '';
        $defaultUser = User::first();
        $this->editUserId = $defaultUser ? $defaultUser->id : 1;
        $this->editTodayDate = now()->format('Y-m-d');
        $this->editOrders = [];
        $this->resetNewOrderFields();
    }

    // Show delete confirmation for invoice
    public function showDeleteConfirmation($invoiceId)
    {
        $this->dispatch('showConfirmationDialog', [
            'title' => 'تأكيد الحذف',
            'message' => 'هل أنت متأكد من حذف هذه الفاتورة؟ سيتم حذف جميع البيانات والمنتجات المرتبطة بها نهائياً.',
            'icon' => 'warning',
            'confirmText' => 'نعم، احذف',
            'cancelText' => 'إلغاء',
            'confirmColor' => '#e63946',
            'event' => 'confirmDeleteInvoice',
            'params' => ['invoiceId' => $invoiceId]
        ]);
    }

    #[On('confirmDeleteInvoice')]
    public function deleteInvoice($invoiceId)
    {
        if ($invoiceId) {
            Invoice::find($invoiceId)->delete();

            $this->dispatch('showSuccessAlert', 'تم حذف الفاتورة بنجاح!');
            $this->dispatch('invoiceUpdated');
        }
    }

    // Show delete confirmation for product
    public function showProductDeleteConfirmation($index)
    {
        $this->dispatch('showConfirmationDialog', [
            'title' => 'حذف المنتج',
            'message' => 'هل أنت متأكد من حذف هذا المنتج؟',
            'icon' => 'question',
            'confirmText' => 'نعم، احذف',
            'cancelText' => 'إلغاء',
            'confirmColor' => '#e63946',
            'event' => 'confirmRemoveProduct',
            'params' => ['index' => $index]
        ]);
    }

    #[On('confirmRemoveProduct')]
    public function removeOrderFromEdit($index)
    {
        if (isset($this->editOrders[$index])) {
            unset($this->editOrders[$index]);
            $this->editOrders = array_values($this->editOrders);

            if ($this->selectedProductIndex >= count($this->editOrders)) {
                $this->selectedProductIndex = max(0, count($this->editOrders) - 1);
            }

            $this->dispatch('showSuccessAlert', 'تم حذف المنتج بنجاح!');
        }
    }

    // Show delete confirmation for image
    public function showImageDeleteConfirmation($orderIndex, $imageId)
    {
        $this->dispatch('showConfirmationDialog', [
            'title' => 'حذف الصورة',
            'message' => 'هل أنت متأكد من حذف هذه الصورة؟',
            'icon' => 'question',
            'confirmText' => 'نعم، احذف',
            'cancelText' => 'إلغاء',
            'confirmColor' => '#e63946',
            'event' => 'confirmRemoveImage',
            'params' => ['orderIndex' => $orderIndex, 'imageId' => $imageId]
        ]);
    }

    #[On('confirmRemoveImage')]
    public function removeExistingImage($orderIndex, $imageId)
    {
        if (isset($this->editOrders[$orderIndex]['existing_images'])) {
            $this->editOrders[$orderIndex]['existing_images'] = array_filter(
                $this->editOrders[$orderIndex]['existing_images'],
                function ($image) use ($imageId) {
                    return $image['id'] != $imageId;
                }
            );

            $this->editOrders[$orderIndex]['existing_images'] = array_values(
                $this->editOrders[$orderIndex]['existing_images']
            );

            $this->dispatch('showSuccessAlert', 'تم حذف الصورة بنجاح!');
        }
    }

    // Show delete confirmation for temp image
    public function showTempImageDeleteConfirmation($orderIndex, $imageIndex)
    {
        $this->dispatch('showConfirmationDialog', [
            'title' => 'حذف الصورة',
            'message' => 'هل أنت متأكد من حذف هذه الصورة؟',
            'icon' => 'question',
            'confirmText' => 'نعم، احذف',
            'cancelText' => 'إلغاء',
            'confirmColor' => '#e63946',
            'event' => 'confirmRemoveTempImage',
            'params' => ['orderIndex' => $orderIndex, 'imageIndex' => $imageIndex]
        ]);
    }

    #[On('confirmRemoveTempImage')]
    public function removeNewImage($orderIndex, $imageIndex)
    {
        if (isset($this->editOrders[$orderIndex]['new_images'][$imageIndex])) {
            unset($this->editOrders[$orderIndex]['new_images'][$imageIndex]);
            $this->editOrders[$orderIndex]['new_images'] = array_values(
                $this->editOrders[$orderIndex]['new_images']
            );

            $this->dispatch('showSuccessAlert', 'تم حذف الصورة بنجاح!');
        }
    }

    // Show delete confirmation for new order image
    public function showNewOrderImageDeleteConfirmation($index)
    {
        $this->dispatch('showConfirmationDialog', [
            'title' => 'حذف الصورة',
            'message' => 'هل أنت متأكد من حذف هذه الصورة؟',
            'icon' => 'question',
            'confirmText' => 'نعم، احذف',
            'cancelText' => 'إلغاء',
            'confirmColor' => '#e63946',
            'event' => 'confirmRemoveNewOrderImage',
            'params' => ['index' => $index]
        ]);
    }

    #[On('confirmRemoveNewOrderImage')]
    public function removeImageFromNewOrder($index)
    {
        if (isset($this->newOrder['images'][$index])) {
            unset($this->newOrder['images'][$index]);
            $this->newOrder['images'] = array_values($this->newOrder['images']);

            $this->dispatch('showSuccessAlert', 'تم حذف الصورة بنجاح!');
        }
    }

    public function saveEditedInvoice()
    {
        // التحقق من وجود المستخدم أو استخدام الافتراضي
        if (!User::find($this->editUserId)) {
            $defaultUser = User::first();
            $this->editUserId = $defaultUser ? $defaultUser->id : 1;
        }

        // Validate invoice data
        $this->validate([
            'editInvoiceNumber' => 'required|integer|min:1|unique:invoices,invoice_number,' . ($this->selectedInvoiceId ?? 'NULL'),
            'editPhone' => 'required|string|min:10',
            'editAddress' => 'required|string|min:5',
            'editTruckNumber' => 'required|string|min:3',
            'editTodayDate' => 'required|date',
        ]);

        // Check if there are any products
        if (empty($this->editOrders)) {
            $this->dispatch('showErrorAlert', 'يجب إضافة منتج واحد على الأقل');
            return;
        }

        // Validate each product
        foreach ($this->editOrders as $index => $order) {
            $this->validate([
                "editOrders.{$index}.name" => 'required|string|min:2',
                "editOrders.{$index}.quantity" => 'required|integer|min:1',
                "editOrders.{$index}.date_order" => 'required|integer|min:1',
                "editOrders.{$index}.link" => 'nullable|url|max:5000',
                "editOrders.{$index}.delivery_date" => 'required|date',
            ]);
        }

        try {
            DB::beginTransaction();

            // Find or create invoice
            if ($this->selectedInvoiceId) {
                $invoice = Invoice::find($this->selectedInvoiceId);
                $invoice->update([
                    'invoice_number' => $this->editInvoiceNumber,
                    'user_id' => $this->editUserId,
                    'phone' => $this->editPhone,
                    'address' => $this->editAddress,
                    'truck_number' => $this->editTruckNumber,
                    'today_date' => $this->editTodayDate,
                ]);
            } else {
                $invoice = Invoice::create([
                    'invoice_number' => $this->editInvoiceNumber,
                    'user_id' => $this->editUserId,
                    'phone' => $this->editPhone,
                    'address' => $this->editAddress,
                    'truck_number' => $this->editTruckNumber,
                    'today_date' => $this->editTodayDate,
                ]);
            }

            // Get existing item IDs
            $existingItemIds = [];
            foreach ($this->editOrders as $order) {
                if (!empty($order['id'])) {
                    $existingItemIds[] = $order['id'];
                }
            }

            // Delete removed items
            $invoice->items()->whereNotIn('id', $existingItemIds)->delete();

            // Process each product
            foreach ($this->editOrders as $order) {
                if (!empty($order['id']) && in_array($order['id'], $existingItemIds)) {
                    // Update existing product
                    $item = InvoiceItem::find($order['id']);
                    if ($item) {
                        $item->update([
                            'name' => $order['name'],
                            'link' => $order['link'] ?? null,
                            'quantity' => $order['quantity'],
                            'date_order' => $order['date_order'],
                            'delivery_date' => $order['delivery_date'],
                            'invoice_id' => $invoice->id,
                        ]);

                        // Handle images
                        $this->processItemImages($item, $order);
                    }
                } else {
                    // Create new product
                    $item = $invoice->items()->create([
                        'name' => $order['name'],
                        'link' => $order['link'] ?? null,
                        'quantity' => $order['quantity'],
                        'date_order' => $order['date_order'],
                        'delivery_date' => $order['delivery_date'],
                    ]);

                    // Handle images
                    $this->processItemImages($item, $order);
                }
            }

            DB::commit();
            flash()->success('تم تعديل الفاتورة بنجاح!');
            $this->dispatch('showSuccessAlert', 'تم حفظ الفاتورة بنجاح!');
            $this->closeEditModal();
            $this->dispatch('invoiceUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
flash()->error('حدث خطأ أثناء حفظ الفاتورة: ' . $e->getMessage());
            $this->dispatch('showErrorAlert', 'حدث خطأ أثناء حفظ الفاتورة. يرجى المحاولة مرة أخرى.');
            }
    }

    private function processItemImages($item, $order)
    {
        // Delete removed existing images
        if (!empty($order['existing_images'])) {
            $existingImageIds = collect($order['existing_images'])->pluck('id')->toArray();
            $item->images()->whereNotIn('id', $existingImageIds)->delete();
        }

        // Save new images
        if (!empty($order['new_images'])) {
            foreach ($order['new_images'] as $image) {
                if ($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $path = $image->store('products', 'public');
                    $item->images()->create(['image_path' => $path]);
                }
            }
        }
    }

    // Add new product
    public function addNewProduct()
    {
        $this->selectedProductIndex = 'new';
        if (!empty($this->newOrder['date_order'])) {
            $this->newOrder['delivery_date'] = Carbon::parse($this->editTodayDate)
                ->addDays($this->newOrder['date_order'])
                ->format('Y-m-d');
        }
    }

    public function addOrderToEdit()
    {
        $this->validate([
            'newOrder.name' => 'required|string|min:2',
            'newOrder.quantity' => 'required|integer|min:1',
            'newOrder.date_order' => 'required|integer|min:1',
            'newOrder.link' => 'nullable|url|max:5000',
            'newOrder.delivery_date' => 'required|date',
        ]);

        // Calculate delivery date if not set
        if (empty($this->newOrder['delivery_date'])) {
            $this->newOrder['delivery_date'] = Carbon::parse($this->editTodayDate)
                ->addDays($this->newOrder['date_order'])
                ->format('Y-m-d');
        }

        $this->editOrders[] = [
            'id' => null,
            'link' => $this->newOrder['link'] ?? null,
            'name' => $this->newOrder['name'],
            'existing_images' => [],
            'new_images' => $this->newOrder['images'],
            'temp_images' => [],
            'quantity' => $this->newOrder['quantity'],
            'date_order' => $this->newOrder['date_order'],
            'delivery_date' => $this->newOrder['delivery_date']
        ];

        $this->resetNewOrderFields();
        $this->selectedProductIndex = count($this->editOrders) - 1;

        $this->dispatch('showSuccessAlert', 'تم إضافة المنتج الجديد بنجاح!');
    }

    public function resetNewOrderFields()
    {
        $this->newOrder = [
            'link' => '',
            'name' => '',
            'temp_images' => [],
            'images' => [],
            'quantity' => 1,
            'date_order' => 1,
            'delivery_date' => ''
        ];
        $this->calculateDeliveryDate();
    }

    public function calculateDeliveryDate()
    {
        if (!empty($this->editTodayDate) && !empty($this->newOrder['date_order'])) {
            try {
                $this->newOrder['delivery_date'] = Carbon::parse($this->editTodayDate)
                    ->addDays((int) $this->newOrder['date_order'])
                    ->format('Y-m-d');
            } catch (\Exception $e) {
                $this->newOrder['delivery_date'] = now()->format('Y-m-d');
            }
        }
    }

    public function updated($property, $value)
    {
        // Update delivery date when date_order changes
        if (in_array($property, ['newOrder.date_order', 'editTodayDate'])) {
            $this->calculateDeliveryDate();
        }

        // Handle new image uploads for new product
        if ($property === 'newOrder.temp_images' && !empty($value)) {
            foreach ($value as $image) {
                if ($image) {
                    $this->newOrder['images'][] = $image;
                }
            }
            $this->newOrder['temp_images'] = [];
        }

        // Handle image uploads in edit orders
        foreach ($this->editOrders as $index => $order) {
            if ($property === "editOrders.{$index}.temp_images" && !empty($value)) {
                foreach ($value as $image) {
                    if ($image) {
                        $this->editOrders[$index]['new_images'][] = $image;
                    }
                }
                $this->editOrders[$index]['temp_images'] = [];
            }
        }
    }

    public function render()
    {
        // FIX: Get invoices and pass them to the view
        $invoices = Invoice::with(['items'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('address', 'like', '%' . $this->search . '%')
                        ->orWhere('truck_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('items', function ($itemQuery) {
                            $itemQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.invoices.edit', [
            'invoices' => $invoices
        ]);
    }
}
