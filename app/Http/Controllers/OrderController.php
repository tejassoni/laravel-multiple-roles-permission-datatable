<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProductPivot;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {  //KEY : MULTIPERMISSION
        $this->middleware('permission:order-list|order-create|order-edit|order-show|order-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
        $this->middleware('permission:order-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('updated_at', 'desc')->get();
        $orders = Order::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
        return view('orders.index', compact('products', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();
        return view('orders.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        try {
            $createdOrder = Order::firstOrCreate(['order_code' => $request->order_code, 'user_id' => auth()->user()->id]);

            // Create an array of data for bulk insertion
            $data = [];
            foreach ($request->products as $productId) {
                $data[] = [
                    'order_id' => $createdOrder->id,
                    'product_id' => $productId,
                ];
            }
            OrderProductPivot::insert($data);
            if ($createdOrder) { // inserted success
                $createdOrder->total_amount = Product::whereIn('id', $request->products)->sum('price');
                $createdOrder->save();
                \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success inserting data : ".json_encode([request()->all(),$data]));
                return redirect()->route('orders.index')
                    ->withSuccess('Created successfully...!');
            }
            throw new \Exception('fails not created..!', 403);
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query inserting data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error inserting data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $selectedProducts = [];
        $orderId = $order->id;
        $order->with('products')->where('user_id', auth()->user()->id);
        if ($order->has('products')) {
            $order->products->each(function ($prod, $key) use (&$selectedProducts) {
                $selectedProducts[] = $prod->id;
            });
        }
        $products = Product::with(['getParentCatHasOne'])->where('status', Product::STATUS_ACTIVE)->get();
        return view('orders.show', compact('order', 'products', 'selectedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $selectedProducts = [];
        $order->with('products')->where('user_id', auth()->user()->id);
        if ($order->has('products')) {
            $order->products->each(function ($prod) use (&$selectedProducts) {
                $selectedProducts[] = $prod->id;
            });
        }
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();
        return view('orders.edit', compact('order', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        try {
            // Delete old order and product record 
            OrderProductPivot::where('order_id', $order->id)->delete();
            // Create an array of data for bulk insertion
            $data = [];
            foreach ($request->products as $productId) {
                $data[] = [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                ];
            }
            OrderProductPivot::insert($data);
            // update order details
            $order->updateOrFail(['order_code' => $request->order_code, 'total_amount' => Product::whereIn('id', $request->products)->sum('price'), 'user_id' => auth()->user()->id]);

            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success updating data : ".json_encode([request()->all(),$order]));
            return redirect()->route('orders.index')
                ->withSuccess('Updated Successfully...!');            
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query updating data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error updating data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            OrderProductPivot::where('order_id', $order->id)->delete(); // related order and product pivot data remove
            $order->delete(); // main order table record remove
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success deleting data : " . json_encode([request()->all(), $order]));
            return redirect()->route('orders.index')
                ->withSuccess('Deleted Successfully.');                
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query deleting data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error deleting data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

}
