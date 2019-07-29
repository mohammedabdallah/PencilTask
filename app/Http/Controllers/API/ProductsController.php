<?php

// ProductController.php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;
class ProductsController extends Controller
{
    private $productServiceObj;
    public function __construct(ProductService $productServiceObj) {
        $this->productServiceObj = $productServiceObj;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        //stuck with formatting custom field objec in angular side
        $product = Product::create([
          'id'   => Uuid::uuid4(),
          'name' => $request['name'],
          'code' => $request['code'],
          'price'=> $request['price'],
          'description' => $request['description'],
          'image_path'  => $path
        ]);
        $columns = json_decode($request['customfields'],true);
        $this->productServiceObj->createColumnsinRunTime($columns,$product);
        $this->productServiceObj->attachValuestoCreatedColumns($columns,$product);
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
        return $product;
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
        if($request['product_image']){
            $product_image = $request->file('product_image');

            $name=$product_image->getClientOriginalName();

            $uploaded_file = $product_image->move(public_path().'/uploads/', $name);
            $request['image_path']         = \URL::to('/uploads/'.$uploaded_file->getFileName());
        }
        return response()->json([
            'status'=>200,
            'message'=>$product
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