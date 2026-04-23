<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('payments.index', compact('orders'));
    }
    
    public function create($order_id = null)
    {
        if ($order_id) {
            $order = Order::findOrFail($order_id);
            return view('payments.create', compact('order'));
        }
        
        $orders = Order::where('remaining_balance', '>', 0)->get();
        return view('payments.create', compact('orders'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string'
        ]);
        
        $order = Order::findOrFail($request->order_id);
        
        $newAmountPaid = $order->amount_paid + $request->amount_paid;
        $newRemainingBalance = $order->total_amount - $newAmountPaid;
        
        // Determine payment status
        if ($newRemainingBalance <= 0) {
            $paymentStatus = 'Paid';
            $newRemainingBalance = 0;
        } else {
            $paymentStatus = 'Partial';
        }
        
        // Create payment record
        Payment::create([
            'order_id' => $order->id,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method
        ]);
        
        // Update order
        $order->update([
            'amount_paid' => $newAmountPaid,
            'remaining_balance' => $newRemainingBalance,
            'payment_status' => $paymentStatus
        ]);
        
        $message = 'Payment of ₱' . number_format($request->amount_paid, 2) . ' processed successfully! ';
        
        if ($paymentStatus == 'Paid') {
            $message .= 'Order is now fully paid.';
        } else {
            $message .= 'Remaining balance: ₱' . number_format($newRemainingBalance, 2);
        }
        
        return redirect()->route('payments.index')->with('success', $message);
    }
    
    public function history(Request $request)
    {
        $query = Order::with('payments');
        
        if ($request->customer) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->customer . '%');
        }
        
        $transactions = $query->latest()->get();
        return view('payments.history', compact('transactions'));
    }
}