<?php
namespace App\Livewire\Paying;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Driver;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class Pay extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedDriverId = null;
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedInvoices = [];
    public $selectAll = false;
    public $showBulkDriverModal = false;
    public $bulkDriverId = null;
    public $drivers = [];
    
    // Change Delivery Date Modal Properties
    public $showChangeDateModal = false;
    public $newDeliveryDate = '';

    protected $listeners = [
        'refreshInvoices' => '$refresh',
        'processPayment' => 'processPayment',
        'processChangeDate' => 'updateDeliveryDate'
    ];

    public function mount()
    {
        $this->loadDrivers();
        $this->newDeliveryDate = now()->format('Y-m-d');
    }

    public function loadDrivers()
    {
        $this->drivers = Driver::all();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $unpaidInvoices = Invoice::where('status', 1)
                ->where('is_active', 0)
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('invoice_number', 'like', '%' . $this->search . '%')
                            ->orWhereHas('customer', function ($customerQuery) {
                                $customerQuery->where('phone', 'like', '%' . $this->search . '%');
                            })
                            ->orWhere('id_truck', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->selectedDriverId, function ($query) {
                    $query->whereHas('customer', function ($customerQuery) {
                        $customerQuery->where('driver_id', $this->selectedDriverId);
                    });
                })
                ->when($this->dateFrom, function ($query) {
                    $query->whereHas('customer', function ($customerQuery) {
                        $customerQuery->whereDate('today_date', '>=', $this->dateFrom);
                    });
                })
                ->when($this->dateTo, function ($query) {
                    $query->whereHas('customer', function ($customerQuery) {
                        $customerQuery->whereDate('today_date', '<=', $this->dateTo);
                    });
                })
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
            
            $this->selectedInvoices = $unpaidInvoices;
        } else {
            $this->selectedInvoices = [];
        }
    }

    public function updatedSelectedInvoices()
    {
        $totalUnpaid = Invoice::where('status', 1)
            ->where('is_active', 0)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('phone', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('id_truck', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedDriverId, function ($query) {
                $query->whereHas('customer', function ($customerQuery) {
                    $customerQuery->where('driver_id', $this->selectedDriverId);
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereHas('customer', function ($customerQuery) {
                    $customerQuery->whereDate('today_date', '>=', $this->dateFrom);
                });
            })
            ->when($this->dateTo, function ($query) {
                $query->whereHas('customer', function ($customerQuery) {
                    $customerQuery->whereDate('today_date', '<=', $this->dateTo);
                });
            })
            ->count();
        
        $this->selectAll = count($this->selectedInvoices) === $totalUnpaid && $totalUnpaid > 0;
    }

    public function openBulkDriverModal()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'الرجاء تحديد فواتير أولاً'
            ]);
            return;
        }
        
        $this->loadDrivers();
        $this->showBulkDriverModal = true;
    }

    public function closeBulkDriverModal()
    {
        $this->showBulkDriverModal = false;
        $this->bulkDriverId = null;
        $this->resetValidation();
        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'تم إغلاق النافذة بنجاح'
        ]);
    }

    public function updateBulkDriver()
    {
        $this->validate([
            'bulkDriverId' => 'required|exists:drivers,id',
        ], [
            'bulkDriverId.required' => 'الرجاء اختيار السائق',
        ]);

        $invoices = Invoice::whereIn('id', $this->selectedInvoices)->with('customer')->get();
        
        foreach ($invoices as $invoice) {
            if ($invoice->customer) {
                $invoice->customer->update([
                    'driver_id' => $this->bulkDriverId,
                ]);
            }
        }

        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'تم تغيير السائق للفواتير المحددة بنجاح'
        ]);

        $this->closeBulkDriverModal();
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    // Change Delivery Date Methods
    public function openChangeDateModal()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'الرجاء تحديد فواتير أولاً'
            ]);
            return;
        }

        $this->showChangeDateModal = true;
    }

    public function closeChangeDateModal()
    {
        $this->showChangeDateModal = false;
        $this->newDeliveryDate = now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function confirmChangeDate()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'الرجاء تحديد فواتير أولاً'
            ]);
            return;
        }

        $this->validate([
            'newDeliveryDate' => 'required|date',
        ], [
            'newDeliveryDate.required' => 'الرجاء إدخال تاريخ التوصيل',
            'newDeliveryDate.date' => 'تاريخ التوصيل يجب أن يكون تاريخ صحيح',
        ]);

        $this->dispatch('confirmDateChange');
    }

    public function updateDeliveryDate()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'الرجاء تحديد فواتير أولاً'
            ]);
            return;
        }

        $invoices = Invoice::whereIn('id', $this->selectedInvoices)->with('customer')->get();
        
        foreach ($invoices as $invoice) {
            if ($invoice->customer) {
                $invoice->customer->update([
                    'delivery_date' => $this->newDeliveryDate,
                ]);
            }
        }

        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'تم تحديث تاريخ التوصيل بنجاح'
        ]);

        $this->closeChangeDateModal();
        $this->selectedInvoices = [];
        $this->selectAll = false;
        $this->dispatch('$refresh');
    }

    public function confirmPayment()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'الرجاء تحديد فواتير أولاً'
            ]);
            return;
        }
        
        $this->dispatch('showPaymentConfirmation');
    }

    public function processPayment()
    {
        Invoice::whereIn('id', $this->selectedInvoices)
            ->update([
                'is_active' => 1,
            ]);

        $this->selectedInvoices = [];
        $this->selectAll = false;

        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'تم تأكيد دفع الفواتير المحددة بنجاح'
        ]);
        
        $this->dispatch('$refresh');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedDriverId', 'dateFrom', 'dateTo']);
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    public function getInvoicesProperty()
    {
        $query = Invoice::query()
            ->with(['customer.driver'])
            ->withCount(['items as items_count'])
            ->addSelect([
                'total_quantity' => DB::table('invoice_items')
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('invoice_id', 'invoices.id')
            ])
            ->where('status', 1)
            ->where('is_active', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('phone', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('id_truck', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedDriverId) {
            $query->whereHas('customer', function ($customerQuery) {
                $customerQuery->where('driver_id', $this->selectedDriverId);
            });
        }

        if ($this->dateFrom) {
            $query->whereHas('customer', function ($customerQuery) {
                $customerQuery->whereDate('delivery_date', '>=', $this->dateFrom);
            });
        }

        if ($this->dateTo) {
            $query->whereHas('customer', function ($customerQuery) {
                $customerQuery->whereDate('delivery_date', '<=', $this->dateTo);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function render()
    {
        $invoices = $this->invoices;
        
        $totalAmount = 0;
        $unpaidCount = 0;
        $paidCount = 0;
        
        foreach ($invoices as $invoice) {
            $totalAmount += $invoice->total_price ?? 0;
            if ($invoice->is_active == 0) {
                $unpaidCount++;
            } else {
                $paidCount++;
            }
        }
        
        return view('livewire.paying.pay', [
            'invoices' => $invoices,
            'totalInvoices' => $invoices->total(),
            'totalAmount' => $totalAmount,
            'unpaidCount' => $unpaidCount,
            'paidCount' => $paidCount,
        ]);
    }
}