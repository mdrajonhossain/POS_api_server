<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sales;
use App\Models\sales_details;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller{
    //

    public function add_sales(Request $request){      
        try {
            $user = $request->attributes->get('user');
            
            if (!$user || !$user->id) {
                return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed' ], 401);
            }
    
            // $sale = new sales();        
            // $sale->customer_id = 0;
            // $sale->total_item = 0;
            // $sale->total_price = 0;
            // $sale->discount = 0;
            // $sale->pay = 0;
            // $sale->received = 0;
            // $sale->user_id = $user->id;

            $sale = new sales();        
            $sale->customer_id = $request->customer_id;
            $sale->total_item = $request->total_item;
            $sale->total_price = $request->total_price;
            $sale->discount = $request->discount;
            $sale->pay = $request->pay;
            $sale->received = $request->received;
            $sale->user_id = $user->id;
    
            if ($sale->save()) {
                return response()->json([ 'status' => true, 'message' => 'successfully', 'sale' => $sale ], 200);
            } else {
                return response()->json(['error' => 'Save Failed', 'status' => false, 'message' => 'failed to save sale' ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error', 'status' => false], 500);
        }
    }


    public function getsales(Request $request){  

        $user = $request->attributes->get('user');       
        $sales = sales::all();
                  
        if (!$user->id) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed'], 401);
        }
        return response()->json(['status' => true, 'all sales' =>$sales], 200);
    }



    public function sales_in_product_quanityUpdate(Request $request){      
        try {
            $user = $request->attributes->get('user');
                        
            if (!$user || !$user->id) {
                return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed' ], 401);
            }
            
            $sales_details = sales_details::find($request->id);        
            $sales_details->quantity = $request->quantity;
            $save = $sales_details->save();
    
            if ($save) {
                return response()->json([ 'status' => true, 'message' => 'successfully', 'sale' => $sales_details ], 200);
            } else {
                return response()->json(['error' => 'Save Failed', 'status' => false, 'message' => 'failed to save sale' ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error', 'status' => false], 500);
        }
    }



    public function salesdelete(Request $request){
        $user = $request->attributes->get('user');          
        $sale = sales::find($request->id);        
        if ($sale) {
            $sale->delete();
            $salesall = sales::all();
            return response()->json(['status' => true, 'Deleted_salese' =>$sale, "all_data" => $salesall], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
        return response()->json(['status' => false], 200);        
    }



    public function add_sales_in_items(Request $request){      
        try {
            $user = $request->attributes->get('user');
            $Product = Product::find($request->product_id);  
            
            if (!$user || !$user->id) {
                return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed' ], 401);
            }
                 
            

            $sales_details = new sales_details();            
            $sales_details->sales_id = $request->sales_id;
            $sales_details->product_id = $request->product_id;
            $sales_details->sales_price = $Product->selling_price;
            $sales_details->quantity = $request->quantity;
            $sales_details->discount = $Product->discount;
            $sales_details->sub_total = $request->quantity * $Product->selling_price;
            $save = $sales_details->save();
    
            if ($save) {
                return response()->json([ 'status' => true, 'message' => 'successfully', 'sale' => $sales_details ], 200);
            } else {
                return response()->json(['error' => 'Save Failed', 'status' => false, 'message' => 'failed to save sale' ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error', 'status' => false], 500);
        }
    }


        // sales_details items delete
        public function add_sales_in_items_delete(Request $request){
            $user = $request->attributes->get('user');             
    
            $delete = sales_details::where('id', $request->id)->delete();
    
            if (!$delete) {
                return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'failed'], 401);
            }
            return response()->json(['status' => true, 'items' => $delete], 200);
        }


        // sales_details_in_items quantity edit
      public function add_sales_in_items_edit(Request $request){
        $user = $request->attributes->get('user');             
               
        $item = sales_details::find($request->id);   
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }        
        $item->quantity = $request->quantity;
        $item->sub_total = $item->sales_price * $item->quantity;
        $saved = $item->save();

        if (!$saved) {
            return response()->json(['status' => false, 'message' => 'Item updated faild'], 200);
        }        
        return response()->json(['status' => true, 'message' => 'Item updated successfully', 'items' => $item], 200);
    }



    
    // final save sales table_in_items save
    public function final_sales_in_items_save(Request $request) {
        $user = $request->attributes->get('user');

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|integer',
            'customer' => 'required|integer',            
            'received' => 'required|numeric',            
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false,'message' => 'Validation failed','errors' => $validator->errors()], 400);
        }
        $price = sales_details::where('sales_id', $request->sales_id)->get();

        if ($price->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Item update failed'], 200);
        }
        
        $total_price = 0;
        foreach ($price as $item) {
            $total_price += $item->sub_total;
        }
        
        $sales = sales::find($request->sales_id);
        if (!$sales) {
            return response()->json(['status' => false, 'message' => 'Item update failed'], 200);
        }
        
        $sales->customer_id = $request->customer;
        $sales->total_item = $price->count();
        $sales->total_price = $total_price;                
        $sales->pay = $total_price;                
        $sales->received = $request->received;
        $saved = $sales->save();

        if (!$saved) {
            return response()->json(['status' => false, 'message' => 'Item update failed'], 200);
        } 

        return response()->json(['status' => true, 'message' => 'Purchase added successfully', 'items' => $sales], 200);
    }

    


    public function getsalesdetails(Request $request) {
        try {
            $user = $request->attributes->get('user');                        
            
            $salesdetails = sales_details::with('product')->where('sales_id', $request->sales_id)->get();
    
            if ($salesdetails->isEmpty()) {
                return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'No sales details found'], 401);
            }
    
            return response()->json(['status' => true, 'items' => $salesdetails], 200);
    
        } catch (\Exception $e) {            
            return response()->json(['error' => 'Server Error', 'status' => false, 'message' => $e->getMessage()], 500);
        }
    }











}
