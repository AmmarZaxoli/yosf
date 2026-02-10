<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ItemImage;
use Livewire\Attributes\Session;
use Illuminate\Support\Facades\DB;

use function Flasher\Prime\flash;

class Insert extends Component
{
    use WithFileUploads;

    // Form fields
    public $invoice_number = null;
    public $phone = '';
    public $address = '';
    public $truck_number = '';
    public $link = '';
    public $name = '';
    public $temp_images = [];
    public $images = [];
    public $quantity = 1;
    public $date_order = 1;
    public $today_date;
    public $delivery_date = '';
    public $selected_user = 1;
    public $orders = [];

    // States
    public $invoice_button_disabled = false;
    public $customer_fields_disabled = false;
    public $invoice_counter = 1001;
    
    // Constants
    const MAX_IMAGES_PER_ITEM = 6; // Maximum 6 images per product

    public function validateAndAddOrder()
    {
        if ($this->link && strlen($this->link) >= 5000) {
            flash()->error('الرابط طويل جداً، الحد الأقصى هو 5000 حرف');
            return; 
        }
        
        // Validate image count before proceeding
        if (count($this->images) > self::MAX_IMAGES_PER_ITEM) {
            flash()->error('يمكنك تحميل 6 صور كحد أقصى');
            return;
        }
        
        $this->validate([
            'invoice_number' => 'required',
            'selected_user' => 'required',

            'phone' => $this->customer_fields_disabled ? '' : 'required',
            'address' => $this->customer_fields_disabled ? '' : 'required',
            'truck_number' => $this->customer_fields_disabled ? '' : 'required',

            'name' => 'required|string|min:2',
            'quantity' => 'required|integer|min:1',
            'date_order' => 'required|integer|min:1',
            'today_date' => 'required|date',

            'link' => 'nullable|url|max:5000',
            'temp_images.*' => 'nullable|image|max:2048',
        ], [
            // Invoice errors
            'invoice_number.required' => 'رقم الفاتورة مطلوب',
            'selected_user.required' => 'المستخدم المسؤول مطلوب',

            // Customer errors
            'phone.required' => 'رقم الموبايل مطلوب',
            'address.required' => 'العنوان مطلوب',
            'truck_number.required' => 'رقم التراك مطلوب',

            // Product errors
            'name.required' => 'اسم المنتج مطلوب',
            'name.min' => 'اسم المنتج يجب أن يكون على الأقل حرفين',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'الكمية يجب أن تكون رقماً',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'date_order.required' => 'مدة التوصيل مطلوبة',
            'date_order.integer' => 'مدة التوصيل يجب أن تكون رقماً',
            'date_order.min' => 'مدة التوصيل يجب أن تكون على الأقل يوم واحد',
            'today_date.required' => 'تاريخ اليوم مطلوب',
            'today_date.date' => 'تاريخ اليوم غير صحيح',

            // Optional fields errors
            'link.url' => 'الرابط يجب أن يكون رابطاً صحيحاً',
            'temp_images.*.image' => 'يجب أن تكون الملفات صوراً',
            'temp_images.*.max' => 'الصورة يجب أن لا تتجاوز 2 ميجابايت',
        ]);

        // If validation passes, call the original addOrder method
        $this->addOrder();
    }

    protected $rules = [
        'invoice_number' => 'required|integer|min:1',
        'phone' => 'required|string|min:10',
        'address' => 'required|string|min:5',
        'truck_number' => 'required|string|min:3',
        'orders' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->today_date = now()->format('Y-m-d');
        $this->invoice_counter = $this->getNextInvoiceNumber();

        // Initialize arrays if they're null
        $this->orders = $this->orders ?? [];
        $this->images = $this->images ?? [];
        $this->temp_images = $this->temp_images ?? [];

        $this->calculateDeliveryDate();
    }

    public function calculateDeliveryDate()
    {
        if ($this->today_date && $this->date_order && is_numeric($this->date_order)) {
            try {
                $this->delivery_date = Carbon::parse($this->today_date)
                    ->addDays((int) $this->date_order)
                    ->format('Y-m-d');
            } catch (\Exception $e) {
                $this->delivery_date = now()->format('Y-m-d');
            }
        } else {
            $this->delivery_date = now()->format('Y-m-d');
        }
    }

    public function updated($property)
    {
        if (in_array($property, ['today_date', 'date_order'])) {
            $this->calculateDeliveryDate();
        }

        // Handle image uploads for backward compatibility
        if ($property === 'temp_images' && $this->temp_images) {
            $this->updatedTempImages();
        }
    }

    // Generate new invoice number
    public function addNewInvoice()
    {
        $this->invoice_number = $this->invoice_counter;
        $this->invoice_counter++;
        $this->invoice_button_disabled = true;
    }

