<?php

namespace App\Livewire\Developer\Components;

use Livewire\Component;
use Livewire\WithPagination;
class Notifications extends Component
{
    use WithPagination;

    public $notifications;
    public $unreadNotifications;
    public $authUser;
    public $iconActive;

    public function mount()
    {
        $this->notifications = auth()->user()->notifications;
        $this->unreadNotifications = auth()->user()->unreadNotifications;
        $this->authUser = auth()->user();
        $this->iconActive = $this->unreadNotifications->isNotEmpty();
    }

    public function markAsRead()
    {
        $this->authUser->unreadNotifications->markAsRead();
        $this->unreadNotifications = $this->authUser->fresh()->unreadNotifications;
        $this->iconActive = $this->unreadNotifications->isNotEmpty();
    }

    public function render()
    {
        return view('livewire.developer.components.notifications');
    }
}
