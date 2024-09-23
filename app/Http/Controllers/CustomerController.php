<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller{

    //

    public function get_customer(Request $request){      
      
        $user = $request->attributes->get('user');
        $Customer = Customer::all();

        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,            
            'data' => $Customer
        ], 200);

    }

    public function storecustomer(Request $request){
        $user = $request->user();
        
        // Validate incoming request data
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_code' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255'
        ]);
    
        // Check authorization
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {
            // Create new Customer instance
            $customer = new Customer();
            $customer->customer_name = $request->customer_name;
            $customer->customer_code = $request->customer_code;
            $customer->address = $request->address;
            $customer->phone = $request->phone;
            $customer->save();
    
            // Return success response
            return response()->json(['status' => true, 'data' => $customer], 201);
        } catch (\Exception $e) {
            // Return error response if exception occurs
            return response()->json(['status' => false, 'message' => 'Failed to create customer'], 500);
        }
    }



    public function updateCustomer(Request $request){

        $validatedData = $request->validate([
            'id' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);
            
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {            
            $customer = Customer::find($request->id);                
            $customer->customer_name = $request->customer_name;
            $customer->address = $request->address;
            $customer->phone = $request->phone;                        
            $customer->save();                
            return response()->json(['status' => true, 'data' => $customer], 200);
        } catch (\Exception $e) {            
            return response()->json(['status' => false, 'message' => 'Failed to update customer', 'error' => $e->getMessage()], 500);
        }
    }


    public function customer_delete(Request $request){
        $user = $request->attributes->get('user');          
        $Customer = Customer::find($request->id);        
        if ($Customer) {
            $Customer->delete();
            $Customerall = Customer::all();
            return response()->json(['status' => true, 'Deleted_salese' =>$Customer, "all_data" => $Customerall], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
        return response()->json(['status' => false], 200);        
    }




}
