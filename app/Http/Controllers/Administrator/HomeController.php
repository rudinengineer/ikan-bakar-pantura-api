<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages/main');
    }

    public function dashboard()
    {
        $ordersPending = Order::whereDate('created_at', date('Y-m-d'))
            ->where('status', 'pending');

        if (Auth::user()->role->level > 1) {
            $ordersPending->where('store_id', Auth::user()->store_id);
        }

        $customer = Customer::query();
        if (Auth::user()->role->level > 1) {
            $customer->where('store_id', Auth::user()->store_id);
        }

        $ordersConfirmed = Order::whereDate('created_at', date('Y-m-d'))
            ->where('status', 'confirmed');
        if (Auth::user()->role->level > 1) {
            $ordersConfirmed->where('store_id', Auth::user()->store_id);
        }

        $ordersToday = Order::whereDate('created_at', date('Y-m-d'));
        if (Auth::user()->role->level > 1) {
            $ordersToday->where('store_id', Auth::user()->store_id);
        }

        $data = [
            'customerTotal' => $customer->count(),
            'ordersPending' => $ordersPending->count(),
            'ordersConfirmed' => $ordersConfirmed->count(),
            'ordersToday' => $ordersToday->count()
        ];

        return view('pages/dashboard/index', $data);
    }
}
