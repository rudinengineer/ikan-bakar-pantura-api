<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Response;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function reportTotal()
    {
        return Response::successWithData([
            User::where('role', 'user')->count()
        ]);
    }
}
