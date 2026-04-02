<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ItemImage;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

use function Flasher\Prime\flash;

class Insert extends Component
{
    use WithFileUploads;

    // Form fields
    public $invoice_number = null;
    public $namecustomer = ''; // Customer name
    public $phone = '';
    public $address = '';
    public $id_truck = 'Peading';
    public $namecompany = ''; // Company name
    public $total_price = 0;
    public $link = '';
    public $productprice;
    public $temp_images = [];
    public $images = [];
    public $quantity = 1;
    public $delivery_days = '';
    public $custom_delivery_days = null;
    public $today_date;
    public $delivery_date = '';
    public $selected_user = 1;
    public $orders = [];

    // States
    public $invoice_button_disabled = false;
    public $customer_fields_disabled = false;
    public $invoice_counter = 1001;

    // Constants
    const MAX_IMAGES_PER_ITEM = 6;

    public function mount()
    {
        $this->today_date = now()->format('Y-m-d');
        $this->invoice_counter = $this->getNextInvoiceNumber();

        $this->orders = $this->orders ?? [];
        $this->images = $this->images ?? [];
        $this->temp_images = $this->temp_images ?? [];

        if (!$this->delivery_days) {
            $this->delivery_days = '';
        }

        $this->calculateDeliveryDate();
        $this->calculateTotalFromOrders();
    }

    public function validateAndAddOrder()
    {
        if ($this->link && strlen($this->link) >= 5000) {
            flash()->error('الرابط طويل جداً، الحد الأقصى هو 5000 حرف');
            return;
        }

        if (count($this->images) > self::MAX_IMAGES_PER_ITEM) {
            flash()->error('يمكنك تحميل 6 صور كحد أقصى');
            return;
        }

        $this->validate([
            'invoice_number' => 'required',
            'selected_user' => 'required',
            'namecustomer' => 'nullable|string|min:2',
            'namecompany' => 'nullable|string|min:2',
            'phone' => $this->customer_fields_disabled ? '' : 'nullable|string|min:7',
            'address' => $this->customer_fields_disabled ? '' : 'nullable|string|min:1',
            'id_truck' => $this->customer_fields_disabled ? '' : 'nullable|string|min:3',
            'productprice' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            // 'delivery_days' => 'required|integer|min:1',
            'today_date' => 'required|date',
            'link' => 'nullable|url|max:5000',
            'temp_images.*' => 'nullable|image|max:2048',
        ], [
            'invoice_number.required' => 'رقم الفاتورة مطلوب',
            'selected_user.required' => 'المستخدم المسؤول مطلوب',
            'namecustomer.min' => 'اسم العميل يجب أن يكون على الأقل حرفين',
            'namecompany.min' => 'اسم الشركة يجب أن يكون على الأقل حرفين',
            'phone.min' => 'رقم الموبايل يجب أن يكون على الأقل 10 أرقام',
            'address.min' => 'العنوان يجب أن يكون على الأقل 5 أحرف',
            'id_truck.min' => 'رقم التراك يجب أن يكون على الأقل 3 أحرف',
            'productprice.required' => 'سعر المنتج مطلوب',
            'productprice.numeric' => 'سعر المنتج يجب أن يكون رقماً',
            'productprice.min' => 'سعر المنتج يجب أن يكون أكبر من 0',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'الكمية يجب أن تكون رقماً',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'delivery_days.required' => 'مدة التوصيل مطلوبة',
            'delivery_days.integer' => 'مدة التوصيل يجب أن تكون رقماً',
            'delivery_days.min' => 'مدة التوصيل يجب أن تكون على الأقل يوم واحد',
            'today_date.required' => 'تاريخ اليوم مطلوب',
            'today_date.date' => 'تاريخ اليوم غير صحيح',
            'link.url' => 'الرابط يجب أن يكون رابطاً صحيحاً',
            'temp_images.*.image' => 'يجب أن تكون الملفات صوراً',
            'temp_images.*.max' => 'الصورة يجب أن لا تتجاوز 2 ميجابايت',
        ]);

        $this->addOrder();
    }

    public function calculateTotalFromOrders()
    {
        $total = 0;
        foreach ($this->orders as $order) {
            $total += ($order['price'] * $order['quantity']);
        }

        $this->total_price = $total;
        return $total;
    }

    public function calculateDeliveryDate()
    {
        if ($this->today_date && $this->delivery_days) {

            $days = (int) $this->delivery_days;

            $this->delivery_date = Carbon::parse($this->today_date)
                ->addDays($days)
                ->format('Y-m-d');
        } else {

            $this->delivery_date = $this->today_date;
        }
    }

    public function updatedTodayDate()
    {
        $this->calculateDeliveryDate();
    }

    public function updatedDeliveryDays()
    {
        $this->calculateDeliveryDate();
    }

    public function addNewInvoice()
    {
        $this->invoice_number = $this->invoice_counter;
        $this->invoice_counter++;
        $this->invoice_button_disabled = true;
    }

    public function updatedTempImages()
    {
        if (!$this->temp_images) {
            return;
        }

        if (!is_array($this->temp_images)) {
            $this->temp_images = [$this->temp_images];
        }

        $currentCount = count($this->images);
        $maxCanAdd = self::MAX_IMAGES_PER_ITEM - $currentCount;

        if ($maxCanAdd <= 0) {
            flash()->error('لقد وصلت إلى الحد الأقصى 6 صور');
            $this->temp_images = [];
            return;
        }

        $imagesToAdd = array_slice($this->temp_images, 0, $maxCanAdd);

        foreach ($imagesToAdd as $image) {
            if ($image) {
                $this->images[] = $image;
            }
        }

        if (count($this->temp_images) > $maxCanAdd) {
            flash()->warning("تم إضافة $maxCanAdd صور فقط (الحد الأقصى 6 صور)");
        }

        $this->temp_images = [];
    }

