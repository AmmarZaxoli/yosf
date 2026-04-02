<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Livewire\Component;

class Show extends Component
{

    public $expenses;
    public $name, $price, $namething;
    public $expenseId = null;

    public $isEdit = false;

    public function mount()
    {
        $this->loadExpenses();
    }

    // Load all expenses
    public function loadExpenses()
    {
        $this->expenses = Expense::orderBy('created_at', 'desc')->get();
    }

    // Reset input fields
    public function resetInput()
    {
        $this->name = '';
        $this->price = '';
        $this->namething = '';
        $this->expenseId = null;
        $this->isEdit = false;
    }

    // Create new expense
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'namething' => 'required|string|max:255',
        ]);

        Expense::create([
            'name' => $this->name,
            'price' => $this->price,
            'namething' => $this->namething,
        ]);

        session()->flash('success', 'Expense added successfully!');
        $this->resetInput();
        $this->loadExpenses();
    }

    // Edit an expense
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->expenseId = $expense->id;
        $this->name = $expense->name;
        $this->price = $expense->price;
        $this->namething = $expense->namething;
        $this->isEdit = true;
    }

    // Update expense
    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'namething' => 'required|string|max:255',
        ]);

        $expense = Expense::findOrFail($this->expenseId);
        $expense->update([
            'name' => $this->name,
            'price' => $this->price,
            'namething' => $this->namething,
        ]);

        session()->flash('success', 'Expense updated successfully!');
        $this->resetInput();
        $this->loadExpenses();
    }

    // Delete expense
    public function delete($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        session()->flash('success', 'Expense deleted successfully!');
        $this->loadExpenses();
    }

    public function render()
    {
        return view('livewire.expenses.show');
    }
}
