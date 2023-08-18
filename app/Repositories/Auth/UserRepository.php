<?php
namespace App\Services\Auth;

use App\Models\User;

class ProductRepository
{
    protected $user;
    function __construct(User $user)
    {
        $this->user = $user;
    }

}

?>