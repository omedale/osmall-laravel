<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Twholesale extends Model
{
    protected  $table = 'twholesale';

    public function storetwholesale($request,$tproduct_id)
    {
        for ($i = 0; $i < (($request->wholesaleprices)+1); $i++) {
            $wholwsale_table = new twholesale();
            $wholwsale_table->tproduct_id = $tproduct_id;
            $wholwsale_table->unit = $request->wholesaleunits[$i];
            $wholwsale_table->funit = $request->wholesalefunits[$i];
            $wholwsale_table->price = $request->wholesalepricesa[$i]*100;
			if($request->wholesalepricesa[$i]>0){
				$wholwsale_table->save();
			}
        }
    }
}
