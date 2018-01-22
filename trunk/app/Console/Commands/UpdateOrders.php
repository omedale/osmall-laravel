<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OpenWish;
use App\Models\OpenWishPledge;
use App\Models\Product;
use App\Models\Ocredit;
use App\SProduct;
use App\Models\StationProduct;
use App\Models\MerchantProduct;
use App\Models\POrder;
use App\Models\Station;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\DeliveryOrder;
use App\Models\Receipt;
use App\Models\Globals;
use App\Http\Controllers\UtilityController;
use Carbon;
use DateTime;
use DB;
class UpdateOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Order status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
    Updates stock & available in sproduct table.
	To be used in station inventory handler. Logic.
    See if user id has role station.
    */ 
    public function sproductUpdater($porder)
    {
         $userIdWhoBought=$porder->user_id;
         $hasRole=UtilityController::hasRole($userIdWhoBought,'sto');
         // 1 equivalent of True, 0 for false.
         if ($hasRole ==1) {
             # The order belongs to a station.Now get station id
            $stId=UtilityController::getStationId($userIdWhoBought);
            $ops=OrderProduct::where('porder_id',$porder->id)->get();
            foreach ($ops as $op) {
                // Find if a sproduct record exists.
                $sp=SProduct::join('stationsproduct as sp','sp.sproduct_id','=','sproduct.id')
                ->where('sproduct.product_id',$op->product_id)
                ->where('sp.station_id',$stId)
                ->select(DB::raw("sproduct.id as id "))->first();

				/*
                echo "product_id ".$op->product_id."\n";
                $product_id=Product::where('id',$op->product_id)->
					pluck('parent_id');
                echo "parent product_id ".$product_id."\n";
				*/

                $product_id=$op->product_id;
                if (!is_null($sp)) {
                    echo "$sp not null \n";
                    $sproduct=SProduct::find($sp->id);
                    $sproduct->available+=$op->quantity;
                    $sproduct->stock+=$op->quantity;
                    $sproduct->save();
                   
                } else {
                    echo "$sp null \n";
                    $sproduct=new SProduct;
                    $sproduct->product_id=$product_id;
                    $sproduct->available=$op->quantity;
                    $sproduct->stock=$op->quantity;
                    $sproduct->status="active";
                    $sproduct->save();
                    // Create a record in StationProduct
                    $stationproduct=new StationProduct;
                    $stationproduct->station_id=$stId;
                    $stationproduct->sproduct_id=$sproduct->id;
                    $stationproduct->save();
                }
            }
		}
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
			$orders = DB::table('porder')
            ->whereNotIn('status',['manual','commented'])
            ->whereNull('deleted_at')
            ->get();
          
			$globals = DB::table('global')->first();
            $interval=$globals->merchant_process_salesorder_window+
                ($globals->buyer_return_window*24)+
                ($globals->merchant_approve_cre_window*24);

			// Squidster: 4hr interval: FOR TESTING ONLY!!!
            $interval=4;
            echo "R:$interval ";

			foreach($orders as $order){
				$date = $order->created_at;
               
                $timestamp2=Carbon::now();
                $diff= UtilityController::timeDiff($date,$timestamp2);

				if ($diff > $interval) {
                    POrder::find($order->id)
                        ->update(['status'=>'completed',
							'updated_at'=>date('Y-m-d H:i:s')]);
					/*
					echo 'B2B:$this->sproductUpdater($order)';
					*/
				}
			}
        } catch (\Exception $e){
            dump($e->getMessage());
			echo "Error for Update";
        }
    }
}
