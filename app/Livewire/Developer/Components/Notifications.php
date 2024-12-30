<?php

namespace App\Livewire\Developer\Components;

use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
class Notifications extends Component
{
    use WithPagination;
    use LivewireAlert;
    public $notifications;
    public $unreadNotifications;
    public $authUser;
    public $iconActive;

    public $title;
    public $description;
    public $icon;
    public $type;

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
        $this->alert('success', 'marked as read');
    }

    public function render()
    {
        return view('livewire.developer.components.notifications');
    }
}
