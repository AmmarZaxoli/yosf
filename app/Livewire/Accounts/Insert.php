<?php

namespace App\Livewire\Accounts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class Insert extends Component
{
    use WithPagination;

    public $name, $role, $password;
    public $account_id;
    public $isOpen = false;
    public $isEditing = false;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:255|unique:accounts,name,' . $this->account_id,
            'role' => 'required|in:admin,user',
        ];

        // Simple password rule - no confirmation needed
        if (!$this->account_id) {
            // For new accounts, password is required
            $rules['password'] = 'required';
        } else {
            // For editing, password is optional
            $rules['password'] = 'nullable';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'الاسم مطلوب',
        'name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
        'name.unique' => 'الاسم مستخدم بالفعل',
        'role.required' => 'الدور مطلوب',
        'role.in' => 'الدور يجب أن يكون مدير أو مستخدم',
        'password.required' => 'كلمة المرور مطلوبة',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
        $this->isEditing = false;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->role = '';
        $this->password = '';
        $this->account_id = '';
        $this->isEditing = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'role' => $this->role,
            ];

            if ($this->account_id) {
                // Update existing account
                $account = Account::find($this->account_id);
                
                // Only update password if provided
                if (!empty($this->password)) {
                    $data['password'] = $this->password; // Store as plain text
                }
                
                $account->update($data);
                flash()->success('تم تحديث الحساب بنجاح.');
            } else {
                // Create new account
                $data['password'] = $this->password; // Store as plain text
                Account::create($data);
                flash()->success('تم إضافة الحساب بنجاح.');
            }

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            flash()->error('حدث خطأ أثناء الحفظ');
        }
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $this->account_id = $id;
        $this->name = $account->name;
        $this->role = $account->role;
        $this->password = $account->password; // Load the password
        
        $this->isEditing = true;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        try {
            $account = Account::find($id);
            
            if (!$account) {
                session()->flash('error', 'الحساب غير موجود');
                return;
            }
            
            // Don't allow deleting your own account
            if (Auth::check() && Auth::id() == $account->id) {
                flash()->error('لا يمكنك حذف حسابك الخاص');
                return;
            }
            
            $account->delete();
            flash()->success('تم حذف الحساب بنجاح.');
        } catch (\Exception $e) {
            flash()->error('حدث خطأ أثناء الحذف');
        }
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        $accounts = Account::where('name', 'like', $searchTerm)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.accounts.insert', [
            'accounts' => $accounts
        ]);
    }
}