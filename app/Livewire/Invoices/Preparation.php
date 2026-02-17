<?php



namespace App\Livewire\Invoices;


use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Preparation extends Component
{
    use WithPagination;
    public $editId;
    public $id_truck;

    public function openEditModal($id)
    {
        $invoice = Invoice::findOrFail($id);

        $this->editId = $invoice->id;
        $this->id_truck = $invoice->id_truck;

        $this->dispatch('show-edit-modal');
    }

    public function updateInvoice()
    {
        // Determine the values to update
        $updateData = [
            'id_truck' => $this->id_truck ?: 'Peading', // if empty, use 'Peading'
        ];

        // If truck number is empty, also set status = 0
        if (empty($this->id_truck)) {
            $updateData['status'] = 0;
        }

        // Update the invoice
        Invoice::findOrFail($this->editId)->update($updateData);

        flash()->success('Saved Successfully');

        $this->dispatch('close-modal');
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

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);


        return view('livewire.invoices.preparation', [
            'invoices' => $invoices,
        ]);
    }
}
