<?php

namespace App\View\Components\Admin\UserManagement;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreateUserModal extends Component
{
    /**
     * Create a new component instance.
     */
    private $roles = [];
    public function __construct($roles = [])
    {
        $this->roles = $roles;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.user-management.create-user-modal', ['roles' => $this->roles]);
    }
}
