<?php

namespace App\Livewire\Invoices;



use App\Models\InfoInvoice;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class Sell extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $expandedInvoice = null;
    public $showInfoInvoice = false;

    public $selectedTruck = null;
    public $truckInvoices = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleExpand($invoiceId)
    {
        $this->expandedInvoice = $this->expandedInvoice === $invoiceId ? null : $invoiceId;
    }

    public function toggleInfoInvoice()
    {
        $this->showInfoInvoice = !$this->showInfoInvoice;
    }

    public function viewTruckInvoices($truckNumber)
    {
        $this->selectedTruck = $truckNumber;

        $this->truckInvoices = Invoice::where('id_truck', $truckNumber)
            ->orderBy('today_date', 'desc')
            ->get();
    }

    public function closeTruckInvoices()
    {
        $this->selectedTruck = null;
        $this->truckInvoices = [];
    }

    public function ordered($id)
    {
        InfoInvoice::findOrFail($id)->update([
            'status' => 0
        ]);
    }


   
    public function render()
    {
        $trucks = InfoInvoice::query()
            ->where('status', 1) 
            ->select('info_invoices.*')
            ->selectSub(function ($query) {
                $query->from('invoices')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('invoices.id_truck', 'info_invoices.number_track');
            }, 'invoice_count')
            ->when($this->search, function ($query) {
                $query->where('number_track', 'like', '%' . $this->search . '%')
                    ->orWhere('totalbuyprice', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.Invoices.Sell', [
            'trucks' => $trucks,
        ]);
    }
}
