<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFilter = '';

    protected $listeners = [
        'refreshInvoices' => '$refresh',
        'invoiceDetailClosed' => '$refresh'
    ];

    public function render()
    {
        $query = Invoice::query()
            ->withCount(['items as items_count'])
            ->addSelect([
                'total_quantity' => DB::table('invoice_items')
                    ->selectRaw('SUM(quantity)')
                    ->whereColumn('invoice_id', 'invoices.id')
            ]);

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%')
                  ->orWhere('truck_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('items', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply date filter
        if ($this->dateFilter) {
            $query->whereDate('today_date', $this->dateFilter);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.invoices.show', [
            'invoices' => $invoices,
        ]);
    }
}