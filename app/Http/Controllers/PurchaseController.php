<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\purchase;
use App\Models\Supplier;
use App\Models\Product;
// use App\Models\purchase_details;

use App\Models\purchase_details;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller{
    //
    // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    // purchase table in list for purchase list
    public function get_purchase(Request $request){      
      
        $user = $request->attributes->get('user');
        // $purchase = purchase::all(); 
        $purchases = Purchase::with('supplier')->get();


        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,            
            'data' =>$purchases
        ], 200);

    }



    public function get_suplyer(Request $request){      
      
        $user = $request->attributes->get('user');
        $supplier = Supplier::all(); 
       

        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,            
            'data' =>$supplier
        ], 200);

    }

// purchase table in list for purchase list
    public function add_purchase(Request $request){
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {            
            $purchase = new purchase();
            $purchase->supplier_id = $request->supplier;
            $purchase->total_item = 0;
            $purchase->total_price = 0;
            $purchase->discount = 0;
            $purchase->total_pay = 0;
            $purchase->save();
                
            return response()->json(['status' => true, 'data' => $purchase], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create customer'], 500);
        }
    }


    public function purchaseDelete(Request $request){
        $user = $request->attributes->get('user');          
        $purchase = purchase::find($request->id);        
        if ($purchase) {
            $purchase->delete();
            $purchase_all = purchase::all();
            return response()->json(['status' => true, 'Deleted_salese' =>$purchase, "all_data" => $purchase_all], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
        return response()->json(['status' => false], 200);        
    }


    // purchase_in_product select for product list
    public function purchase_in_product_list(Request $request){  

        $user = $request->attributes->get('user');
        $Product = Product::all(); 
       
        if (!$user->id) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed'], 401);
        }
        return response()->json(['status' => true, 'data' =>$Product], 200);
    }


    public function add_purchaseDetails_in_items_list(Request $request){
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {            
            $purchase_details = new purchase_details();            
            $purchase_details->purchase_id = $request->purchase_id;
            $purchase_details->product_id = $request->product_id;
            $purchase_details->purchase_price = $request->purchase_price;
            $purchase_details->quantity = $request->quantity;
            $purchase_details->sub_total = $request->purchase_price * $request->quantity;
            $purchase_details->save();
                
            return response()->json(['status' => true, 'data' => $purchase_details], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create customer'], 500);
        }
    }
    

       // purchase_in_product select for product list id ways all items
       public function purchaseDetails_in_items_list(Request $request){  

        $user = $request->attributes->get('user');       
        $items = purchase_details::with('product')->where('purchase_id', $request->purchase_id)->get();
        
        $count = $items->count();    
        if (!$user->id) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed'], 401);
        }
        return response()->json(['status' => true, 'items List' =>$items, 'count' => $count], 200);
    }



    public function purchase_in_getItems(Request $request){
        $user = $request->attributes->get('user');            
            
        $request->validate([
            'id' => 'required|integer',
        ]);    
    
        $purchaseItems = purchase_details::where('purchase_id', $request->id)->get();
    
        if ($purchaseItems->isEmpty()) {
            return response()->json(['error' => 'No items found', 'status' => false, 'message' => 'Failed'], 404);
        }
    
        return response()->json(['status' => true, 'items' => $purchaseItems], 200);
    }



     // purchase_details items delete
     public function purchaseDetails_in_items_delete(Request $request){
        $user = $request->attributes->get('user');             

        $delete = purchase_details::where('id', $request->id)->delete();

        if (!$delete) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed'], 401);
        }
        return response()->json(['status' => true, 'items' => $delete], 200);
    }



      // purchaseDetails_in_items quantity edit
      public function purchaseDetails_in_items_edit(Request $request){
        $user = $request->attributes->get('user');             
               
        $item = purchase_details::find($request->id);   
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }        
        $item->quantity = $request->quantity;
        $item->sub_total = $item->purchase_price * $item->quantity;
        $saved = $item->save();

        if (!$saved) {
            return response()->json(['status' => false, 'message' => 'Item updated faild'], 200);
        }        
        return response()->json(['status' => true, 'message' => 'Item updated successfully', 'items' => $item], 200);
    }



       // final save purchase table_in_items save
       public function purchase_in_items_save(Request $request){
        $user = $request->attributes->get('user');             
        
        $price = purchase_details::where('purchase_id', $request->purchase_id)->get();
        if (!$price) {
            return response()->json(['status' => false, 'message' => 'Item updated faild'], 200);
        }
        $total_price = 0;
        foreach ($price as $item) {
            $total_price += $item['sub_total'];
        }

        $purchase_table = Purchase::find($request->purchase_id);

        if (!$purchase_table) {
            return response()->json(['status' => false, 'message' => 'Item updated faild'], 200);
        }

        $purchase_table->total_item = $price->count();
        $purchase_table->total_price = $total_price; 
        $purchase_table->discount = $request->discount;
        $purchase_table->total_pay = $request->pay;
        $saved = $purchase_table->save();

        if (!$saved) {
            return response()->json(['status' => false, 'message' => 'Item updated faild'], 200);
        } 
            
        return response()->json([
            'status' => true, 
            'message' => 'Purchase add successfully', 
            'items' => $purchase_table
        ], 200);
    }

 

    







}
