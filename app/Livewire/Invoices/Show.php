<?php
// app/Livewire/Invoices/Show.php

namespace App\Livewire\Invoices;

use App\Models\InfoInvoice;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ItemImage;
use App\Models\Driver;
use App\Models\Customer; // Add this import
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator; // Add this for better validation

class Show extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $truck_number;
    public $buyprice;
    public $search = '';
    public $dateFilter = '';
    public $selectedInvoices = [];
    public $selectAll = false;
    public $singleInvoiceId;
    public $existing_truck_price;

    public $showDriverModal = false;
    public $selectedDriverId = null;
    public $drivers = [];

    // Edit Modal Properties
    public $showEditModal = false;
    public $editInvoiceId;
    public $editInvoiceNumber;
    public $editPhone;
    public $editAddress;
    public $editTruckNumber;
    public $editTodayDate;
    public $editOrders = [];
    public $selectedProductIndex = 0;
    public $newOrder = [];
    public $deletedImages = [];

    // Delete Modals
    public $showDeleteModal = false;
    public $deleteInvoiceId;
    public $showProductDeleteModal = false;
    public $productDeleteIndex;
    public $deliveryPrice = 0;

    protected $listeners = [
        'refreshInvoices' => '$refresh'
    ];

    public function mount()
    {
        $this->loadDrivers();
    }

    public function loadDrivers()
    {
        $this->drivers = Driver::all();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = Invoice::where('status', 0);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($q) {
                            $q->where('phone', 'like', '%' . $this->search . '%')
                                ->orWhere('address', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('id_truck', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->dateFilter) {
                $query->whereHas('customer', function ($q) {
                    $q->whereDate('today_date', $this->dateFilter);
                });
            }

            $this->selectedInvoices = $query
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedInvoices = [];
        }
    }

    public function updatingPage()
    {
        $this->selectAll = false;
        $this->selectedInvoices = [];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function openDriverModal()
    {
        if (empty($this->selectedInvoices)) {
            session()->flash('error', 'الرجاء تحديد فواتير أولاً');
            return;
        }

        $this->loadDrivers();
        $this->showDriverModal = true;
    }

    public function closeDriverModal()
    {
        $this->showDriverModal = false;
        $this->selectedDriverId = null;
        $this->deliveryPrice = 0;
    }

    public function assignToDriver()
    {
        $this->validate([
            'selectedDriverId' => 'required|exists:drivers,id',
            'deliveryPrice' => 'required|numeric|min:0',
        ], [
            'selectedDriverId.required' => 'الرجاء اختيار السائق',
            'deliveryPrice.required' => 'الرجاء إدخال سعر التوصيل',
            'deliveryPrice.numeric' => 'سعر التوصيل يجب أن يكون رقماً',
        ]);

        foreach ($this->selectedInvoices as $invoiceId) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice && $invoice->customer) {
                $invoice->customer->update([
                    'driver_id' => $this->selectedDriverId,
                    'delivery_price' => $this->deliveryPrice,
                ]);

                $invoice->update(['status' => 1]);
            }
        }

        flash()->success('تم تعيين الفواتير للسائق بنجاح');
        $this->closeDriverModal();
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    public function TrackInvoice()
    {
        $this->validate([
            'truck_number' => 'required|unique:info_invoices,number_track',
            'buyprice' => 'required|numeric|min:0',
            'selectedInvoices' => 'required|array|min:1'
        ], [
            'selectedInvoices.min' => 'تکایە بەرهەمەکێ هەلبژێرە'
        ]);

        InfoInvoice::create([
            'number_track' => $this->truck_number,
            'totalbuyprice' => $this->buyprice
        ]);

        Invoice::whereIn('id', $this->selectedInvoices)
            ->update([
                'id_truck' => $this->truck_number,
                'status' => true,
            ]);

        session()->flash('success', 'تم الحفظ بنجاح');

        $this->reset(['truck_number', 'buyprice', 'selectedInvoices', 'selectAll']);
        $this->dispatch('close-modal');
    }

    public function openAddModal($invoiceId)
    {
        $this->singleInvoiceId = $invoiceId;
        $this->truck_number = '';
        $this->existing_truck_price = null;
        $this->dispatch('open-existing-modal');
    }

    public function AddToExistingTruck()
    {
        $this->validate([
            'truck_number' => 'required|exists:info_invoices,number_track',
            'existing_truck_price' => 'required|numeric|min:0',
        ]);

        InfoInvoice::where('number_track', $this->truck_number)
            ->update(['totalbuyprice' => $this->existing_truck_price]);

        Invoice::where('id', $this->singleInvoiceId)
            ->update([
                'id_truck' => $this->truck_number,
                'status' => true,
            ]);

        session()->flash('success', 'تمت الإضافة بنجاح');

        $this->reset(['truck_number', 'existing_truck_price', 'singleInvoiceId']);
        $this->dispatch('close-modal-existing');
    }

    // Edit Modal Methods
    public function openEditModal($invoiceId)
    {
        $this->editInvoiceId = $invoiceId;
        $invoice = Invoice::with(['items.images', 'customer'])->findOrFail($invoiceId);

        $this->editInvoiceNumber = $invoice->invoice_number;
        $this->editPhone = $invoice->customer ? $invoice->customer->phone : '';
        $this->editAddress = $invoice->customer ? $invoice->customer->address : '';
        $this->editTruckNumber = $invoice->id_truck;
        $this->editTodayDate = $invoice->customer && $invoice->customer->today_date
            ? $invoice->customer->today_date->format('Y-m-d')
            : now()->format('Y-m-d');

        $this->editOrders = [];
        foreach ($invoice->items as $item) {
            $existingImages = [];
            foreach ($item->images as $image) {
                $existingImages[] = [
                    'id' => $image->id,
                    'path' => $image->image_path,
                    'url' => Storage::url($image->image_path)
                ];
            }

            $this->editOrders[] = [
                'id' => $item->id,
                'name' => $item->namecompany,
                'quantity' => $item->quantity,
                'link' => $item->link,
                'date_order' => $item->date_order ?? 1,
                'delivery_date' => $item->delivery_date instanceof \DateTime ? $item->delivery_date->format('Y-m-d') : $item->delivery_date,
                'existing_images' => $existingImages,
                'new_images' => [],
                'temp_images' => []
            ];
        }

        $this->selectedProductIndex = 0;
        $this->deletedImages = [];
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->reset([
            'showEditModal',
            'editInvoiceId',
            'editInvoiceNumber',
            'editPhone',
            'editAddress',
            'editTruckNumber',
            'editTodayDate',
            'editOrders',
            'selectedProductIndex',
            'newOrder',
            'deletedImages'
        ]);
    }

    public function addNewProduct()
    {
        $this->newOrder = [
            'name' => '',
            'quantity' => 1,
            'link' => '',
            'date_order' => 1,
            'delivery_date' => null,
            'images' => [],
            'temp_images' => []
        ];
        $this->selectedProductIndex = 'new';
    }

    public function cancelNewProduct()
    {
        $this->newOrder = [];

        if (count($this->editOrders) > 0) {
            $this->selectedProductIndex = 0;
        } else {
            $this->selectedProductIndex = null;
        }
    }

    public function addOrderToEdit()
    {
        $this->validate([
            'newOrder.name' => 'required|string|max:255',
            'newOrder.quantity' => 'required|integer|min:1',
            'newOrder.date_order' => 'required|integer|min:1',
            'newOrder.link' => 'nullable|url',
            'newOrder.delivery_date' => 'nullable|date',
        ]);

        $order = [
            'id' => null,
            'name' => $this->newOrder['name'],
            'quantity' => $this->newOrder['quantity'],
            'link' => $this->newOrder['link'] ?? null,
            'date_order' => $this->newOrder['date_order'],
            'delivery_date' => $this->newOrder['delivery_date'] ?? null,
            'existing_images' => [],
            'new_images' => $this->newOrder['images'] ?? [],
            'temp_images' => []
        ];

        $this->editOrders[] = $order;
        $this->selectedProductIndex = count($this->editOrders) - 1;
        $this->newOrder = [];
    }

    public function removeImageFromNewOrder($index)
    {
        if (isset($this->newOrder['images'][$index])) {
            unset($this->newOrder['images'][$index]);
            $this->newOrder['images'] = array_values($this->newOrder['images']);
        }
    }

    public function updatedEditOrders($value, $key)
    {
        if (str_contains($key, 'temp_images')) {
            $parts = explode('.', $key);
            $index = $parts[0];

            if (isset($this->editOrders[$index]['temp_images']) && !empty($this->editOrders[$index]['temp_images'])) {
                $uploadedFiles = $this->editOrders[$index]['temp_images'];

                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [$uploadedFiles];
                }

                foreach ($uploadedFiles as $file) {
                    if ($file && method_exists($file, 'temporaryUrl')) {
                        $this->editOrders[$index]['new_images'][] = $file;
                    }
                }

                $this->editOrders[$index]['temp_images'] = null;
            }
        }
    }

    public function updatedNewOrder($value, $key)
    {
        if ($key === 'temp_images' && !empty($this->newOrder['temp_images'])) {
            $uploadedFiles = $this->newOrder['temp_images'];

            if (!is_array($uploadedFiles)) {
                $uploadedFiles = [$uploadedFiles];
            }

            foreach ($uploadedFiles as $file) {
                if ($file && method_exists($file, 'temporaryUrl')) {
                    $this->newOrder['images'][] = $file;
                }
            }

            $this->newOrder['temp_images'] = null;
        }
    }

    public function removeExistingImage($orderIndex, $imageId)
    {
        if (isset($this->editOrders[$orderIndex])) {
            $this->deletedImages[] = $imageId;

            $this->editOrders[$orderIndex]['existing_images'] = array_filter(
                $this->editOrders[$orderIndex]['existing_images'],
                fn($img) => $img['id'] != $imageId
            );
            $this->editOrders[$orderIndex]['existing_images'] = array_values($this->editOrders[$orderIndex]['existing_images']);
        }
    }

    public function removeNewImage($orderIndex, $imageIndex)
    {
        if (isset($this->editOrders[$orderIndex]['new_images'][$imageIndex])) {
            unset($this->editOrders[$orderIndex]['new_images'][$imageIndex]);
            $this->editOrders[$orderIndex]['new_images'] = array_values($this->editOrders[$orderIndex]['new_images']);
        }
    }

    public function showProductDeleteConfirmation($index)
    {
        $this->productDeleteIndex = $index;
        $this->showProductDeleteModal = true;
    }

    public function closeProductDeleteModal()
    {
        $this->showProductDeleteModal = false;
        $this->productDeleteIndex = null;
    }

    public function deleteProductFromEdit()
    {
        if (isset($this->editOrders[$this->productDeleteIndex])) {
            if (isset($this->editOrders[$this->productDeleteIndex]['id'])) {
                foreach ($this->editOrders[$this->productDeleteIndex]['existing_images'] as $img) {
                    $this->deletedImages[] = $img['id'];
                }
            }

            unset($this->editOrders[$this->productDeleteIndex]);
            $this->editOrders = array_values($this->editOrders);

            if (count($this->editOrders) > 0) {
                $this->selectedProductIndex = min($this->productDeleteIndex, count($this->editOrders) - 1);
            } else {
                $this->selectedProductIndex = null;
            }
        }

        $this->closeProductDeleteModal();
    }

    public function saveEditedInvoice()
    {
        $this->validate([
            'editPhone' => 'required|string',
            'editAddress' => 'required|string',
            'editTruckNumber' => 'nullable|string',
            'editTodayDate' => 'nullable|date',
        ]);

        $invoice = Invoice::findOrFail($this->editInvoiceId);

        // Update invoice
        $invoice->update([
            'id_truck' => $this->editTruckNumber,
        ]);

        // Update or create customer
        if ($invoice->customer) {
            $invoice->customer->update([
                'phone' => $this->editPhone,
                'address' => $this->editAddress,
                'today_date' => $this->editTodayDate,
            ]);
        } else {
            $invoice->customer()->create([
                'phone' => $this->editPhone,
                'address' => $this->editAddress,
                'name' => $invoice->invoice_number ?? 'Customer',
                'today_date' => $this->editTodayDate,
            ]);
        }

        // Handle deleted images
        foreach ($this->deletedImages as $imageId) {
            $image = ItemImage::find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        $processedItemIds = [];

        foreach ($this->editOrders as $orderData) {
            if (isset($orderData['id'])) {
                $item = InvoiceItem::find($orderData['id']);
                if ($item) {
                    $item->update([
                        'namecompany' => $orderData['name'],
                        'quantity' => $orderData['quantity'],
                        'link' => $orderData['link'] ?? null,
                        'date_order' => $orderData['date_order'],
                        'delivery_date' => $orderData['delivery_date'],
                    ]);
                    $processedItemIds[] = $item->id;
                }
            } else {
                $item = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'namecompany' => $orderData['name'],
                    'quantity' => $orderData['quantity'],
                    'link' => $orderData['link'] ?? null,
                    'date_order' => $orderData['date_order'],
                    'delivery_date' => $orderData['delivery_date'],
                ]);
                $processedItemIds[] = $item->id;
            }

            if (!empty($orderData['new_images'])) {
                foreach ($orderData['new_images'] as $image) {
                    $path = $image->store('invoice-items', 'public');
                    ItemImage::create([
                        'invoice_item_id' => $item->id,
                        'image_path' => $path,
                    ]);
                }
            }
        }

        InvoiceItem::where('invoice_id', $invoice->id)
            ->whereNotIn('id', $processedItemIds)
            ->delete();

        session()->flash('success', 'تم تحديث الفاتورة بنجاح');
        $this->closeEditModal();
    }

    public function openDeleteModal($invoiceId)
    {
        $this->deleteInvoiceId = $invoiceId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteInvoiceId = null;
    }

    public function deleteInvoice()
    {
        $invoice = Invoice::with(['items.images', 'customer'])->find($this->deleteInvoiceId);

        if ($invoice) {
            foreach ($invoice->items as $item) {
                foreach ($item->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
                $item->delete();
            }

            if ($invoice->customer) {
                $invoice->customer->delete();
            }

            $invoice->delete();
            session()->flash('success', 'تم حذف الفاتورة بنجاح');
        }

        $this->closeDeleteModal();
    }

    public function render()
    {
        $query = Invoice::with(['customer', 'items'])
            ->withCount(['items as items_count'])
            ->addSelect([
                'total_quantity' => DB::table('invoice_items')
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('invoice_id', 'invoices.id')
            ])
            ->where('status', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('phone', 'like', '%' . $this->search . '%')
                            ->orWhere('address', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('id_truck', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            $query->whereHas('customer', function ($q) {
                $q->whereDate('today_date', $this->dateFilter);
            });
        }

        return view('livewire.invoices.show', [
            'invoices' => $query->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }
}
