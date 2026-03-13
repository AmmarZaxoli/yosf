<?php

namespace App\Livewire\Paying;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class ReturnPay extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedInvoices = [];
    public $selectAll = false;
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';

    // Change Delivery Date Modal Properties
    public $showChangeDateModal = false;
    public $newDeliveryDate = '';

    protected $listeners = [
        'refreshInvoices' => '$refresh',
        'processReturn' => 'returnPayment',
        'processChangeDate' => 'updateDeliveryDate'
    ];

    public function mount()
    {
        $this->newDeliveryDate = now()->format('Y-m-d');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedInvoices = $this->getFilteredQuery()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedInvoices = [];
        }
    }

    public function updatedSelectedInvoices()
    {
        $totalInvoices = $this->getFilteredQuery()->count();
        $this->selectAll = count($this->selectedInvoices) === $totalInvoices && $totalInvoices > 0;
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    public function updatedDateTo()
    {
        $this->resetPage();
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateFrom', 'dateTo']);
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    private function getFilteredQuery()
    {
        $query = Invoice::with('customer')
            ->where('status', 1)
            ->where('is_active', 1);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('phone', 'like', '%' . $this->search . '%')
                            ->orWhere('address', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%');
                    });
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

        return $query;
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

    public function confirmReturn()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'الرجاء تحديد فواتير للإرجاع'
            ]);
            return;
        }

        $this->dispatch('confirmReturn');
    }

    public function returnPayment()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'الرجاء تحديد فواتير للإرجاع'
            ]);
            return;
        }

        Invoice::whereIn('id', $this->selectedInvoices)
            ->update([
                'is_active' => 0
            ]);

        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'تم ارجاع الدفع بنجاح'
        ]);

        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    public function render()
    {
        $invoices = $this->getFilteredQuery()
            ->latest()
            ->paginate(10);

        // Calculate totals
        $totalInvoices = $this->getFilteredQuery()->count();
        $totalAmount = $this->getFilteredQuery()->sum('total_price');
        $paidCount = $totalInvoices;

        return view('livewire.paying.return-pay', [
            'invoices' => $invoices,
            'totalInvoices' => $totalInvoices,
            'totalAmount' => $totalAmount,
            'paidCount' => $paidCount,
        ]);
    }
}