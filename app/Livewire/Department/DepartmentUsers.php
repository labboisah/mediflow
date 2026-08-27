<?php

namespace App\Livewire\Department;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.live')]
class DepartmentUsers extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public int $perPage = 15;
    public ?int $editingUserId = null;
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    public function render()
    {
        $departmentId = auth()->user()->department_id;

        return view('components.department.department-users', [
            'users' => User::query()
                ->with(['department', 'roles'])
                ->where('department_id', $departmentId)
                ->when(trim($this->search) !== '', function ($query) {
                    $search = trim($this->search);
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->orderBy('name')
                ->paginate($this->perPage),
        ]);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function edit(int $userId): void
    {
        $user = $this->departmentUserQuery()->findOrFail($userId);

        $this->resetValidation();
        $this->editingUserId = $user->id;
        $this->email = (string) $user->email;
        $this->password = '';
        $this->passwordConfirmation = '';
    }

    public function save(): void
    {
        if ($this->editingUserId === null) {
            $this->dispatch('notify', type: 'warning', message: 'Select a department user before saving changes.');
            return;
        }

        $validated = $this->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingUserId),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'passwordConfirmation' => ['nullable', 'same:password'],
        ], [
            'passwordConfirmation.same' => 'The password confirmation does not match.',
        ]);

        $user = $this->departmentUserQuery()->findOrFail($this->editingUserId);
        $payload = ['email' => $validated['email']];

        if ($validated['password'] !== '') {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        $this->resetEditor();
        $this->dispatch('notify', type: 'success', message: 'Department user updated successfully.');
    }

    public function cancelEdit(): void
    {
        $this->resetEditor();
    }

    private function resetEditor(): void
    {
        $this->resetValidation();
        $this->editingUserId = null;
        $this->email = '';
        $this->password = '';
        $this->passwordConfirmation = '';
    }

    private function departmentUserQuery()
    {
        return User::query()
            ->where('department_id', auth()->user()->department_id);
    }
}
