<?php

namespace App\Livewire\Invoices;

use App\Models\InfoInvoice;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    use WithPagination;

    // Properties
    public $truck_number;
    public $buyprice;
    public $search = '';
    public $dateFilter = '';
    public $selectedInvoices = [];
    public $selectAll = false; // زێدەکرنا ئەڤێ بۆ چارەسەرکرنا Error

    protected $listeners = [
        'refreshInvoices' => '$refresh',
        'invoiceDetailClosed' => '$refresh'
    ];

    // دەمێ selectAll دهێتە گۆهۆڕین
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedInvoices = Invoice::where('status', 0)->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedInvoices = [];
        }
    }

    public function TrackInvoice()
    {
        // 1. ل ڤێرێ پشکنینێ بکە - ئەگەر کێشە هەبیت ل ڤێرێ دێ ڕاوەستیت و مۆداڵ نا هێتە گرتن
        $this->validate([
            'truck_number' => 'required|unique:info_invoices,number_track',
            'buyprice' => 'required|numeric|min:0',
            'selectedInvoices' => 'required|array|min:1'
        ], [
            'selectedInvoices.min' => 'تکایە بەرهەمەکێ هەلبژێرە'
        ]);

        // 2. دروستکرنا زانیاریێن تراك
        InfoInvoice::create([
            'number_track' => $this->truck_number,
            'totalbuyprice' => $this->buyprice
        ]);

        // 3. نووکرنا فواتیرێن هەلبژارتی
        Invoice::whereIn('id', $this->selectedInvoices)
            ->update([
                'id_truck' => $this->truck_number,
                'status' => true,
            ]);

        flash()->success('ب سەرکەفتی هاتە پاشکەفتن');

        // 4. پاقژکرن و داخستن
        $this->reset(['truck_number', 'buyprice', 'selectedInvoices', 'selectAll']);
        $this->dispatch('close-modal');
    }


    public $singleInvoiceId;
    public $existing_truck_price;

   
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

        // 1. نووکرنا قیمەتێ تڕاکی
        InfoInvoice::where('number_track', $this->truck_number)
            ->update(['totalbuyprice' => $this->existing_truck_price]);

        // 2. زێدەکرنا وێ فاتوورەیا دیارکری بۆ تڕاکی
        Invoice::where('id', $this->singleInvoiceId)
            ->update([
                'id_truck' => $this->truck_number,
                'status' => true,
            ]);

        flash()->success('تمت الإضافة بنجاح');

        $this->reset(['truck_number', 'existing_truck_price', 'singleInvoiceId']);
        $this->dispatch('close-modal-existing');
    }

    public function render()
    {
        $query = Invoice::query()
            ->withCount(['items as items_count'])
            ->addSelect([
                'total_quantity' => DB::table('invoice_items')
                    ->selectRaw('SUM(quantity)')
                    ->whereColumn('invoice_id', 'invoices.id')
            ])->where('status', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('id_truck', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            $query->whereDate('today_date', $this->dateFilter);
        }

        return view('livewire.invoices.show', [
            'invoices' => $query->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }
}
