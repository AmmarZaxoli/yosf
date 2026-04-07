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
        $this->errorMessage = '';
        $this->name = '';
        $this->password = '';
    }

    public function updatedName()
    {
        $this->errorMessage = '';
    }

    public function login()
    {
        $this->errorMessage = '';

        if (empty($this->name)) {
            $this->errorMessage = 'الرجاء اختيار اسم المستخدم';
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

    public function render()
    {
        $accounts = Account::select('name', 'role')
            ->orderBy('name')
            ->get();

        return view('livewire.auth.login', [
            'accounts' => $accounts,
        ]);
    }
}