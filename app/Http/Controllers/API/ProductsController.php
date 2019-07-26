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
        $input = $request->all();
        $validatedData = \Validator::make($request->all(),Product::$rules);
         if ($validatedData->fails()) {

          return response()->json(['errors'=>$validatedData->errors()]);
        }

        $path = '';
        if($request['product_image']){
            $product_image = $request->file('product_image');

            $name=$product_image->getClientOriginalName();

            $uploaded_file = $product_image->move(public_path().'/uploads/', $name);
            $path          = \URL::to('/uploads/'.$uploaded_file->getFileName());
        }
        $product = Product::create([
          'id'   => Uuid::uuid4(),
          'name' => $request['name'],
          'code' => $request['code'],
          'price'=> $request['price'],
          'description' => $request['description'],
          'image_path'  => $path
        ]);

        return response()->json([
            'code'   => 201,
            'message'=> 'Successfully added'
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'status'=>200,
            'product'=>$product
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $product->update($request->all());  
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