<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        
        if ($request->status) {
            $query->where('order_status', $request->status);
        }
        
        $orders = $query->latest()->get();
        return view('orders.index', compact('orders'));
    }
    
    public function create()
    {
        $menus = Menu::all();
        return view('orders.create', compact('menus'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'cart_data' => 'required',
            'total_amount' => 'required|numeric',
            'order_status' => 'required|in:Pending,Processing,Completed'
        ]);
        
        $cart = json_decode($request->cart_data, true);
        
        // Check stock availability
        foreach ($cart as $item) {
            $menu = Menu::find($item['menu_id']);
            if ($menu->stock < $item['quantity']) {
                return back()->with('error', 'Insufficient stock for ' . $menu->name);
            }
        }
        
        // Deduct stock
        foreach ($cart as $item) {
            $menu = Menu::find($item['menu_id']);
            $menu->decrement('stock', $item['quantity']);
        }
        
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'items' => $cart,
            'total_amount' => $request->total_amount,
            'amount_paid' => 0,
            'remaining_balance' => $request->total_amount,
            'order_status' => $request->order_status,
            'payment_status' => 'Unpaid'
        ]);
        
        return redirect()->route('orders.show', $order->id)->with('success', 'Order created successfully! Order #: ' . $order->order_number);
    }
    
    public function show($id)
    {
        $order = Order::with('payments')->findOrFail($id);
        return view('orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:Pending,Processing,Completed'
        ]);
        
        $order = Order::findOrFail($id);
        $order->update(['order_status' => $request->order_status]);
        
        return back()->with('success', 'Order status updated!');
    }
}