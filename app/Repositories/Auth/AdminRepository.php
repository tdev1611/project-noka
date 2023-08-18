<?php
namespace App\Services\Auth;

use App\Models\Admin;

class ProductRepository
{
    protected $admin;
    function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

}

?>