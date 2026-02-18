<?php

namespace App\Livewire\Invoices;

use App\Models\InfoInvoice;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Preparation extends Component
{
    use WithPagination;

    public $editId;
    public $id_truck;
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    public $allTrucks = [];

    public $newTruckNumber;
    public $transferringInvoiceId;

    public function prepareTransfer($invoiceId)
    {
        $this->transferringInvoiceId = $invoiceId;
        $this->dispatch('open-transfer-modal');
    }

    public function transferTruck()
    {
        if ($this->newTruckNumber) {
            $invoice = Invoice::find($this->transferringInvoiceId);
            $invoice->update(['id_truck' => $this->newTruckNumber]);

            $this->dispatch('close-modal-transfer');
            $this->reset(['newTruckNumber', 'transferringInvoiceId']);

            flash()->success('تم التحويل بنجاح');
        }
    }


    public function mount()
    {
        $this->allTrucks = InfoInvoice::where('status', 0)->get();
    }
    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->perPage = 10;
        $this->resetPage();
    }


    protected $listeners = [
        'clearTruckConfirmed' => 'updateInvoice'
    ];


    public function updateInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'id_truck' => 'Peading',
            'status' => 0
        ]);

        $this->dispatch('truckCleared'); // optional success event

        flash()->success('تم مسح التراك بنجاح');
    }



    public function render()
    {
        $query = Invoice::query()
            ->withCount(['items as items_count'])
            ->addSelect([
                'total_quantity' => DB::table('invoice_items')
                    ->selectRaw('SUM(quantity)')
                    ->whereColumn('invoice_id', 'invoices.id')
            ])
            ->where('status', 1)
            ->where('is_active', 0);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('id_truck', 'like', '%' . $this->search . '%');
            });
        }

        // Apply date filters
        if (!empty($this->dateFrom)) {
            $query->whereDate('today_date', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('today_date', '<=', $this->dateTo);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.Invoices.Preparation', [
            'invoices' => $invoices,
        ]);
    }
}
