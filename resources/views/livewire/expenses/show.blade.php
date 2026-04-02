<div class="container">
    <h1 class="mb-4">Manage Expenses</h1>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Name" wire:model.defer="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" class="form-control" placeholder="Price" wire:model.defer="price">
                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Namething" wire:model.defer="namething">
                        @error('namething') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">{{ $isEdit ? 'Update' : 'Add' }} Expense</button>
                    <button type="button" wire:click="resetInput" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Namething</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->id }}</td>
                            <td>{{ $expense->name }}</td>
                            <td>{{ $expense->price }}</td>
                            <td>{{ $expense->namething }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" wire:click="edit({{ $expense->id }})">Edit</button>
                                <button class="btn btn-sm btn-danger" wire:click="delete({{ $expense->id }})" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                    @if($expenses->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No expenses found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>