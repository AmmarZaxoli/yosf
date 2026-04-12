<?php

namespace App\Livewire\Print;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class Multi extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedInvoices = [];
    public $selectAll = false;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
        $this->selectedInvoices = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedInvoices = Invoice::query()
                ->where('status', 1)
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('invoice_number', 'like', '%' . $this->search . '%')
                            ->orWhereHas('customer', function ($customerQuery) {
                                $customerQuery->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('phone', 'like', '%' . $this->search . '%');
                            })
                            ->orWhere('id_truck', 'like', '%' . $this->search . '%');
                    });
                })
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedInvoices = [];
        }
    }

    public function updatedSelectedInvoices()
    {
        $totalInvoices = Invoice::query()
            ->where('status', 1)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('phone', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('id_truck', 'like', '%' . $this->search . '%');
                });
            })
            ->count();

        $this->selectAll = count($this->selectedInvoices) === $totalInvoices && $totalInvoices > 0;
    }

    public function openPrint()
    {
        if (empty($this->selectedInvoices)) {
            $this->dispatch('showAlert', [
                'message' => 'الرجاء اختيار فواتير للطباعة',
                'type' => 'error',
            ]);
            return;
        }

        session(['print_invoices' => $this->selectedInvoices]);

        $this->dispatch('printInHiddenFrame', url: route('print.multi.window'));
    }

    public function getInvoicesProperty()
    {
        return Invoice::query()
            ->with('customer')
            ->where('status', 1)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('phone', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('id_truck', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.print.multi', [
            'invoices' => $this->invoices,
        ]);
    }
}
