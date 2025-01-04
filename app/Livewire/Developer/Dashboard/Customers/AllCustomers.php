<?php

namespace App\Livewire\Developer\Dashboard\Customers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Layout('components.layouts.developer', ['title' => 'كل العملاء'])]
#[Title('كل العملاء')]
class AllCustomers extends Component
{
    use WithPagination;

    public $selectedUserId = null;
    public $selectedUserModel = null;
    public $selectedGroup = null;
    public $search = '';
    public $perPage = 10;
    public $authuser;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedGroup' => ['except' => ''],
        'selectedUserId' => ['except' => null],
        'perPage' => ['except' => 10],
    ];

    protected function getUserGroups(User $user): array
    {
        $groups = [];

        if ($user->orders()->exists()) {
            $groups[] = 'buyers';
        }

        if ($user->favorites()->exists()) {
            $groups[] = 'active';
        }

        return $groups;
    }

    public function getGroupsProperty()
    {
        return [
            'all' => User::whereHas('favoritesProjects', function ($query) {
                $query->where('developer_id', $this->authuser->developer->id);
            })->orWhereHas('orders.unit.project', function($query) {
                $query->where('developer_id', $this->authuser->developer->id);
            })->count(),

            'buyers' => User::whereHas('orders.unit.project', function($query) {
                $query->where('developer_id', $this->authuser->developer->id);
            })->count(),

            'active' => User::whereHas('favoritesProjects', function ($query) {
                $query->where('developer_id', $this->authuser->developer->id);
            })->count(),
        ];
    }

    public function loadUser($userId)
    {
        $this->selectedUserId = (int) $userId;

        $this->selectedUserModel = User::query()
            ->with(['orders.unit.project', 'favorites'])
            ->whereHas('orders.unit.project', function($query) {
                $query->where('developer_id', $this->authuser->developer->id);
            })
            ->where('id', $userId)
            ->first();

        if (!$this->selectedUserModel) {
            $this->selectedUserId = null;
        }
    }

    public function mount()
    {
        $this->authuser = Auth::user();

        // Load user on initial page load if ID exists in URL
        if ($this->selectedUserId) {
            $this->loadUser($this->selectedUserId);
        }
    }

    public function getUsersProperty()
    {
        $query = User::query()
            ->whereHas('orders.unit.project', function($query) {
                $query->where('developer_id',  $this->authuser->developer->id);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('username', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->selectedGroup === 'buyers', fn($q) => $q->whereHas('orders'))
            ->when($this->selectedGroup === 'active', fn($q) => $q->whereHas('favorites'));

        return $query->paginate($this->perPage);
    }

    // updated hook selectedUserId
    public function updatedSelectedUserId($value)
    {
        if ($value) {
            $this->loadUser($value);
        } else {
            $this->selectedUserModel = null;
        }
    }

    public function render()
    {
        return view('livewire.developer.dashboard.customers.all-customers',
            [
                'groups' => $this->groups,
                'users' => $this->users,
            ]
        );
    }
}