    public function addOrder()
    {
        if (count($this->images) > self::MAX_IMAGES_PER_ITEM) {
            flash()->error('يمكنك تحميل 6 صور كحد أقصى');
            return;
        }

        $this->orders = $this->orders ?? [];
        $this->images = $this->images ?? [];

        $storedImages = [];
        foreach ($this->images as $image) {
            if ($image) {
                if (is_object($image) && method_exists($image, 'store')) {
                    $storedImages[] = $image;
                } else {
                    $storedImages[] = $image;
                }
            }
        }

        $todayDate = $this->today_date ?: now()->format('Y-m-d');

        // Calculate delivery date properly
        $daysToAdd = 1; // Default value
        if ($this->delivery_days !== '' && $this->delivery_days !== null && is_numeric($this->delivery_days)) {
            $daysToAdd = (int)$this->delivery_days;
        }

        // Make sure it's at least 1
        if ($daysToAdd < 1) {
            $daysToAdd = 1;
        }

        $deliveryDate = Carbon::parse($todayDate)
            ->addDays($daysToAdd)
            ->format('Y-m-d');

        $this->orders[] = [
            'id' => uniqid(),
            'link' => $this->link,
            'namecompany' => $this->namecompany,
            'images' => $storedImages,
            'price' => (float) $this->productprice,
            'quantity' => (int) $this->quantity,
            'delivery_days' => $daysToAdd,
            'today_date' => $todayDate,
            'delivery_date' => $deliveryDate,
        ];

        if (count($this->orders) === 1) {
            $this->customer_fields_disabled = true;
        }

        $this->calculateTotalFromOrders();
        $this->resetOrderFields();
    }
    public function saveAll()
    {
        if ($this->total_price <= 0) {
            flash()->error('يرجى إدخال مبلغ البيع');
            return;
        }

        DB::transaction(function () {
            // Create the invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'user_id' => $this->selected_user,
                'id_truck' => $this->id_truck,
                'status' => $this->id_truck != "Peading" ? 1 : 0,
                'total_price' => $this->total_price,
            ]);

            // Create customer record with both dates
            if ($this->namecustomer || $this->phone || $this->address) {
                $customer = Customer::create([
                    'name' => $this->namecustomer,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'date_order' => $this->orders[0]['today_date'],
                    'delivery_date' => $this->orders[0]['delivery_date'],
                    'invoice_id' => $invoice->id,
                ]);
            }

            // Create invoice items from orders
            foreach ($this->orders as $order) {
                $item = $invoice->items()->create([
                    'namecompany' => $order['namecompany'] ?? $this->namecompany,
                    'link' => $order['link'],
                    'productprice' => $order['price'] ?? 0,
                    'quantity' => $order['quantity'],
                    // You can also store item-specific dates if needed
                    'today_date' => $order['today_date'] ?? $this->today_date,
                    'delivery_date' => $order['delivery_date'] ?? $this->delivery_date,
                ]);

                // Save images for this item
                if (!empty($order['images'])) {
                    $savedImages = [];

                    foreach ($order['images'] as $image) {
                        if (is_object($image) && method_exists($image, 'store')) {
                            $path = $image->store('products', 'public');
                            $savedImages[] = ['image_path' => $path];
                        } elseif (is_string($image)) {
                            $savedImages[] = ['image_path' => $image];
                        }
                    }

                    if (!empty($savedImages)) {
                        $item->images()->createMany($savedImages);
                    }
                }
            }

            flash()->success('تم حفظ الفاتورة بنجاح!');
            $this->clearAll();
        });
    }

    private function resetOrderFields()
    {
        $this->link = '';
        $this->productprice="";
        $this->images = [];
        $this->temp_images = [];
        $this->quantity = 1;
        $this->delivery_days = '';
        $this->custom_delivery_days = null;
        // Don't call calculateDeliveryDate here as it might reset the date
        // Instead, set delivery_date based on current values
        if (!empty($this->delivery_days) && is_numeric($this->delivery_days)) {
            $this->calculateDeliveryDate();
        } else {
            $this->delivery_date = $this->today_date;
        }
    }

    public function resetAll()
    {
        $this->orders = [];
        $this->invoice_number = null;
        $this->invoice_counter = $this->getNextInvoiceNumber();
        $this->invoice_button_disabled = false;
        $this->namecustomer = '';
        $this->phone = '';
        $this->address = '';
        $this->id_truck = 'Peading';
        $this->namecompany = '';
        $this->total_price = 0;
        $this->customer_fields_disabled = false;
        $this->resetOrderFields();
        $this->today_date = now()->format('Y-m-d');
        $this->custom_delivery_days = null;
        $this->delivery_days = '';
        $this->calculateDeliveryDate();
    }

    public function clearAll()
    {
        $this->resetAll();
    }

    public function removeOrder($index)
    {
        $this->orders = $this->orders ?? [];

        if (isset($this->orders[$index])) {
            unset($this->orders[$index]);
            $this->orders = array_values($this->orders);

            if (empty($this->orders)) {
                $this->customer_fields_disabled = false;
            }

            $this->calculateTotalFromOrders();
        }
    }

    public function clearAllOrders()
    {
        $this->orders = [];
        $this->customer_fields_disabled = false;
        $this->calculateTotalFromOrders();
    }

    public function removeImage($index)
    {
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
        $this->orders = $this->orders ?? [];
        $this->images = $this->images ?? [];
        $this->temp_images = $this->temp_images ?? [];

        return view('livewire.invoices.insert');
    }
}
