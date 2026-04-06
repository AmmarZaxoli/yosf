<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;

class Login extends Component
{
    public $name = '';
    public $password = '';
    public $remember = false;
    public $errorMessage = '';
    public $showPassword = false;
    public $accounts = [];
    public $searchTerm = '';
    public $showDropdown = false;
    public $selectedAccount = '';

    protected $rules = [
        'name' => 'required|string|exists:accounts,name',
        'password' => 'required|string',
    ];

    protected $messages = [
        'name.required' => 'الرجاء اختيار اسم المستخدم',
        'name.exists' => 'اسم المستخدم غير موجود في النظام',
        'password.required' => 'الرجاء إدخال كلمة المرور',
    ];

    public function mount()
    {
        $this->loadAccounts();
        $this->errorMessage = '';
        $this->name = '';
        $this->password = '';
        $this->searchTerm = '';
        $this->showDropdown = false;
        $this->selectedAccount = '';
    }

    public function loadAccounts()
    {
        $this->accounts = Account::select('name', 'role')->get();
    }

    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) > 0) {
            $this->accounts = Account::where('name', 'like', '%' . $this->searchTerm . '%')
                ->select('name', 'role')
                ->get();
            $this->showDropdown = true;
        } else {
            $this->loadAccounts();
            $this->showDropdown = false;
        }
    }

    public function selectAccount($accountName)
    {
        $this->name = $accountName;
        $this->selectedAccount = $accountName;
        $this->searchTerm = $accountName;
        $this->showDropdown = false;
        $this->errorMessage = '';
    }

    public function updatedName()
    {
        $this->searchTerm = $this->name;
        $this->errorMessage = '';
        if ($this->name != $this->selectedAccount) {
            $this->selectedAccount = '';
        }
    }

    public function login()
{
    $this->errorMessage = '';

    if (empty($this->name)) {
        $this->errorMessage = 'الرجاء إدخال اسم المستخدم';
        return;
    }

    if (empty($this->password)) {
        $this->errorMessage = 'الرجاء إدخال كلمة المرور';
        return;
    }

    $this->validate();

    $account = Account::where('name', $this->name)->first();

    if (!$account) {
        $this->errorMessage = 'اسم المستخدم غير موجود';
        $this->password = '';
        return;
    }

    if (trim($this->password) === trim($account->password)) {
        Auth::login($account, $this->remember);
        session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    $this->errorMessage = 'كلمة المرور غير صحيحة. يرجى المحاولة مرة أخرى';
    $this->password = '';
}

    public function toggleShowPassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}