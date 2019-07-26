<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'code', 'price', 'image_path', 'description'
    ];
    public static $rules = 
    [
        'name' => 'required',
        'code' => 'required',
        'price'=> 'required',
        'product_image' => 'required'  
    ];

}
