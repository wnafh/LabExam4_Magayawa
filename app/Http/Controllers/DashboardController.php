<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMenuItems = Menu::count();
        $pendingOrders = Order::where('order_status', 'Pending')->count();
        $processingOrders = Order::where('order_status', 'Processing')->count();
        $completedOrders = Order::where('order_status', 'Completed')->count();
        $totalRevenue = Order::where('payment_status', 'Paid')->sum('total_amount');
        $recentOrders = Order::latest()->take(5)->get();
        
        return view('dashboard', compact(
            'totalMenuItems',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}