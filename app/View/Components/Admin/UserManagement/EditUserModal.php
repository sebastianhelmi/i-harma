<?php

namespace App\View\Components\Admin\UserManagement;

use Illuminate\View\Component;

class EditUserModal extends Component
{
    public $roles;

    public function __construct($roles)
    {
        $this->roles = $roles;
    }

    public function render()
    {
        return view('components.admin.user-management.edit-user-modal');
    }
}