    // Handle image upload - UPDATED with limit check
    public function updatedTempImages()
    {
        if (!$this->temp_images) {
            return;
        }

        // Ensure temp_images is an array
        if (!is_array($this->temp_images)) {
            $this->temp_images = [$this->temp_images];
        }

        // Calculate how many more images we can add
        $currentCount = count($this->images);
        $maxCanAdd = self::MAX_IMAGES_PER_ITEM - $currentCount;
        
        if ($maxCanAdd <= 0) {
            flash()->error('لقد وصلت إلى الحد الأقصى 6 صور');
            $this->temp_images = [];
            return;
        }

        // Limit the number of new images to what we can still add
        $imagesToAdd = array_slice($this->temp_images, 0, $maxCanAdd);
        
        // Validate each image and add to images array
        foreach ($imagesToAdd as $image) {
            if ($image) {
                $this->images[] = $image;
            }
        }
        
        // If user tried to upload more than allowed, show warning
        if (count($this->temp_images) > $maxCanAdd) {
            flash()->warning("تم إضافة $maxCanAdd صور فقط (الحد الأقصى 6 صور)");
        }

        // Clear temp_images after processing
        $this->temp_images = [];
    }

    // Add order to list - UPDATED with image limit check
    public function addOrder()
    {
        // Validate image count
        if (count($this->images) > self::MAX_IMAGES_PER_ITEM) {
            flash()->error('يمكنك تحميل 6 صور كحد أقصى');
            return;
        }

        // Ensure arrays are initialized
        $this->orders = $this->orders ?? [];
        $this->images = $this->images ?? [];

        // Store images
        $storedImages = [];
        foreach ($this->images as $image) {
            if ($image) {
                if (is_object($image) && method_exists($image, 'store')) {
                    // For new uploads
                    $storedImages[] = $image;
                } else {
                    // For existing images
                    $storedImages[] = $image;
                }
            }
        }

        // Calculate dates
        $todayDate = $this->today_date ?: now()->format('Y-m-d');
        $deliveryDate = Carbon::parse($todayDate)
            ->addDays((int) $this->date_order)
            ->format('Y-m-d');

        // Add to orders array
        $this->orders[] = [
            'id' => uniqid(),
            'link' => $this->link,
            'name' => $this->name,
            'images' => $storedImages,
            'quantity' => (int) $this->quantity,
            'date_order' => (int) $this->date_order,
            'today_date' => $todayDate,
            'delivery_date' => $deliveryDate,
        ];

        // Disable customer fields after adding first product
        if (count($this->orders) === 1) {
            $this->customer_fields_disabled = true;
        }

        // Clear order-specific fields
        $this->resetOrderFields();
    }

    public function saveAll()
    {
        DB::transaction(function () {
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'user_id' => $this->selected_user,
                'phone' => $this->phone,
                'address' => $this->address,
                'truck_number' => $this->truck_number,
                'today_date' => $this->today_date,
            ]);

            foreach ($this->orders as $order) {
                $item = $invoice->items()->create([
                    'name' => $order['name'],
                    'link' => $order['link'],
                    'quantity' => $order['quantity'],
                    'date_order' => $order['date_order'],
                    'delivery_date' => $order['delivery_date'],
                ]);

                if (!empty($order['images'])) {
                    $savedImages = [];

                    foreach ($order['images'] as $image) {
                        if (is_object($image) && method_exists($image, 'store')) {
                            // store in "storage/app/public/products"
                            $path = $image->store('products', 'public');
                            $savedImages[] = ['image_path' => $path];
                        } elseif (is_string($image)) {
                            // already saved image
                            $savedImages[] = ['image_path' => $image];
                        }
                    }

                    if (!empty($savedImages)) {
                        $item->images()->createMany($savedImages);
                    }
                }
            }

            flash()->success('تم حفظ الفاتورة بنجاح!');

            // Reset all fields
            $this->clearAll();
        });
    }

    // Helper Methods
    private function resetOrderFields()
    {
        $this->link = '';
        $this->name = '';
        $this->images = [];
        $this->temp_images = [];
        $this->quantity = 1;
        $this->date_order = 1;
        $this->calculateDeliveryDate(); // Recalculate delivery date
    }

    public function resetAll()
    {
        $this->orders = [];
        $this->invoice_number = null;
        $this->invoice_counter = $this->getNextInvoiceNumber();
        $this->invoice_button_disabled = false;
        $this->phone = '';
        $this->address = '';
        $this->truck_number = '';
        $this->customer_fields_disabled = false;
        $this->resetOrderFields();
        $this->today_date = now()->format('Y-m-d');
        $this->calculateDeliveryDate();
    }

    public function clearAll()
    {
        $this->resetAll();
    }

    public function removeOrder($index)
    {
        // Ensure orders is an array
        $this->orders = $this->orders ?? [];

        if (isset($this->orders[$index])) {
            unset($this->orders[$index]);
            $this->orders = array_values($this->orders);

            if (empty($this->orders)) {
                $this->customer_fields_disabled = false;
            }
        }
    }

    public function clearAllOrders()
    {
        $this->orders = [];
        $this->customer_fields_disabled = false;
    }

    public function removeImage($index)
    {
        // Ensure images is an array
        $this->images = $this->images ?? [];

        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function clearAllImages()
    {
        $this->images = [];
        $this->temp_images = [];
    }

    private function getNextInvoiceNumber()
    {
        try {
            $lastInvoice = Invoice::max('invoice_number');
            return $lastInvoice ? $lastInvoice + 1 : 1001;
        } catch (\Exception $e) {
            return 1001;
        }
    }

    public function render()
    {
        // Ensure all arrays are initialized before rendering
        $this->orders = $this->orders ?? [];
        $this->images = $this->images ?? [];
        $this->temp_images = $this->temp_images ?? [];

        return view('livewire.invoices.insert');
    }
}