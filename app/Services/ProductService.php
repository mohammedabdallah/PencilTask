<?php

namespace App\Services;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductService {

	public function createColumnsinRunTime($columns)
	{
		$columns->each(function($value, $key) {
			$newColumnType = 'string';
			$newColumnName = $value;
			Schema::table('products', function (Blueprint $table) use ($newColumnType, $newColumnName)
			{
			    $table->$newColumnType($newColumnName);
			});
		});
	}
}