<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Inertia\Inertia;
use App\Models\Customer; 
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Order::paginate(50);
        // 合計
        $orders = Order::groupBy('id') 
        ->selectRaw('id, customer_name, sum(subtotal) as total, status, created_at' ) 
        ->paginate(50);
        return Inertia::render('Purchases/Index', [ 
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $customers = Customer::select('id', 'name', 'kana')->get();
        $items = Item::select('id', 'name', 'price')
        ->where('is_selling', true)->get();

        return Inertia::render('Purchases/Create', [ 
            // 'customers' => $customers,
            'items' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        DB::beginTransaction();
        
        try{
            $purchase = Purchase::create([ 
                'customer_id' => $request->customer_id, 
                'status' => $request->status,
            ]);

            foreach($request->items as $item)
            { 
                $purchase->items()->attach(
                    $item['id'],
                    ['quantity' => $item['quantity']]
                );
            }
            DB::commit();
        
            return to_route('dashboard');
        } catch(\Exception $e){ 
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        // 小計
        $items = Order::where('id', $purchase->id)
        ->get();
        // 合計
        $order = Order::groupBy('id')
        ->where('id', $purchase->id)
        ->selectRaw('id, customer_name, sum(subtotal) as total, status, created_at')
        ->get();
        //dd($subtotals, $order);
        return Inertia::render('Purchases/Show', [ 
            'items' => $items, 
            'order' => $order 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $allItems = Item::select('id', 'name', 'price')->get(); // 全商品を取得
        $items = []; // 空の配列を用意

        // 販売中の商品と中間テーブルを比較し、中間テーブルに数量があれば数量を取得、なければ0で設定
        foreach($allItems as $allItem){
            $quantity = 0; // 数量初期値 0
            foreach($purchase->items as $item){ //中間テーブルを1件ずつチェック
                if($allItem->id === $item->id){ // 同じidがあれば
                    $quantity = $item->pivot->quantity; // 中間テーブルの数量を設定 } }
                    array_push($items, [
                        'id' => $allItem->id, 
                        'name' => $allItem->name,
                        'price' => $allItem->price, 
                        'quantity' => $quantity 
                    ]); 
                }
            }
        }
        
        $order = Order::groupBy('id')
        ->where('id', $purchase->id)
        ->selectRaw('id, customer_id, customer_name,
        status, created_at') 
        ->get();
        
        return Inertia::render('Purchases/Edit', [ 
            'items' => $items,
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();

        try{
        // dd($request, $purchase);
        $purchase->status = $request->status;
        $purchase->save();

        $items = [];

        foreach($request->items as $item){
            $items = $items + [
                $item['id'] => [
                    'quantity' => $item['quantity']
                ]
            ];
        }

        // dd($items);
        $purchase->items()->sync($items);

        DB::commit();
        
        return to_route('dashboard');
        } catch(\Exception $e){
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
