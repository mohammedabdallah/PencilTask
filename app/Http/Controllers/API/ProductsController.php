<?php

// ProductController.php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 200,
            'Allproducts' => $products
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::create([
          'id'   => Uuid::uuid4(),
          'name' => $request['name'],
          'code' => $request['code'],
          'price'=> $request['price'],
          'description' => $request['description']
        ]);
        return response()->json([
            'code'   => 201,
            'message'=> 'Successfully added'
        ]);
    }
    public function destroy($id)
    {
        //
        $product = Product::find($id);
        if(!$product)
            return response()->json([
                'status' => 404,
                'message'=>'No product found'
            ]);
        $product->delete();
        return response()->json([
                'message'=>'deleted',
                'status'=>200
            ]);
        
    }
}