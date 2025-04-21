<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return $this->api(new UserResource(auth()->user()),__METHOD__);
        
    }

 
}
