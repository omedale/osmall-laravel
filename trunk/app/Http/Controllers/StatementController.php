<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Array_;
use Request;
use Input;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Station;
use App\Models\Address;
use App\Models\Buyer;
use App\Models\User;
use Carbon\Carbon;
use App\Models\POrder;
use App\Models\Receipt;
use App\Models\DeliveryOrder;
use App\Models\Delivery;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\IdController;
use DB;
use DateTime;
use PDF;

class StatementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public  $cStatus='"completed","reviewed","commented"';
    public function lsov($logistic_id){
		$logistic = DB::table('logistic')->where('id',$logistic_id)->first();
        $query = Delivery::leftJoin('orderreturn as op','op.porder_id','=','delivery.porder_id')
                
                ->where('delivery.logistic_id','=',$logistic_id)
                    ->select('delivery.created_at')
                    ->orderBy('delivery.created_at','desc')
                    ->get();                
                $actual_year = Delivery::leftJoin('orderreturn as op','op.porder_id','=','delivery.porder_id')
                
                ->where('delivery.logistic_id','=',$logistic_id)
                    ->select('delivery.created_at')
                    ->orderBy('delivery.created_at','desc')
                 
                    ->first();
					
        $years = Array();$months = Array();$y = Array();$index = 0;
            foreach($query as $que){
                $years[$que->created_at->year][$index] = $que->created_at->month;
                $index++;
            }
        $today = Carbon::today();

        $company = Station::where('id' , $logistic->station_id)
                ->first();

        $s = Address::where('id' , $company->address_id)
            ->first(array('line1','line2','line3','line4'));
            $mer = "Logistic ID";
            $id = IdController::nS($company->id);
        $arr=array('myreturn' => $query, 'today' => $today, 'years'=>$years, 'actual_year'=>$actual_year);
        // return $arr;
        $today = $arr['today'];
        $myreturn = $arr['myreturn'];
        $years = $arr['years'];
        $actual_year = $arr['actual_year'];
        $current_year = 0;
        if(isset($actual_year)){
            $actual_year = $actual_year->created_at;
            if($actual_year->year != $today->year){
                $current_year = 0;
            }else{
                $current_year = 1;
            }
        }		
		return view('statement.logisticdetaill',compact('today','myreturn','current_year'))
			->with('mer',$mer)->with('id',$id)->with('company',$company)->with('logistic_id',$logistic_id)->with('s' , $s)->with('years',$years)->with('title','Statement')->with('detail','detail');
	}
	
    public function sov($custom_id = null){
		if(Auth::check()){
			if(isset($custom_id))
				$id = $custom_id;
			else
				$id =  \Auth::user()->id;
		} else {
			return Redirect::back();
		}


		$stmt = "sov";
		$arr = $this->get_all($id, $stmt);
		$ireturn = $arr['ireturn'];
		$today = $arr['today'];
		$myreturn = $arr['myreturn'];
		$mer = $arr['mer'];
		$id = $arr['id'];
		$company = $arr['company'];
		$s = $arr['s'];
		$years = $arr['years'];
        $actual_year = $arr['actual_year'];
        $current_year = 0;
        if(isset($actual_year)){
            $actual_year = $actual_year->created_at;
            if($actual_year->year != $today->year){
                $current_year = 0;
            }else{
                $current_year = 1;
            }
        }
		return view('statement.merchantdetail',compact('ireturn','today','myreturn','current_year'))
			->with('mer',$mer)->with('id',$id)->with('company',$company)->with('s' , $s)->with('years',$years)->with('title','Statement')->with('detail','detail');
    }
    public function showMerchantStatement($month,$year,$merchant_id="merchant",$type="st")
    {
        // Test Code .
        $y2=intval($year);
        $m2=intval($month);
        if ($month==12) {
            $m2=01;
            $y2=$y2+1;
        }
        $rawMonth=$month;
        $firstcyle_sdate=$year."-".$month."-01";
        $firstcyle_edate=$year."-".$month."-16";
        $secondcyle_sdate=$year."-".$month."-16";
        $secondcyle_edate=$y2."-".($m2+1)."-01";
        if (!Auth::check()) {
            return view('common.generic')
            ->with('message_type','error')
            ->with('message','Please login to access.');
        }
        if (Auth::user()->hasRole('adm')) {
            
        }else{
            // Get merchant id
            try {
                $merchant_id=Merchant::where('user_id',Auth::user()->id)->pluck('id');
            } catch (\Exception $e) {
                dump($e);
                return view('common.generic')
                ->with('message_type',"error")
                ->with('message','You do not have permission to access this resource. #001')
                ;
            }
        }
        try{
            $extraData=[UtilityController::numberMonth($month),$year,$rawMonth];
            $firstcyle=$this->getMerchantStatement($merchant_id,$firstcyle_sdate,$firstcyle_edate);
            $firstcylepenalty=$this->getMerchantStatementPenalty($merchant_id,$firstcyle_sdate,$firstcyle_edate);
           
            $secondcycle=$this->getMerchantStatement($merchant_id,$secondcyle_sdate,$secondcyle_edate);
            $secondcyclepenalty=$this->getMerchantStatementPenalty($merchant_id,$secondcyle_sdate,$secondcyle_edate);
            
            $cycle=[$firstcyle,$secondcycle,$firstcylepenalty,$secondcyclepenalty,$extraData];
           
            return view('statement.merchant_statement',compact('cycle','merchant_id','type'));
        }catch(\Exception $e){
            dump($e);
            return view('common.generic')
                ->with('message_type',"error")
                ->with('message','You do not have permission to access this resource. #002')
                ;
        }
    }
    public function sendUpdateMerchantStatement($last_record)
    {
        
    }
    public function pdfMerchantStatement($month,$year,$merchant_id="merchant",$type="st")
    {
        $y2=intval($year);
        $m2=intval($month);
        if ($month==12) {
            $m2=01;
            $y2=$y2+1;
        }
        $rawMonth=$month;
        $firstcyle_sdate=$year."-".$month."-01";
        $firstcyle_edate=$year."-".$month."-16";
        $secondcyle_sdate=$year."-".$month."-16";
        $secondcyle_edate=$y2."-".($m2+1)."-01";
          if ($merchant_id!="merchant") {
            
        }else{
            // Get merchant id
            try {
                $merchant_id=Merchant::where('user_id',Auth::user()->id)->pluck('id');
            } catch (\Exception $e) {
             return $e;
            }
        }
        $merchant=Merchant::find($merchant_id);
        $merchant_address=Address::find($merchant->address_id);
        $extraData=[UtilityController::numberMonth($month),$year,$rawMonth,$merchant,$merchant_address];
        $firstcyle=$this->getMerchantStatement($merchant_id,$firstcyle_sdate,$firstcyle_edate);
        $firstcylepenalty=$this->getMerchantStatementPenalty($merchant_id,$firstcyle_sdate,$firstcyle_edate);

        $secondcycle=$this->getMerchantStatement($merchant_id,$secondcyle_sdate,$secondcyle_edate);
        $secondcyclepenalty=$this->getMerchantStatementPenalty($merchant_id,$secondcyle_sdate,$secondcyle_edate);
        
        $statement_file_name="statement/ops_statement_merchant_".$month."_".$year.".pdf";
        $cycle=[$firstcyle,$secondcycle,$firstcylepenalty,$secondcyclepenalty,$extraData];
        // Wrapper
        $pdf=PDF::loadView('statement.pdf.merch',['cycle'=>$cycle,'type'=>$type])->setOption('margin-bottom', 20)
            ->save(storage_path($statement_file_name));
    }

    public function downloadMSPDF($month,$year,$merchant_id,$type="st")
    {
        $this->pdfMerchantStatement($month,$year,$merchant_id,$type);
        $headers = array(
              'Content-Type: application/pdf',
        );
        $file_path="statement/ops_statement_merchant_".$month."_".$year.".pdf";

        return response()->download(storage_path($file_path),"statement.pdf",$headers)->deleteFileAfterSend(true);
    }

    public function getMerchantStatementPenalty($merchant_id,$start_cycle,$end_cycle)
    {
       

        $data=DB::table('adjustment')
				->leftJoin('users','users.id','=','adjustment.admin_user_id')
                ->where('adjustment.merchant_id',$merchant_id)
                ->whereBetween('adjustment.created_at',[$start_cycle,$end_cycle])
                ->where('adjustment.price','>',0)
                // ->where('porder.id','!=',"NULL")
                ->select(DB::raw("
						adjustment.*,
						users.first_name,
						users.last_name
                    
                    "))
                ->get();

        return $data;

      
    }	
	
    public function getMerchantStatement($merchant_id,$start_cycle,$end_cycle)
    {
       

        $data=POrder::join('orderproduct as op','op.porder_id','=','porder.id')

                ->join('merchantproduct as mp','mp.product_id','=','op.product_id')
                ->leftJoin('orderreturn as or','or.porder_id','=','porder.id')
                ->where('mp.merchant_id',$merchant_id)
                ->whereBetween('porder.created_at',[$start_cycle,$end_cycle])
                
                // ->where('porder.id','!=',"NULL")
                ->select(DB::raw("
                    porder.id as oid,
                    SUM(((op.osmall_comm_amount/100)*(op.order_price*op.quantity))/100) as osmall_commission,
                    porder.updated_at as completed_at,
                    SUM(op.order_price*op.quantity)+op.order_delivery_price as price,
                    SUM(op.actual_delivery_price) as delivery,
                    porder.order_administration_fee as oafee,
                    SUM(op.payment_gateway_fee) as pgfee,
                   
                    CASE
                    WHEN porder.id  in (select porder_id from porderpayment) THEN 'paid'
                    ELSE 'unpaid' 
                    END as status,
                    CASE
                    WHEN or.status = 'failed' THEN or.return_price
                    ELSE 0
                    END as rdelivery
                    
                    "))
                ->groupBy('porder.id')
                ->get();

        return $data;

      
    }
    public function rec($custom_id = null){

		if(Auth::check()){
			if(isset($custom_id))
				$id = $custom_id;
			else
				$id =  \Auth::user()->id;
		} else {
			return Redirect::back();
		}

		$stmt = "rec";
		$arr = $this->get_all($id, $stmt);
		$ireturn = $arr['ireturn'];
		$today = $arr['today'];
		$myreturn = $arr['myreturn'];
		$mer = $arr['mer'];
		$id = $arr['id'];
		$company = $arr['company'];
		$s = $arr['s'];
		$years = $arr['years'];
        $actual_year = $arr['actual_year'];
        $current_year = 0;
        if(isset($actual_year)){
            $actual_year = $actual_year->created_at;
            if($actual_year->year != $today->year){
                $current_year = 0;
            }else{
                $current_year = 1;
            }
        }
		return view('statement.merchantdetail',compact('ireturn','today','myreturn','current_year'))
			->with('mer',$mer)->with('id',$id)->with('company',$company)->with('s' , $s)->with('years',$years)->with('title','Receipt')->with('detail','recdetail');
	}

    public function dor($custom_id = null){

		if(Auth::check()){
			if(isset($custom_id))
				$id = $custom_id;
			else
				$id =  \Auth::user()->id;
		} else {
			return Redirect::back();
		}

		$stmt = "dor";
		$arr = $this->get_all($id, $stmt);
		$ireturn = $arr['ireturn'];
		$today = $arr['today'];
		$myreturn = $arr['myreturn'];
		$mer = $arr['mer'];
		$id = $arr['id'];
		$company = $arr['company'];
		$s = $arr['s'];
		$years = $arr['years'];
        $actual_year = $arr['actual_year'];
        $current_year = 0;
        if(isset($actual_year)){
            $actual_year = $actual_year->created_at;
            if($actual_year->year != $today->year){
                $current_year = 0;
            }else{
                $current_year = 1;
            }
        }
		return view('statement.merchantdetail',compact('ireturn','today','myreturn','current_year'))
			->with('mer',$mer)->with('id',$id)->with('company',$company)->with('s' , $s)->with('years',$years)->with('title','Delivery Order')->with('detail','dodetail');
	}

	public function get_all($id, $stmt){
            $merchant = Merchant::where('user_id',$id)
                    ->first();
            $ireturn=array();
            $station = Station::where('user_id' , $id)
                ->first();

			if($stmt == "sov"){
				if(!is_null($merchant)){
				$query = POrder::join('orderproduct as op','op.porder_id','=','porder.id')
                ->join('merchantproduct as mp','op.product_id','=','mp.product_id')
                ->where('mp.merchant_id','=',$merchant->id)
                    ->select('porder.user_id','porder.created_at')
                    ->orderBy('porder.created_at','desc')
                    ->get();                
                $actual_year =  POrder::join('orderproduct as op','op.porder_id','=','porder.id')
                ->join('merchantproduct as mp','op.product_id','=','mp.product_id')
                ->where('mp.merchant_id','=',$merchant->id)
                    ->select('porder.user_id','porder.created_at')
                    ->orderBy('porder.created_at','desc')
                   
                    ->first();
				} else {
					$query = array();
					$actual_year = array();
				}
			}
			if($stmt == "rec"){
				$query = Receipt::join('porder', 'porder.id', '=', 'receipt.porder_id')
					->where('user_id' , $id)
					->select('porder.user_id','porder.created_at')
					->orderBy('porder.created_at','desc')
					->get();
                $actual_year = Receipt::join('porder', 'porder.id', '=', 'receipt.porder_id')
                    ->where('user_id' , $id)
                    ->select('porder.user_id','porder.created_at')
                    ->orderBy('porder.created_at','desc')
                    ->first();
			}
			if($stmt == "dor"){
				$query = DeliveryOrder::join('receipt', 'receipt.id', '=', 'deliveryorder.receipt_id')
					->join('porder', 'porder.id', '=', 'receipt.porder_id')
					->where('user_id' , $id)
					->select('porder.user_id','porder.created_at')
					->orderBy('porder.created_at','desc')
					->get();
                $actual_year = DeliveryOrder::join('receipt', 'receipt.id', '=', 'deliveryorder.receipt_id')
                    ->join('porder', 'porder.id', '=', 'receipt.porder_id')
                    ->where('user_id' , $id)
                    ->select('porder.user_id','porder.created_at')
                    ->orderBy('porder.created_at','desc')
                    ->first();
			}
            $years = Array();$months = Array();$y = Array();$index = 0;
            foreach($query as $que){
                $years[$que->created_at->year][$index] = $que->created_at->month;
                $index++;
            }
            $today = Carbon::today();
            if(isset($merchant)){
                $merchant_address = Address::where( 'id',$merchant->address_id)
                ->first(array('line1','line2','line3','line4'));
                    $mer = "Merchant ID";
                    $id = IdController::nSeller($merchant->user_id);
                    $s = $merchant_address;
                    $name = $merchant->oshop_name;
                    $company = $merchant->company_name;
					$ireturn = 	$merchant;
					return (array('ireturn'=>$ireturn, 'myreturn' => $query, 'today' => $today, 'mer' => $mer, 'id' => $id, 'name' => $name, 'company'=>$company, 's'=>$s, 'years'=>$years, 'actual_year'=>$actual_year));
            } else {
				if(isset ($station)){
					$station_address = Address::where('id' , $station->address_id)
						->first(array('line1','line2','line3','line4'));
						$mer = "Station ID";
						$id = IdController::nSeller($station->user_id);
						$s = $station_address;
						$name = $station->company_name;
						$company = $station->station_name;
						$ireturn = 	$station;
						return (array('ireturn'=>$ireturn, 'myreturn' => $query, 'today' => $today, 'mer' => $mer, 'id' => $id, 'name' => $name, 'company'=>$company, 's'=>$s, 'years'=>$years, 'actual_year'=>$actual_year));
				}
			}
			
	}

	public function merchantdetailrc()
    {
    	$from = Carbon::today();
    	$from->day = 1;
    	$from->month = Request::input('month');
    	$from->year = Request::input('year');
    	$mid = Request::input('mid');
    	$to = Carbon::create($from->year,$from->month,$from->day);
        $to = $to->endOfMonth();
        $id = Auth::user()->id;
        $merchant = DB::table('merchant')->where('id',$mid)
            ->first();
        $merchant_address = Address::where('id',$merchant->address_id)
                ->first(array('line1','line2','line3','line4'));
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
		$porders = DB::table('porder')->join('orderproduct as op','op.porder_id','=','porder.id')->join('merchantproduct as mp','op.product_id','=','mp.product_id')
					->where('mp.merchant_id','=',$merchant->id)
                    ->select('porder.*')
                    ->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])->orderBy('porder.created_at','DESC')->get();
		
		$orders = array();
        foreach ($porders as $po) {
            # code...
			$roder = [];
            $roder['oid'] = $po->id;
            $total= DB::table('orderproduct')->where('porder_id',$po->id)->select(DB::raw("SUM(order_price * quantity) as total"))->first();
			$rtotal = 0;
			if(!is_null($total)){
				$rtotal = $total->total;
			}
			$roder['total'] = $rtotal;
			$roder['o_exec'] = $po->created_at;            
			array_push($orders, $roder);
        }		
		
        $roles = \DB::table('role_users')
                ->join('roles' , 'roles.id' ,'=' , 'role_users.role_id')
                ->where('role_users.user_id','=',$id)
                ->select('slug' , 'role_users.user_id')
                ->get();
            $i = 0;$r = array();
//	dd($orders);		
    if(isset($roles)){
        foreach($roles as $role){
            $r[$i] =  $role->slug;
            $i++;
        }
    } $i = 1;
		$user_role = "";
		
		if(in_array("mct",$r) || isset($merchant)){
            $user_role = "mct";
        }
        else if(in_array("sto" , $r) || isset ($station)){
            $user_role = "sto";
        } else if(in_array("byr" , $r)){
            $user_role = "byr";
        }
		$s= $merchant_address;
		$name = $merchant->company_name;	
        $monthNum=Request::input('month');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March		
        $year=Request::input('year');
            return view('statement.merchantdetailrc')
            ->with('product_orders' ,$orders)
            ->with('merchant' , $merchant)
            ->with('currency' , $currency)
            ->with('merchant_address' , $merchant_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('month',$monthName)
            ->with('year',$year);		
	}
	
    public function buyerdetail()
    {
    	$from = Carbon::today();
    	$from->day = 1;
    	$from->month = Request::input('month');
    	$from->year = Request::input('year');
    	$to = Carbon::create($from->year,$from->month,$from->day);
        $to = $to->endOfMonth();
        $id = Auth::user()->id;
        $buyer = User::where('id',$id)
            ->first();
        $buyer_address = Address::where( 'id',$buyer->default_address_id)
                ->first(array('line1','line2','line3','line4'));
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
		$porders = DB::table('porder')->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])->where('user_id', $id)->orderBy('porder.created_at','DESC')->get();
		
		$b= new BuyerController();
		$product_orders= $b->products($porders);
		$orders = array();
        foreach ($product_orders as $po) {
            # code...
            $ex=DB::table('porder')->where('id',$po['oid'])->first();
            $total= DB::table('payment')->where('id',$ex->payment_id)->pluck('receivable');
         //   $po['total']=$total;
            $po['status']=$ex->status;
            $po['o_receipt']=$ex->receipt_tstamp;
			$date = $ex->created_at;
			$date1 = new DateTime(date('Y-m-d H:i:s'));
			$date2 = new DateTime(date('Y-m-d H:i:s', strtotime($date)));
			$diff = $date1->diff($date2);
			$hours = $diff->h;
			$days = $diff->days;
			$totaldiff = ($diff->days * 24) + $diff->h + ($diff->i / 60) + ($diff->s / 3600);
			$po['hours']=$hours;
			$po['days']=$days;
			$po['totaldiff']=$totaldiff;
            array_push($orders, $po);
        }		
		
        $roles = \DB::table('role_users')
                ->join('roles' , 'roles.id' ,'=' , 'role_users.role_id')
                ->where('role_users.user_id','=',$id)
                ->select('slug' , 'role_users.user_id')
                ->get();
            $i = 0;$r = array();
	//dd($sorders);		
    if(isset($roles)){
        foreach($roles as $role){
            $r[$i] =  $role->slug;
            $i++;
        }
    } $i = 1;
		$user_role = "";
		
		if(in_array("mct",$r) || isset($merchant)){
            $user_role = "mct";
        }
        else if(in_array("sto" , $r) || isset ($station)){
            $user_role = "sto";
        } else if(in_array("byr" , $r)){
            $user_role = "byr";
        }
		$mer = "Buyer ID";
		$id = $buyer->id;
		$s= $buyer_address;
		$name = $buyer->name;	
        $monthNum=Request::input('month');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March		
        $year=Request::input('year');
            return view('statement.buyerdetail')
            ->with('product_orders' ,$orders)
            ->with('buyer' , $buyer)
            ->with('currency' , $currency)
            ->with('buyer_address' , $buyer_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('mer' , $mer)
            ->with('user_role' , $user_role)
            ->with('month',$monthName)
            ->with('year',$year);
    }
	
    public function salesmemodetailweb($user_id)
    {
    	$from = Carbon::today();
    	$from->day = 1;
    	$from->month = Request::input('month');
    	$from->year = Request::input('year');
    	$to = Carbon::create($from->year,$from->month,$from->day);
        $to = $to->endOfMonth();
        $id = $user_id;
        $buyer = User::where('id',$id)
            ->first();
        $buyer_address = Address::where( 'id',$buyer->default_address_id)
                ->first(array('line1','line2','line3','line4'));
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
		$salesmemo = DB::table('salesmemo')->join('salesmemoproduct','salesmemoproduct.salesmemo_id','=','salesmemo.id')->whereBetween('salesmemo.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])->where('user_id', $id)->select('salesmemo.*')->distinct()->orderBy('salesmemo.created_at','DESC')->get();
		foreach($salesmemo as $sm){
			$products = DB::table('salesmemoproduct')->where('salesmemo_id',$sm->id)->get();
			$total= 0;
			foreach($products as $pp){
				$tt = $pp->order_price * $pp->quantity;
				$total += $tt;
			}
			$sm->total = $total;
		}
		$id = $buyer->id;
		$s= $buyer_address;
		$name = $buyer->name;	
        $monthNum=Request::input('month');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March		
        $year=Request::input('year');
            return view('statement.salesmemodetailweb')
            ->with('product_orders' ,$salesmemo)
            ->with('buyer' , $buyer)
            ->with('currency' , $currency)
            ->with('buyer_address' , $buyer_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('month',$monthName)
            ->with('year',$year);
    }		
	
    public function salesmemodetail($user_id)
    {
    	$from = Carbon::today();
    	$from->day = 1;
    	$from->month = Request::input('month');
    	$from->year = Request::input('year');
    	$to = Carbon::create($from->year,$from->month,$from->day);
        $to = $to->endOfMonth();
        $id = $user_id;
        $buyer = User::where('id',$id)
            ->first();
        $buyer_address = Address::where( 'id',$buyer->default_address_id)
                ->first(array('line1','line2','line3','line4'));
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
		$salesmemo = DB::table('salesmemo')->join('salesmemoproduct','salesmemoproduct.salesmemo_id','=','salesmemo.id')->whereBetween('salesmemo.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])->where('user_id', $id)->select('salesmemo.*')->distinct()->orderBy('salesmemo.created_at','DESC')->get();
		foreach($salesmemo as $sm){
			$products = DB::table('salesmemoproduct')->where('salesmemo_id',$sm->id)->get();
			$total= 0;
			foreach($products as $pp){
				$tt = $pp->order_price * $pp->quantity;
				$total += $tt;
			}
			$sm->total = $total;
		}
		$id = $buyer->id;
		$s= $buyer_address;
		$name = $buyer->name;	
        $monthNum=Request::input('month');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March		
        $year=Request::input('year');
            return view('statement.salesmemodetail')
            ->with('product_orders' ,$salesmemo)
            ->with('buyer' , $buyer)
            ->with('currency' , $currency)
            ->with('buyer_address' , $buyer_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('month',$monthName)
            ->with('year',$year);
    }	

    public function detail()
    {
    	$from = Carbon::today();
    	$from->day = 1;
    	$from->month = Request::input('month');
    	$from->year = Request::input('year');
    	$to = Carbon::create($from->year,$from->month,$from->day);
        $to = $to->endOfMonth();
        $id = Request::input('id');
        $merchant = Merchant::where('user_id',$id)
            ->first();
        if(isset($merchant)){
            $merchant_address = Address::where( 'id',$merchant->address_id)
                ->first(array('line1','line2','line3','line4'));
        }  else {
            $merchant_address = null;
        }
        $station = Station::where('user_id' , $id)
            ->first();
        if(isset($station)){
            $station_address = Address::where('id' , $station->address_id)
                ->first(array('line1','line2','line3','line4'));
        }else{
            $station_address = null;
        }
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
    //    $o = POrder::where('user_id' , $id)->select('id')->get();

          $sorders = \DB::table('porder')
                ->join('station' , 'station.user_id' , '=' , 'porder.user_id')
                ->join('sorder', 'sorder.porder_id','=','porder.id')
                ->join('orderproduct' , 'orderproduct.porder_id' , '=' , 'sorder.porder_id')
                ->join('product' , 'product.id' , '=' , 'orderproduct.product_id')
                ->join('merchantproduct' , 'orderproduct.product_id' , '=' , 'merchantproduct.product_id')
                ->join('merchant','merchantproduct.merchant_id','=','merchant.id')
                  ->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])
                ->where('porder.user_id',$id)
                ->select(
                        'porder.id',
                        'sorder.created_at',
                        'product.name AS description',
                        'sorder.id AS transaction_id',
                        'merchant.id AS merchant_id',
                        'product.id AS product_id',
                        'orderproduct.order_price',
                        'orderproduct.quantity'
                        )
                ->get();
            $porders = \DB::table('porder')
                    ->join('orderproduct', 'orderproduct.porder_id','=','porder.id')
                    ->join('product' ,'product.id','=','orderproduct.product_id')
                    ->join('merchant' , 'merchant.user_id' , '=' , 'porder.user_id')
                    ->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])
                    ->where('porder.user_id',$id)
                    ->select(
                            'porder.created_at',
                            'porder.station_id',
                            'product.name AS description',
                            'porder.id AS transaction_id',
                            'merchant.id AS merchant_id',
                            'product_id',
                            'porder.user_id AS buyer_id',
                            'orderproduct.order_price',
                            'orderproduct.quantity'
                            )
                    ->get();

        $roles = \DB::table('role_users')
                ->join('roles' , 'roles.id' ,'=' , 'role_users.role_id')
                 ->join('merchant' , 'merchant.user_id' , '=', 'role_users.user_id')
                ->where('role_users.user_id','=',$id)
                ->where('merchant.user_id' , '=' , $id)
                ->select('slug' , 'role_users.user_id')
                ->get();
            $i = 0;$r = array();
    if(isset($roles)){
        foreach($roles as $role){
            $r[$i] =  $role->slug;
            $i++;
        }
    } $i = 1;
    if(in_array("mct",$r) || isset($merchant)){
            $user_role = "mct";
            $mer = "Merchant ID";
            $id = $merchant->user_id;
            $s= $merchant_address;
            $name = $name = $merchant->oshop_name;
        }
        else if(in_array("sto" , $r) || isset ($station)){
            $user_role = "sto";
            $mer = "Station ID";
            $id = $station->user_id;
            $s = $station_address;
            $name = $station->station_name;
        } else if(in_array("byr" , $r)){
            $user_role = "byr";
            $mer = "Merchant ID";
            $id = $merchant->user_id;
            $s= $merchant_address;
            $name = $name = $merchant->oshop_name;
        }
            return view('statement.buyerdetail')
            ->with('porders' ,$porders)
            ->with('sorders' , $sorders)
            ->with('station' , $station)
            ->with('merchant' , $merchant)
            ->with('currency' , $currency)
            ->with('merchant_address' , $merchant_address)
            ->with('station_address' , $station_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('mer' , $mer)
            ->with('user_role' , $user_role);
    }

    public function recdetail()
    {
    	$from = Carbon::today();
    	$from->day = 1;
    	$from->month = Request::input('month');
    	$from->year = Request::input('year');
    	$to = Carbon::create($from->year,$from->month,$from->day);
        $to = $to->endOfMonth();
        $id = Request::input('id');
        $merchant = Merchant::where('user_id',$id)
            ->first();
        if(isset($merchant)){
            $merchant_address = Address::where( 'id',$merchant->address_id)
                ->first(array('line1','line2','line3','line4'));
        }  else {
            $merchant_address = null;
        }
        $station = Station::where('user_id' , $id)
            ->first();
        if(isset($station)){
            $station_address = Address::where('id' , $station->address_id)
                ->first(array('line1','line2','line3','line4'));
        }else{
            $station_address = null;
        }
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
        $roles = \DB::table('role_users')
            ->join('roles' , 'roles.id' ,'=' , 'role_users.role_id')
            ->join('merchant' , 'merchant.user_id' , '=', 'role_users.user_id')
            ->where('role_users.user_id','=',$id)
            ->where('merchant.user_id' , '=' , $id)
            ->select('slug' , 'role_users.user_id')
            ->get();
        $o = POrder::where('user_id' , $id)->select('id')->get();

          $sorders = \DB::table('receipt')
                ->join('porder' , 'receipt.porder_id' , '=' , 'porder.id')
                ->join('station' , 'station.user_id' , '=' , 'porder.user_id')
                ->join('sorder', 'sorder.porder_id','=','porder.id')
                ->join('orderproduct' , 'orderproduct.porder_id' , '=' , 'sorder.porder_id')
                ->join('product' , 'product.id' , '=' , 'orderproduct.product_id')
                ->join('merchantproduct' , 'orderproduct.product_id' , '=' , 'merchantproduct.product_id')
                ->join('merchant','merchantproduct.merchant_id','=','merchant.id')
                  ->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])
                ->where('porder.user_id',$id)
                ->select(
                        'receipt.id as recid',
                        'porder.id as porderid',
                        'porder.receipt_tstamp as receipt_tstamp',
                        'porder.delivery_tstamp as delivery_tstamp',
                        'sorder.created_at',
                        'product.name AS description',
                        'sorder.id AS transaction_id',
                        'merchant.id AS merchant_id',
                        'product.id AS product_id',
                        'orderproduct.order_price',
						'porder.user_id AS seller_id',
                        'orderproduct.quantity'
                        )
                ->get();
            $porders = \DB::table('receipt')
					->join('porder' , 'receipt.porder_id' , '=' , 'porder.id')
                    ->join('orderproduct', 'orderproduct.porder_id','=','porder.id')
                    ->join('product' ,'product.id','=','orderproduct.product_id')
                    ->join('merchant' , 'merchant.user_id' , '=' , 'porder.user_id')
                    ->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])
                    ->where('porder.user_id',$id)
                    ->select(
							'receipt.id as recid',
							'porder.id as porderid',
							'porder.receipt_tstamp as receipt_tstamp',
							'porder.delivery_tstamp as delivery_tstamp',
                            'porder.created_at',
                            'porder.station_id',
                            'product.name AS description',
                            'merchant.id AS merchant_id',
                            'product_id',
                            'porder.user_id AS buyer_id',
                            'orderproduct.order_price',
                            'orderproduct.quantity'
                            )
                    ->get();
            $i = 0;$r = array();
    if(isset($roles)){
        foreach($roles as $role){
            $r[$i] =  $role->slug;
            $i++;
        }
    } $i = 1;
    if(in_array("mct",$r) || isset($merchant)){
            $user_role = "mct";
            $mer = "Merchant ID";
            $id = $merchant->id;
            $s= $merchant_address;
            $name = $name = $merchant->oshop_name;
        }
        else if(in_array("sto" , $r) || isset ($station)){
            $user_role = "sto";
            $mer = "Station ID";
            $id = $station->id;
            $s = $station_address;
            $name = $station->station_name;
        }
            return view('statement.recdetail')
            ->with('porders' ,$porders)
            ->with('sorders' , $sorders)
            ->with('roles' , $roles)
            ->with('station' , $station)
            ->with('merchant' , $merchant)
            ->with('currency' , $currency)
            ->with('merchant_address' , $merchant_address)
            ->with('station_address' , $station_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('mer' , $mer)
            ->with('user_role' , $user_role);
    }

	public function stationdetailinvoicedetail()
    {
    	// $from = Carbon::today();
    	// $from->day = 1;
    	// $from->month = Request::input('month');
    	// $from->year = Request::input('year');
    	// $to = Carbon::create($from->year,$from->month,$from->day);
     //    $to = $to->endOfMonth();
        $month=(int)Request::input('month');
        $emonth=(string)$month+1;
        $smonth=(string)$month;
        $year=Request::input('year');
        $id = Request::input('user_id');
        $fromDate=$year."-".$smonth.'-01';
        $toDate=$year."-".$emonth."-01";
        $station = Station::where('user_id',$id)
            ->first();
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
            // dump($fromDate);
            // dump($toDate);
            $porders = \DB::table('deliveryinvoice')
					->join('invoice' , 'deliveryinvoice.invoice_id' , '=' , 'invoice.id')
					->join('porder' , 'invoice.porder_id' , '=' , 'porder.id')
                    ->join('ordertproduct', 'ordertproduct.porder_id','=','porder.id')
                    ->join('tproduct' ,'tproduct.id','=','ordertproduct.tproduct_id')
                    ->join('merchanttproduct' ,'merchanttproduct.tproduct_id','=','ordertproduct.tproduct_id')
                    ->whereBetween('porder.created_at',[$fromDate,$toDate])
                    // ->whereRaw("porder.created_at BETWEEN '2017-05-1' AND '2017-06-1'")
                    ->where('porder.user_id',$id)
					->distinct()
                    ->select(
							'invoice.id as invid',
							'porder.id as porderid',
							'porder.receipt_tstamp as receipt_tstamp',
							'porder.delivery_tstamp as delivery_tstamp',
                            'porder.created_at',
                            'merchanttproduct.merchant_id AS seller_id'
                            )
                    ->orderBy('porder.created_at','DESC')
                    ->get();
            return view('statement.invoicestationdetail')
            ->with('porders' ,$porders)
            ->with('station' , $station)
            ->with('currency' , $currency)
            ->with('id' , $id);		
	}
	
	public function stationpurchasedetail()
    {
    	// $from = Carbon::today();
    	// $from->day = 1;
    	// $from->month = Request::input('month');
    	// $from->year = Request::input('year');
    	// $to = Carbon::create($from->year,$from->month,$from->day);
     //    $to = $to->endOfMonth();
        $month=(int)Request::input('month');
        $emonth=(string)$month+1;
        $smonth=(string)$month;
        $year=Request::input('year');
        $id = Request::input('user_id');
        $fromDate=$year."-".$smonth.'-01';
        $toDate=$year."-".$emonth."-01";
        $station = Station::where('user_id',$id)
            ->first();
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
            // dump($fromDate);
            // dump($toDate);
            $porders = \DB::table('deliveryinvoice')
					->join('invoice' , 'deliveryinvoice.invoice_id' , '=' , 'invoice.id')
					->join('porder' , 'invoice.porder_id' , '=' , 'porder.id')
                    ->join('ordertproduct', 'ordertproduct.porder_id','=','porder.id')
                    ->join('tproduct' ,'tproduct.id','=','ordertproduct.tproduct_id')
                    ->join('merchanttproduct' ,'merchanttproduct.tproduct_id','=','ordertproduct.tproduct_id')
                    ->whereBetween('porder.created_at',[$fromDate,$toDate])
                    // ->whereRaw("porder.created_at BETWEEN '2017-05-1' AND '2017-06-1'")
                    ->where('porder.user_id',$id)
					->distinct()
                    ->select(
							'invoice.id as invid',
							'porder.id as porderid',
							'porder.receipt_tstamp as receipt_tstamp',
							'porder.delivery_tstamp as delivery_tstamp',
                            'porder.created_at',
                            'merchanttproduct.merchant_id AS seller_id'
                            )
                    ->orderBy('porder.created_at','DESC')
                    ->get();
            return view('statement.purchasestationdetail')
            ->with('porders' ,$porders)
            ->with('station' , $station)
            ->with('currency' , $currency)
            ->with('id' , $id);		
	}	
	
	public function add_adjustment()
    {
		$adjustment=(int)Request::input('adjustment');
		$description=Request::input('description');
		$footer=Request::input('footer');
		$merchant_id=Request::input('merchant_id');
		$def_ad = $adjustment*100;
		DB::table('adjustment')->insert(['price'=>$def_ad,'admin_user_id'=>Auth::user()->id,'description'=>$description,'footer_note'=>$footer,'merchant_id'=>$merchant_id,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
		return "OK";
	}
	
	public function merchantinvoicedetail()
    {
    	// $from = Carbon::today();
    	// $from->day = 1;
    	// $from->month = Request::input('month');
    	// $from->year = Request::input('year');
    	// $to = Carbon::create($from->year,$from->month,$from->day);
     //    $to = $to->endOfMonth();
     //    $id = Request::input('id');

        $month=(int)Request::input('month');
        $emonth=(string)$month+1;
        $smonth=(string)$month;
        $year=Request::input('year');
        $user_id = Request::input('user_id');
        $id=DB::table('merchant')->where('user_id',$user_id)->pluck('id');
        $fromDate=$year."-".$smonth.'-01';
        $toDate=$year."-".$emonth."-01";
        $merchant = Merchant::where('id',$id)
            ->first();
        if(isset($merchant)){
            $merchant_address = Address::where( 'id',$merchant->address_id)
                ->first(array('line1','line2','line3','line4'));
        }  else {
            $merchant_address = null;
        }
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }

            $porders = \DB::table('deliveryinvoice')
					->join('invoice' , 'deliveryinvoice.invoice_id' , '=' , 'invoice.id')
					->join('porder' , 'invoice.porder_id' , '=' , 'porder.id')
                    ->join('ordertproduct', 'ordertproduct.porder_id','=','porder.id')
                    ->join('tproduct' ,'tproduct.id','=','ordertproduct.tproduct_id')
                    ->join('merchanttproduct' ,'merchanttproduct.tproduct_id','=','ordertproduct.tproduct_id')
                    ->whereBetween('porder.created_at',[$fromDate,$toDate])
                    ->where('merchanttproduct.merchant_id',$id)
					->distinct()
                    ->select(
							'invoice.id as invid',
							'porder.id as porderid',
							'porder.receipt_tstamp as receipt_tstamp',
							'porder.delivery_tstamp as delivery_tstamp',
                            'porder.created_at',
                            'porder.user_id AS buyer_id'
                            )
                    ->orderBy('porder.created_at','DESC')
                    ->get();
			//dd($porders);
            $mer = "Merchant ID";
            $id = $merchant->id;
            $s= $merchant_address;
            $name = $merchant->oshop_name;
            return view('statement.invoicemerchantdetail')
            ->with('porders' ,$porders)
            ->with('merchant' , $merchant)
            ->with('currency' , $currency)
            ->with('merchant_address' , $merchant_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('mer' , $mer);		
	}	
	
	public function merchantpurchasedetail()
    {
    	// $from = Carbon::today();
    	// $from->day = 1;
    	// $from->month = Request::input('month');
    	// $from->year = Request::input('year');
    	// $to = Carbon::create($from->year,$from->month,$from->day);
     //    $to = $to->endOfMonth();
     //    $id = Request::input('id');

        $month=(int)Request::input('month');
        $emonth=(string)$month+1;
        $smonth=(string)$month;
        $year=Request::input('year');
        $user_id = Request::input('user_id');
        $id=DB::table('merchant')->where('user_id',$user_id)->pluck('id');
        $fromDate=$year."-".$smonth.'-01';
        $toDate=$year."-".$emonth."-01";
        $merchant = Merchant::where('id',$id)
            ->first();
        if(isset($merchant)){
            $merchant_address = Address::where( 'id',$merchant->address_id)
                ->first(array('line1','line2','line3','line4'));
        }  else {
            $merchant_address = null;
        }
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }

            $porders = \DB::table('deliveryinvoice')
					->join('invoice' , 'deliveryinvoice.invoice_id' , '=' , 'invoice.id')
					->join('porder' , 'invoice.porder_id' , '=' , 'porder.id')
                    ->join('ordertproduct', 'ordertproduct.porder_id','=','porder.id')
                    ->join('tproduct' ,'tproduct.id','=','ordertproduct.tproduct_id')
                    ->join('merchanttproduct' ,'merchanttproduct.tproduct_id','=','ordertproduct.tproduct_id')
                    ->whereBetween('porder.created_at',[$fromDate,$toDate])
                    ->where('merchanttproduct.merchant_id',$id)
					->distinct()
                    ->select(
							'invoice.id as invid',
							'porder.id as porderid',
							'porder.receipt_tstamp as receipt_tstamp',
							'porder.delivery_tstamp as delivery_tstamp',
                            'porder.created_at',
                            'porder.user_id AS buyer_id'
                            )
                    ->orderBy('porder.created_at','DESC')
                    ->get();
			//dd($porders);
            $mer = "Merchant ID";
            $id = $merchant->id;
            $s= $merchant_address;
            $name = $merchant->oshop_name;
            return view('statement.purchasemerchantdetail')
            ->with('porders' ,$porders)
            ->with('merchant' , $merchant)
            ->with('currency' , $currency)
            ->with('merchant_address' , $merchant_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('mer' , $mer);		
	}		
	
    public function dodetail()
    {
    	$from = Carbon::today();
    	$from->day = 1;
    	$from->month = Request::input('month');
    	$from->year = Request::input('year');
    	$to = Carbon::create($from->year,$from->month,$from->day);
        $to = $to->endOfMonth();
        $id = Request::input('id');
        $merchant = Merchant::where('user_id',$id)
            ->first();
        if(isset($merchant)){
            $merchant_address = Address::where( 'id',$merchant->address_id)
                ->first(array('line1','line2','line3','line4'));
        }  else {
            $merchant_address = null;
        }
        $station = Station::where('user_id' , $id)
            ->first();
        if(isset($station)){
            $station_address = Address::where('id' , $station->address_id)
                ->first(array('line1','line2','line3','line4'));
        }else{
            $station_address = null;
        }
        $currency = \DB::table('currency')
        ->where('currency.active' ,'=',1)
        ->select('currency.code')
        ->get();
            foreach ($currency as $value) {
                $currency = $value;
            }
        $roles = \DB::table('role_users')
            ->join('roles' , 'roles.id' ,'=' , 'role_users.role_id')
            ->join('merchant' , 'merchant.user_id' , '=', 'role_users.user_id')
            ->where('role_users.user_id','=',$id)
            ->where('merchant.user_id' , '=' , $id)
            ->select('slug' , 'role_users.user_id')
            ->get();
        $o = POrder::where('user_id' , $id)->select('id')->get();

          $sorders = \DB::table('deliveryorder')
                ->join('receipt' , 'deliveryorder.receipt_id' , '=' , 'receipt.id')
                ->join('porder' , 'receipt.porder_id' , '=' , 'porder.id')
                ->join('station' , 'station.user_id' , '=' , 'porder.user_id')
                ->join('sorder', 'sorder.porder_id','=','porder.id')
                ->join('orderproduct' , 'orderproduct.porder_id' , '=' , 'sorder.porder_id')
                ->join('product' , 'product.id' , '=' , 'orderproduct.product_id')
                ->join('merchantproduct' , 'orderproduct.product_id' , '=' , 'merchantproduct.product_id')
                ->join('merchant','merchantproduct.merchant_id','=','merchant.id')
                  ->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])
                ->where('porder.user_id',$id)
                ->select(
						'deliveryorder.id as doid',
                        'receipt.id as recid',
                        'porder.id as porderid',
                        'porder.receipt_tstamp as receipt_tstamp',
                        'porder.delivery_tstamp as delivery_tstamp',
                        'sorder.created_at',
                        'product.name AS description',
                        'sorder.id AS transaction_id',
                        'merchant.id AS merchant_id',
                        'product.id AS product_id',
                        'orderproduct.order_price',
						'porder.user_id AS seller_id',
                        'orderproduct.quantity'
                        )
                ->get();
            $porders = \DB::table('deliveryorder')
					->join('receipt' , 'deliveryorder.receipt_id' , '=' , 'receipt.id')
					->join('porder' , 'receipt.porder_id' , '=' , 'porder.id')
                    ->join('orderproduct', 'orderproduct.porder_id','=','porder.id')
                    ->join('product' ,'product.id','=','orderproduct.product_id')
                    ->join('merchant' , 'merchant.user_id' , '=' , 'porder.user_id')
                    ->whereBetween('porder.created_at',[$from->format('Y-m-d'),$to->format('Y-m-d')])
                    ->where('porder.user_id',$id)
                    ->select(
							'deliveryorder.id as doid',
							'receipt.id as recid',
							'porder.id as porderid',
							'porder.receipt_tstamp as receipt_tstamp',
							'porder.delivery_tstamp as delivery_tstamp',
                            'porder.created_at',
                            'porder.station_id',
                            'product.name AS description',
                            'merchant.id AS merchant_id',
                            'product_id',
                            'porder.user_id AS buyer_id',
                            'orderproduct.order_price',
                            'orderproduct.quantity'
                            )
                    ->get();
            $i = 0;$r = array();
    if(isset($roles)){
        foreach($roles as $role){
            $r[$i] =  $role->slug;
            $i++;
        }
    } $i = 1;
    if(in_array("mct",$r) || isset($merchant)){
            $user_role = "mct";
            $mer = "Merchant ID";
            $id = $merchant->id;
            $s= $merchant_address;
            $name = $name = $merchant->oshop_name;
        }
        else if(in_array("sto" , $r) || isset ($station)){
            $user_role = "sto";
            $mer = "Station ID";
            $id = $station->id;
            $s = $station_address;
            $name = $station->station_name;
        }
            return view('statement.dodetail')
            ->with('porders' ,$porders)
            ->with('sorders' , $sorders)
            ->with('roles' , $roles)
            ->with('station' , $station)
            ->with('merchant' , $merchant)
            ->with('currency' , $currency)
            ->with('merchant_address' , $merchant_address)
            ->with('station_address' , $station_address)
            ->with('name' ,$name)
            ->with('s' , $s)
            ->with('id' , $id)
            ->with('mer' , $mer)
            ->with('user_role' , $user_role);
    }
     public function showLogisticStatement($month,$year,$logistic_id="logistic")
    {
        // Test Code .
        $y2=intval($year);
        $m2=intval($month);
        if ($month==12) {
            $m2=01;
            $y2=$y2+1;
        }
        $rawMonth=$month;
        $firstcyle_sdate=$year."-".$month."-01";
        $firstcyle_edate=$year."-".$month."-16";
        $secondcyle_sdate=$year."-".$month."-16";
        $secondcyle_edate=$y2."-".($m2+1)."-01";
        if (Auth::user()->hasRole('adm')) {
            
        }else{
            // Get merchant id
            try {
                $logistic_id=Station::join('logistic','logistic.station_id','=','station.id')
                ->where('user_id',Auth::user()->id)
                ->pluck('logistic.id');
                $logistic_id=1;
            } catch (\Exception $e) {
                dump($e);
                return view('common.generic')
                ->with('message_type',"error")
                ->with('message','You do not have permission to access this resource. #001')
                ;
            }
        }
        // echo "string";
        // return $logistic_id;
        try{
            $extraData=[UtilityController::numberMonth($month),$year,$rawMonth];
            $firstcyle=$this->getLogisticStatement($logistic_id,$firstcyle_sdate,$firstcyle_edate);
           
            $secondcycle=$this->getLogisticStatement($logistic_id,$secondcyle_sdate,$secondcyle_edate);

            $cycle=[$firstcyle,$secondcycle,$extraData];
            // return $cycle;
            return view('statement.logistic_statement',compact('cycle'));
        }catch(\Exception $e){
            // dump($e);
            return view('common.generic')
                ->with('message_type',"error")
                ->with('message','You do not have permission to access this resource. #002')
                ;
        }
    }
    public function getLogisticStatement($logistic_id,$start_cycle,$end_cycle)
    {
       
      
        $data=DB::select(DB::raw(
            "
             SELECT DISTINCT SUM(op.actual_delivery_price+orn.return_price) as price,
                    delivery.delivery_administration_fee as dafee,
                    SUM(op.shipping_cost) as credit,
                    delivery.id as did,
                    op.porder_id as poid,
                    'unpaid' as status,
                    delivery.updated_at as completed_at,
                    (SUM(op.actual_delivery_price+orn.return_price) -SUM(op.shipping_cost) ) as logistic_commission

            FROM 
            delivery
            JOIN (SELECT DISTINCT porder_id FROM delivery  group by id) as porder on delivery.porder_id=porder.porder_id
            JOIN orderproduct as op on porder.porder_id=op.porder_id

            LEFT JOIN orderreturn as orn on orn.porder_id=porder.porder_id
            WHERE 
            delivery.logistic_id=".$logistic_id."
            AND (delivery.created_at BETWEEN '".$start_cycle."' AND '".$end_cycle."')

            GROUP BY delivery.id

            "
            ));
        return $data;

      
    }
       public function pdfLogisticStatement($month,$year,$logistic_id="logistic")
    {
        $y2=intval($year);
        $m2=intval($month);
        if ($month==12) {
            $m2=01;
            $y2=$y2+1;
        }
        $rawMonth=$month;
        $firstcyle_sdate=$year."-".$month."-01";
        $firstcyle_edate=$year."-".$month."-16";
        $secondcyle_sdate=$year."-".$month."-16";
        $secondcyle_edate=$y2."-".($m2+1)."-01";
          if ($logistic_id!="logistic_id" and Auth::user()->hasRole('adm')) {
            $logistic_id=$logistic_id;
        }else{
            // Get merchant id
            try {
           
                $logistic_id=1;
                 $merchant=Station::join('logistic','logistic.station_id','=','station.id')

                ->where('logistic.id','=',$logistic_id)
                ->first();
               
               
            } catch (\Exception $e) {
             return $e;
            }
        }
        
        $merchant_address=Address::find($merchant->address_id);
        $extraData=[UtilityController::numberMonth($month),$year,$rawMonth,$merchant,$merchant_address];
        $firstcyle=$this->getLogisticStatement($logistic_id,$firstcyle_sdate,$firstcyle_edate);

        $secondcycle=$this->getLogisticStatement($logistic_id,$secondcyle_sdate,$secondcyle_edate);
        $statement_file_name="statement/ops_statement_logistic_".$month."_".$year.".pdf";
        $cycle=[$firstcyle,$secondcycle,$extraData];
        // Wrapper
        $pdf=PDF::loadView('statement.pdf.log',['cycle'=>$cycle])->setOption('margin-bottom', 20)
            ->save(storage_path($statement_file_name));
    }

    public function downloadLogisticStatement($month,$year)
    {
        $this->pdfLogisticStatement($month,$year);
        $headers = array(
              'Content-Type: application/pdf',
        );
        $file_path="statement/ops_statement_logistic_".$month."_".$year.".pdf";

        return response()->download(storage_path($file_path),"statement.pdf",$headers)->deleteFileAfterSend(true);
    }

}


            
