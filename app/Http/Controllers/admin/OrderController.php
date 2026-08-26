<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
    public function index(Request $request) {
        $orders = Order::with(array_merge($this->orderRelations(), [
                'latestStatus', 'items', 'items.product', 'items.size', 'items.color',
                'variant.variant_image',
                'items.product.category',
                'items.product.subCategory',
                'items.product.subSubCategory',                
                ]))               
            ->latest('orders.created_at')
            ->paginate(20);

        // Order counts
        $totalOrders = Order::count();                         

        return view('admin.orders.list', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,                        
        ]);
    }


    private function orderRelations() {
        return [
            'user',
            'items',
            'items.product',            
            'orderItems.product.images',
            'variant.variant_image',
            'orderItems.size',
            'orderItems.color',
            'items.product.size',
            'items.product.color',            
            'items.product.category',
            'items.product.subCategory',
            'items.product.subSubCategory'
        ];
    }


    public function detail($orderId){
        $order = Order::with([
                'items.product.category',
                'items.product.subCategory',
                'items.product.subSubCategory',
                'items.size', 
                'items.color',
                'orderItems',
            ])
            ->select(
                'orders.*',
                'customer_addresses.name',
                'customer_addresses.mobile',
                'customer_addresses.address',
                'customer_addresses.locality',
                'customer_addresses.city',
                'customer_addresses.zip',
                'states.name as stateName'
            )
            ->where('orders.id', $orderId)
            ->leftJoin('customer_addresses', 'customer_addresses.id', '=', 'orders.customer_address_id')
            ->leftJoin('states', 'states.id', '=', 'customer_addresses.state_id')
            ->first();

        $orderItems = OrderItem::with('product.images')
            ->where('order_id', $orderId)
            ->get();

        $latestStatus = OrderStatusHistory::where('order_id',$order->id)
                ->orderBy('date','desc')
                ->first();

        $orderHistory = OrderStatusHistory::where('order_id',$orderId)
                ->orderBy('date','asc')
                ->get();                        

        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems,
            'latestStatus' => $latestStatus,
            'orderHistory' => $orderHistory
        ]);
    }


    public function tracking($id) {
        $statuses = OrderStatusHistory::where('order_id', $id)
            ->orderBy('date','asc')
            ->get();

        $data = [];

        foreach($statuses as $status){
            $data[] = [
                'status' => $status->status,
                'date' => \Carbon\Carbon::parse($status->date)
                            ->format('d M Y h:i A')
            ];
        }        

        return response()->json($data);
    }


    public function changeTrackStatus(Request $request, $orderId){
        $order = new OrderStatusHistory();
        $order->order_id = $request->order_id;
        $order->tracking_number = $request->tracking_number;
        $order->courier = $request->courier;
        $order->note = $request->note;
        $order->status = $request->status;
        $order->date = $request->date;
        $order->save();

        // 👉 Update main order table
        $order = Order::find($request->order_id);
        if ($order) {
            $order->status = $request->status;
            $order->save();
        }

        $message = 'Order track status updated successfully';

        session()->flash('success','Order track status updated successfully');
        return redirect()->back();
    }


    public function sendInvoiceEmail(Request $request, $orderId){
        orderEmail($orderId, $request->userType);

        $message = 'Order email sent successfully';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
