<?php
// app/Livewire/Drivers/Insert.php

namespace App\Livewire\Drivers;

use Livewire\Component;
use App\Models\Driver;
use Livewire\WithPagination;

use function Flasher\Prime\flash;

class Insert extends Component
{
    use WithPagination;

    public $driver_id = null;
    public $name = '';
    public $phone = '';
    public $address = '';
    public $delivery_price = '';
    public $isOpen = false;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|min:3',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'delivery_price' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'name.required' => 'اسم السائق مطلوب',
        'name.min' => 'اسم السائق يجب أن يكون على الأقل 3 أحرف',
        'delivery_price.required' => 'سعر التوصيل مطلوب',
        'delivery_price.numeric' => 'سعر التوصيل يجب أن يكون رقماً',
    ];

    public function mount()
    {
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->driver_id = null;
        $this->name = '';
        $this->phone = '';
        $this->address = '';
        $this->delivery_price = '';
        $this->isEditing = false;
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatch('modal-opened');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
        $this->resetValidation();
        $this->dispatch('modal-closed');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        
        $this->driver_id = $id;
        $this->name = $driver->name;
        $this->phone = $driver->phone;
        $this->address = $driver->address;
        $this->delivery_price = $driver->delivery_price;
        
        $this->isEditing = true;
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'delivery_price' => $this->delivery_price,
        ];

        if ($this->driver_id) {
            Driver::find($this->driver_id)->update($data);
           flash()->success('تم تحديث بيانات السائق بنجاح');
        } else {
            Driver::create($data);
            flash()->success('تم إضافة السائق بنجاح');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function render()
    {
        $drivers = Driver::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.drivers.insert', [
            'drivers' => $drivers,
        ]);
    }
}