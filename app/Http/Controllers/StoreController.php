<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Response;
use App\Http\Repository\StoreRepository;
use App\Http\Resources\StoreResource;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return Response::successWithData(
            StoreResource::collection(
                StoreRepository::getAllStore()
            )
        );
    }
}
