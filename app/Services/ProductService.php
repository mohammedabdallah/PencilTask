<?php

namespace App\Services;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductService {

	public function createColumnsinRunTime($columns,$product)
	{
		
		foreach ($columns as $key => $value) {
			$newColumnType = 'string';
			$newColumnName = $value['cvalue'];
			//check if the column already exist
			if(Schema::hasColumn('products', $value['cvalue']))
				continue;
			Schema::table('products', function (Blueprint $table) use ($newColumnType, $newColumnName)
			{
			    $table->$newColumnType($newColumnName)->nullable();
			});
		}

		return;
	}

	public function attachValuestoCreatedColumns($columns,$product)
	{
		foreach ($columns as $key => $value) {
			\DB::table('products')
            ->where('id', $product->id)
            ->update([$value['cvalue'] => $value['cdescription']]);
		}
		return;
	}
}