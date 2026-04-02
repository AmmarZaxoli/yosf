<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Expense;
use Carbon\Carbon;

class Create extends Component
{
    public $dateFrom;
    public $dateTo;

    public $totalInvoices = null;
    public $totalExpenses = null;
    public $invoices = null;
    public $expenses = null;

    public function mount()
    {
        $today = Carbon::now()->format('Y-m-d');
        $this->dateFrom = $today;
        $this->dateTo = $today;

        $this->totalInvoices = null;
        $this->totalExpenses = null;
        $this->invoices = null;
        $this->expenses = null;
    }

    public function resetFilters()
    {
        $today = Carbon::now()->format('Y-m-d');
        $this->reset(['dateFrom', 'dateTo']);
        $this->dateFrom = $today;
        $this->dateTo = $today;

        $this->totalInvoices = null;
        $this->totalExpenses = null;
        $this->invoices = null;
        $this->expenses = null;
    }

    public function loadData()
    {
        // Invoices
        $invoiceQuery = Invoice::where('is_active', 1);
        if ($this->dateFrom) $invoiceQuery->whereDate('created_at', '>=', $this->dateFrom);
        if ($this->dateTo) $invoiceQuery->whereDate('created_at', '<=', $this->dateTo);

        $this->totalInvoices = $invoiceQuery->sum('total_price');
        $this->invoices = $invoiceQuery->orderBy('created_at', 'desc')->get();

        // Expenses
        $expenseQuery = Expense::query();
        if ($this->dateFrom) $expenseQuery->whereDate('created_at', '>=', $this->dateFrom);
        if ($this->dateTo) $expenseQuery->whereDate('created_at', '<=', $this->dateTo);

        $this->totalExpenses = $expenseQuery->sum('price');
        $this->expenses = $expenseQuery->orderBy('created_at', 'desc')->get();
    }

    // Computed property for net profit
    public function getNetProfitProperty()
    {
        return ($this->totalInvoices ?? 0) - ($this->totalExpenses ?? 0);
    }

    public function render()
    {
        return view('livewire.accounting.create');
    }
}
