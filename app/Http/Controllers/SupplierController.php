<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller{
    //

    public function get_supplier(Request $request){      
      
        $user = $request->attributes->get('user');
        $Supplier = Supplier::all();

        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,            
            'data' => $Supplier
        ], 200);

    }



    public function addsupplier(Request $request){
        $user = $request->user();
    
        // Validate incoming request data
        $request->validate([
            'nama' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255' // Adjust validation rules as needed
        ]);
    
        // Check authorization
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {
            // Create a new supplier
            $supplier = new Supplier();
            $supplier->nama = $request->nama;
            $supplier->address = $request->address;
            $supplier->phone = $request->phone;
            $supplier->save();
    
            // Return success response
            return response()->json(['status' => true, 'data' => $supplier], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create customer'], 500);
        }
    }


    public function supplierUpdate(Request $request){
        $user = $request->user();
            
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|min:5|max:15',
        ]);

        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {            
            $supplier = Supplier::find($request->id);
            $supplier->nama = $request->nama;
            $supplier->address = $request->address;
            $supplier->phone = $request->phone;
            $supplier->save();                
            return response()->json(['status' => true, 'data' => $supplier], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create customer'], 500);
        }
    }



    public function supplier_delete(Request $request){
        $user = $request->attributes->get('user');          
        $supplier = Supplier::find($request->id);        
        if ($supplier) {
            $supplier->delete();
            $supplierall = Supplier::all();
            return response()->json(['status' => true, 'Deleted_salese' =>$supplier, "all_data" => $supplierall], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
        return response()->json(['status' => false], 200);        
    }









}
