<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Globals;
use App\Models\Ocredit;
use App\Models\MerchantProduct;
use App\Models\Product;
use App\Models\POrder;
use App\Models\QR;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\BuyerComplaint as BC;
use App\Models\Merchant;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Station;
use App\Classes\SecurityIDGenerator;
use Cart;
use Schema;
use Carbon;
use Auth;
use DB;
use DateTime;
use Session;
use QrCode;
use DNS1D;

define('UTILLOG', '/tmp/util.log');

class UtilityController extends Controller
{
      public static function log2file($data, $logfile=UTILLOG){
        $fp = fopen($logfile, 'a');
		fwrite($fp, $data."\n");
		fwrite($fp, "-----------------------------------------------\n");
		fclose($fp);
    }
 
    /*
    This controller is supposed to keep all the helper functions which are used crosswide on a frequent basis.
    some eg of such functions are :
    1) Convert an id to the standard 10-digit Square Bracket format .
    The function name should have a comment on what it does and a good name.
    use CamelCase 

    */

    public static function approveButton($id,$classArray=array(),$keyvalueArray=array())
    {
        $customContent="";
        
        foreach ($keyvalueArray as $key => $value) {
            $customContent.=$key."='".$value."'";
        }
        $class="";
        foreach ($classArray as $c) {
            # code...
            $class.=" ".$c;
        }
        echo "<button type='button' class='btn btn-primary ".$class."' ".$customContent."  > Approve </a>";

    }

    public static function rejectButton($id,$classArray=array(),$keyvalueArray=array())
    {
        $customContent="";
        
        foreach ($keyvalueArray as $key => $value) {
            $customContent.=$key."='".$value."'";
        }
        $class="";
        foreach ($classArray as $c) {
            # code...
            $class.=" ".$c;
        }
        echo "<button type='button' class='btn btn-danger ".$class."' ".$customContent."  > Reject</a>";
    }
    public static function s_id($value)
    {
        /* 
            Standarized ID
            @input : an id, eg: XX
            @ouput : [00000000XX]
            This function can't also be used as Blade directive using {{stanid($value)}} in the view file. 
        */
            $limit=10;
            $pad='0';
            $l="[";
            $r="]";
            $pad_value= $limit-strlen($value);
            if ($pad_value==0) {
                # code...
                return $l.$value.$r;

            } else{
                // $padder= $pad_value * $pad;
                try {
                     $padder=str_repeat($pad, $pad_value);
                } catch (\Exception $e) {
                    $padder=str_repeat($pad,10);
                }

                return $l.$padder.$value.$r;
            }


    }
    public static function numberMonth($monthNum){
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');
        return $monthName;
    }
    public static function nsid($value)
    {
        /* 
            Standarized ID
            @input : an id, eg: XX
            @ouput : [00000000XX]
            This function can't also be used as Blade directive using {{stanid($value)}} in the view file. 
        */
            $limit=16;
            $pad='E';
            $l="";
            $r="";
            $pad_value= $limit-strlen($value);
            if ($pad_value==0) {
                # code...
                return $l.$value.$r;

            } else{
                // $padder= $pad_value * $pad;
                try {
                     $padder=str_repeat($pad, $pad_value);
                } catch (\Exception $e) {
                    $padder=str_repeat($pad,16);
                }

                return $l.$padder.$value.$r;
            }


    }
    public static function s_date($timestamp)
    {
        # code...
        /* 
        Standarized Date
        @input : timestamp
        @output: ddMMyy hh:mm
        */
        if (is_null($timestamp)) {
            return "";
        }
        $s= strtotime($timestamp);
        return date("dMy H:i",$s);
    }
	
    public static function s_datenotime($timestamp)
    {
        # code...
        /* 
        Standarized Date
        @input : timestamp
        @output: ddMMyy hh:mm
        */
        $s= strtotime($timestamp);
        return date("dMy",$s);
    }	


    public static function cE($order_id)
    {
        /* complaint exists for a proder id. returns False if doesn't exists*/ 
        $ret= [0];
        $cond =BC::where('porder_id',$order_id)->where('status','unresolved')->first(); 
        try {
            if (!is_null($cond)){
                $ret=[1,$cond];
            }
            
        } catch (\Exception $e) {
            
        }
        return $ret;
    }
    public static function timeDiff($timestamp1,$timestamp2,$type="hour")
    {
        $diff=0;
        if ($type == "hour") {
            $diff = round((strtotime($timestamp2) - strtotime($timestamp1))/3600, 1);
        }
        return $diff;
    }
    public static function minDate ()
    {
        $present_date=Carbon::now();
        $mnyear=$present_date->subYears(18);
        return Carbon::parse($mnyear)->format('Y/m/d');
          }
    public static function maxDate()
    {
        $present_date=Carbon::now();
        $mxyear=$present_date->subYears(100);
        return Carbon::parse($mxyear)->format('Y/m/d');

    }
    public static function s_datenh($timestamp)
    {
        # code...
        /* 
        Standarized Date
        @input : timestamp
        @output: ddMMyy hh:mm
        */
        $s= strtotime($timestamp);
        return date("dMy",$s);
    }
    public static function getStationId($user_id)
    {   $ret=0;
        try {
            $st=Station::where('user_id',$user_id)->first();
            $ret=$st->id;
        } catch (\Exception $e) {
            
        }
        return $ret;
    }
    public static function hasRole($user_id,$role)
    {
        $ret=0;
        $role=DB::table('roles')
        ->join('role_users','role_users.role_id','=','roles.id')
        ->where('role_users.user_id',$user_id)
        ->where('roles.slug',$role)
        ->first();
        if (!is_null($role)) {
            $ret=1;
        }
        return $ret;
    }
    public static function currency()
    {
        return Currency::where('active', true)->first()->code;
    }
    public static function role()
    {
        $role=False;
        $r=DB::table('role_users')->where('user_id',$user_id)->first()->role_id;
        // return $r;
        if ($r==2 or $r==10) {
            $role==True;
        }
        return $role;
    }
    public static function calculateCancelTime($timestamp,$mode=''){
        if ($mode=='') {
            # code...
            $buyer_cancellation = Globals::select('buyer_cancellation_window AS minutes')->first()->minutes;
        }
        if ($mode=='merchant') {
            $buyer_cancellation = Globals::select('merchant_approve_cre_window AS days')->first()->days;
            //Convert to minutes . 
            $buyer_cancellation =$buyer_cancellation*24*60;
        }

        $cancel_time = new Carbon($timestamp);
        $cancel_time->addMinutes($buyer_cancellation);
        return  $cancel_time->toDateTimeString();
    }
	

    public static function calculateReturnProcessTime($timestamp)
    {
        $merchant_process = Globals::select('merchant_process_return_window AS days')->first();
        $cancel_time = new Carbon($timestamp);
        $cancel_time->addDays($merchant_process->days);
        return  $cancel_time->toDateTimeString();
    }

    public static function can_approve_return($timestamp)
    {
        
        $merchant_process = Globals::select('merchant_process_salesorder_window AS days')->first();
        $merchant_process=$merchant_process*24*60;
      
        $created_at = new Carbon($timestamp);
        $created_at->addMinutes($merchant_process);
        
        $now = Carbon::now();
    

        if($created_at->gt($now)){
            return  'yes';
        }else {
            return  'no';
        }
    }

    public static function calculateProcessTime($timestamp){
        $merchant_process = Globals::select('merchant_process_salesorder_window AS hours')->first();
       
		$cancel_time = new Carbon($timestamp);
        $hours=(int)$merchant_process->hours;
      
        $cancel_time->addHours($hours);
        return  $cancel_time->toDateTimeString();
    }	
	
    public static function calculateReturnTime($timestamp){
        $buyer_return = Globals::select('buyer_return_window AS days')->first();
        $return_time = new Carbon($timestamp);
        $return_time->addDays($buyer_return->days);
        return  $return_time->toDateTimeString();
    }
    /*To the dev: What does this function do? Why is it returning text. */
    public static function cancelTime($timestamp,$mode=''){
        if ($mode=='') {
            # code...
            $buyer_cancellation = Globals::select('buyer_cancellation_window AS minutes')->first()->minutes;
        }
        if ($mode=='merchant') {
            $buyer_cancellation = Globals::select('merchant_approve_cre_window AS days')->first()->days;
            //Convert to minutes . 
            $buyer_cancellation =$buyer_cancellation*24*60;
        }
		
        // $buyer_cancellation = Globals::select('buyer_cancellation_window AS minutes')->first();
        $created_at = new Carbon($timestamp);
        $created_at->addMinutes($buyer_cancellation);
		
        $now = Carbon::now();
	//	dump($created_at);
	//	dump($now);

        if($created_at->gt($now)){
            return  'yes';
        }else {
            return  'no';
        }
    }

    public static function can_return($timestamp,$mode=''){
        if ($mode=='') {
            # code...
            $buyer_cancellation = Globals::select('buyer_return_window AS days')->first()->days;
            $buyer_cancellation =$buyer_cancellation*24*60;

        }
        if ($mode=='merchant') {
            $buyer_cancellation = Globals::select('merchant_approve_cre_window AS days')->first()->days;
            //Convert to minutes . 
            $buyer_cancellation =$buyer_cancellation*24*60;
        }
        
        // $buyer_cancellation = Globals::select('buyer_cancellation_window AS minutes')->first();
        $created_at = new Carbon($timestamp);
        $created_at->addMinutes($buyer_cancellation);
        
        $now = Carbon::now();
    //  dump($created_at);
    //  dump($now);

        if($created_at->gt($now)){
            return  'yes';
        }else {
            return  'no';
        }
    }

    public static function returnTime($timestamp){
        $buyer_return = Globals::select('buyer_return_window AS days')->first();
        $created_at = new Carbon($timestamp);
        $created_at->addDays($buyer_return->days);
        $now = Carbon::now();
        //    $now = Carbon::now();
        if($created_at->gt($now)){
            return  'yes';
        }else {
            return  'no';
        }
    }

    public static function admin_opencreditbyid($id)
    {
        # Will calculate total Ocredit balance for a User
        if (!Auth::check()) {
            return 0;
        }

        //  only those without ref_no
        //$oc_rows_1=Ocredit::leftJoin('porder','ocredit.porder_id','=','porder.id')
        $oc_rows_1= DB::table('ocredit')
            ->leftJoin('porder','ocredit.porder_id','=','porder.id')
            ->leftJoin('openwish','ocredit.openwish_id','=','openwish.id')
            ->leftJoin('owarehouse','ocredit.owarehouse_id','=','owarehouse.id')
            ->leftJoin('smmout','ocredit.smmout_id','=','smmout.id')
            ->leftJoin('cre','ocredit.cre_id','=','cre.id')
            ->leftJoin('merchant','merchant.id','=','ocredit.merchant_id')

            ->whereNotNull('ocredit.mode')
            ->whereNotNull('ocredit.source')
            ->whereNull('ocredit.ref_no')
            ->where('porder.user_id',$id)
            ->whereNull('ocredit.deleted_at')

            ->select(
                'ocredit.mode as mode',
                'ocredit.id as ocid',
                'ocredit.ref_no',
                'ocredit.value',
                'ocredit.source',
            //  In this case, source_id is the id below in CASE
            //  this is fake source_id just not to place a same CASE
            //  condition for the already selected id below
                'ocredit.id as source_id',
                'ocredit.product_id as pid',
                'ocredit.created_at as cdate',
                'ocredit.porder_id',

                DB::raw('
                            (CASE 
                                WHEN ocredit.cre_id > 0 THEN
                                    (SELECT cre.user_id from cre where ocredit.cre_id = cre.id)
                                WHEN ocredit.smmout_id > 0 THEN
                                    (SELECT smmout.user_id from smmout where ocredit.smmout_id = smmout.id)
                                WHEN ocredit.openwish_id > 0 THEN
                                    (SELECT openwish.user_id from openwish where ocredit.openwish_id = openwish.id)
                                ELSE
                                    Null 
                                END) as uid
                          '),

                DB::raw('
                            (CASE 
                                WHEN ocredit.cre_id > 0 THEN
                                    ocredit.cre_id
                                WHEN ocredit.smmout_id > 0 THEN
                                    ocredit.smmout_id
                                WHEN ocredit.openwish_id > 0 THEN
                                    ocredit.openwish_id
                                ELSE
                                    Null 
                                END) as id
                          ')
            );
	
        //  Retrieving results for ref_no in a separate query just because
        //  of getting user_id as getting it separately is simple
        //$oc_rows_2=Ocredit::join('porderrefno as prr','ocredit.ref_no','=','prr.ref_no')

        $oc_rows_2=DB::table('ocredit')
            ->join('porderrefno as prr','ocredit.ref_no','=','prr.ref_no')
            ->join('porder','prr.porder_id','=','porder.id')
			->where('porder.user_id',$id)
            ->whereNotNull('ocredit.mode')
            ->whereNotNull('ocredit.source')

            ->groupBy('prr.ref_no')

            ->select(
                'ocredit.mode as mode',
                'ocredit.id as ocid',
                'ocredit.ref_no',
                'ocredit.value',
                'ocredit.source',
                'ocredit.ref_no as source_id',
                'ocredit.product_id as pid',
                'ocredit.created_at as cdate',
                'porder.user_id as uid',
                //'ocredit.ref_no as id',
                'porder.id as id',
				 'ocredit.porder_id'
            );

        //dump($oc_rows_1->get()->toArray());
        //dump($oc_rows_2->get()->toArray());
        $oc_rows = $oc_rows_1->union($oc_rows_2)->get();

        //dump(($oc_rows->toArray()));

        // Set variables
        /*$oc_debit_smm=0;
        $oc_debit_owish=0;
        $oc_debit_cre=0;
        $oc_debit_hyper=0;
        $oc_debit_mcredit=0;
        $oc_debit_purchase=0;
        $oc_debit_other=0; //could be used for anything in future!

        $oc_credit_smm=0;
        $oc_credit_owish=0;
        $oc_credit_cre=0;
        $oc_credit_hyper=0;
        $oc_credit_mcredit=0;
        $oc_credit_purchase=0;
        $oc_credit_other=0;*/

        $oc_debit = 0;
        $oc_credit = 0;
        $ocredit = 0;
        //$null_mode=0;
		//dd($oc_rows);
        //  Sum debits, credits & balance
        foreach ($oc_rows as $o) {
            if ($o->mode == "debit")
                $oc_debit = $oc_debit + $o->value;
            else if ($o->mode == "credit")
                $oc_credit = $oc_credit + $o->value;
            //else
                //$null_mode++;
        }

        /*foreach ($oc_rows_1->get() as $o) {
            if ($o->mode=="debit") {
                //  Sum All Debits regardless of Source
                $oc_debit=$oc_debit+$o->value;

                //  Sum Debits source wise
                //  'smm','openwish','hyper','cre','mcredit','purchase'
                switch ($o->source) {
                    case 'smm':
                        $oc_debit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_debit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_debit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_debit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_debit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_debit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."A");
                        $oc_debit_other+=$o->value;
                        break;
                }
            }

            if ($o->mode=="credit") {
                $oc_credit=$oc_credit+$o->value;

                switch ($o->source) {
                    case 'smm':
                        $oc_credit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_credit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_credit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_credit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_credit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_credit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."B");
                        $oc_credit_other+=$o->value;
                        break;
                }
            }
        }

        foreach ($oc_rows_2->get() as $o) {

            if ($o->mode=="debit") {
                $oc_debit=$oc_debit+$o->value;

                switch ($o->source) {
                    case 'smm':
                        $oc_debit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_debit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_debit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_debit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_debit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_debit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."C");
                        $oc_debit_other+=$o->value;
                        break;
                }
            }
            if ($o->mode=="credit") {
                $oc_credit=$oc_credit+$o->value;

                switch ($o->source) {
                    case 'smm':
                        $oc_credit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_credit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_credit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_credit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_credit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_credit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."D");
                        $oc_credit_other+=$o->value;
                        break;
                }
            }
        }*/

        // Ocredit = Debit-Credit? How do we check it's not negative
        $ocredit = $oc_credit - $oc_debit;

        if ($ocredit < 0)
            $ocredit = 0;

        //return $oc_rows;
        return compact('oc_rows','oc_debit','oc_credit','ocredit');
    }	
	
    public static function admin_opencredit()
    {
        # Will calculate total Ocredit balance for a User
        if (!Auth::check()) {
            return 0;
        }

        //  only those without ref_no
        //$oc_rows_1=Ocredit::leftJoin('porder','ocredit.porder_id','=','porder.id')
        $oc_rows_1= DB::table('ocredit')
            ->leftJoin('porder','ocredit.porder_id','=','porder.id')
            ->leftJoin('openwish','ocredit.openwish_id','=','openwish.id')
            ->leftJoin('owarehouse','ocredit.owarehouse_id','=','owarehouse.id')
            ->leftJoin('smmout','ocredit.smmout_id','=','smmout.id')
            ->leftJoin('cre','ocredit.cre_id','=','cre.id')
            ->leftJoin('merchant','merchant.id','=','ocredit.merchant_id')

            ->whereNotNull('ocredit.mode')
            ->whereNotNull('ocredit.source')
            ->whereNull('ocredit.ref_no')
            ->whereNull('ocredit.deleted_at')

            ->select(
                'ocredit.mode as mode',
                'ocredit.id as ocid',
                'ocredit.ref_no',
                'ocredit.value',
                'ocredit.source',
            //  In this case, source_id is the id below in CASE
            //  this is fake source_id just not to place a same CASE
            //  condition for the already selected id below
                'ocredit.id as source_id',
                'ocredit.product_id as pid',
                'ocredit.created_at as cdate',
                'ocredit.porder_id',

                DB::raw('
                            (CASE 
                                WHEN ocredit.cre_id > 0 THEN
                                    (SELECT cre.user_id from cre where ocredit.cre_id = cre.id)
                                WHEN ocredit.smmout_id > 0 THEN
                                    (SELECT smmout.user_id from smmout where ocredit.smmout_id = smmout.id)
                                WHEN ocredit.openwish_id > 0 THEN
                                    (SELECT openwish.user_id from openwish where ocredit.openwish_id = openwish.id)
                                ELSE
                                    Null 
                                END) as uid
                          '),

                DB::raw('
                            (CASE 
                                WHEN ocredit.cre_id > 0 THEN
                                    ocredit.cre_id
                                WHEN ocredit.smmout_id > 0 THEN
                                    ocredit.smmout_id
                                WHEN ocredit.openwish_id > 0 THEN
                                    ocredit.openwish_id
                                ELSE
                                    Null 
                                END) as id
                          ')
            );
	
        //  Retrieving results for ref_no in a separate query just because
        //  of getting user_id as getting it separately is simple
        //$oc_rows_2=Ocredit::join('porderrefno as prr','ocredit.ref_no','=','prr.ref_no')
        $oc_rows_2=DB::table('ocredit')
            ->join('porderrefno as prr','ocredit.ref_no','=','prr.ref_no')
            ->join('porder','prr.porder_id','=','porder.id')
            ->whereNotNull('ocredit.mode')
            ->whereNotNull('ocredit.source')

            ->groupBy('prr.ref_no')

            ->select(
                'ocredit.mode as mode',
                'ocredit.id as ocid',
                'ocredit.ref_no',
                'ocredit.value',
                'ocredit.source',
                'ocredit.ref_no as source_id',
                'ocredit.product_id as pid',
                'ocredit.created_at as cdate',
                'porder.user_id as uid',
                //'ocredit.ref_no as id',
                'porder.id as id',
				 'ocredit.porder_id'
            );

        //dump($oc_rows_1->get()->toArray());
        //dump($oc_rows_2->get()->toArray());
        $oc_rows = $oc_rows_1->union($oc_rows_2)->orderBy('ocid','desc')->get();

        //dump(($oc_rows->toArray()));

        // Set variables
        /*$oc_debit_smm=0;
        $oc_debit_owish=0;
        $oc_debit_cre=0;
        $oc_debit_hyper=0;
        $oc_debit_mcredit=0;
        $oc_debit_purchase=0;
        $oc_debit_other=0; //could be used for anything in future!

        $oc_credit_smm=0;
        $oc_credit_owish=0;
        $oc_credit_cre=0;
        $oc_credit_hyper=0;
        $oc_credit_mcredit=0;
        $oc_credit_purchase=0;
        $oc_credit_other=0;*/

        $oc_debit = 0;
        $oc_credit = 0;
        $ocredit = 0;
        //$null_mode=0;
		//dd($oc_rows);
        //  Sum debits, credits & balance
        foreach ($oc_rows as $o) {
            if ($o->mode == "debit")
                $oc_debit = $oc_debit + $o->value;
            else if ($o->mode == "credit")
                $oc_credit = $oc_credit + $o->value;
            //else
                //$null_mode++;
        }

        /*foreach ($oc_rows_1->get() as $o) {
            if ($o->mode=="debit") {
                //  Sum All Debits regardless of Source
                $oc_debit=$oc_debit+$o->value;

                //  Sum Debits source wise
                //  'smm','openwish','hyper','cre','mcredit','purchase'
                switch ($o->source) {
                    case 'smm':
                        $oc_debit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_debit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_debit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_debit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_debit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_debit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."A");
                        $oc_debit_other+=$o->value;
                        break;
                }
            }

            if ($o->mode=="credit") {
                $oc_credit=$oc_credit+$o->value;

                switch ($o->source) {
                    case 'smm':
                        $oc_credit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_credit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_credit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_credit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_credit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_credit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."B");
                        $oc_credit_other+=$o->value;
                        break;
                }
            }
        }

        foreach ($oc_rows_2->get() as $o) {

            if ($o->mode=="debit") {
                $oc_debit=$oc_debit+$o->value;

                switch ($o->source) {
                    case 'smm':
                        $oc_debit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_debit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_debit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_debit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_debit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_debit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."C");
                        $oc_debit_other+=$o->value;
                        break;
                }
            }
            if ($o->mode=="credit") {
                $oc_credit=$oc_credit+$o->value;

                switch ($o->source) {
                    case 'smm':
                        $oc_credit_smm+=$o->value;
                        break;
                    case 'openwish':
                        $oc_credit_owish+=$o->value;
                        break;
                    case 'hyper':
                        $oc_credit_hyper+=$o->value;
                        break;
                    case 'cre':
                        $oc_credit_cre+=$o->value;
                        break;
                    case 'mcredit':
                        $oc_credit_mcredit+=$o->value;
                        break;
                    case 'purchase':
                        $oc_credit_purchase+=$o->value;
                        break;
                    default:
                        //dump($o->id."D");
                        $oc_credit_other+=$o->value;
                        break;
                }
            }
        }*/

        // Ocredit = Debit-Credit? How do we check it's not negative
        $ocredit = $oc_credit - $oc_debit;

        if ($ocredit < 0)
            $ocredit = 0;

        //return $oc_rows;
        return compact('oc_rows','oc_debit','oc_credit','ocredit');
    }

    public static function ocreditbyid($user_id)
    {
        $oc_rows_1=Ocredit::leftJoin('porder','ocredit.porder_id','=','porder.id')
                ->leftJoin('openwish','ocredit.openwish_id','=','openwish.id')
                ->leftJoin('owarehouse','ocredit.owarehouse_id','=','owarehouse.id')
                ->leftJoin('smmout','ocredit.smmout_id','=','smmout.id')
                ->leftJoin('cre','ocredit.cre_id','=','cre.id')
                ->leftJoin('merchant','merchant.id','=','ocredit.merchant_id')
                ->where('cre.user_id','=',$user_id)
            
                ->orWhere('openwish.user_id','=',$user_id)
                ->orWhere('smmout.user_id','=',$user_id)
                ->orWhere('porder.user_id','=',$user_id)
                ->orWhere('merchant.user_id','=',$user_id)
                ->select('ocredit.mode as mode',
                    'ocredit.value as value',
                    'ocredit.source as source'
                    )

                ;

        $oc_rows_2=Ocredit::join('porderrefno as prr','ocredit.ref_no','=','prr.ref_no')
                            ->join('porder','prr.porder_id','=','porder.id')

                            ->where('porder.user_id','=',$user_id)
                            ->groupBy('prr.ref_no')

                            ->select('ocredit.mode as mode',
                            'ocredit.value as value',
                            'ocredit.source as source'
                    );
            
        // $oc_rows= $oc_rows_1->union($oc_rows_2)->get();
        
        // Set variables
        $oc_debit_smm=0;
        $oc_debit_owish=0;
        $oc_debit_cre=0;
        $oc_debit_hyper=0;
        $oc_debit_other=0; //could be used for anything in future!
        $oc_credit_smm=0;
        $oc_credit_owish=0;
        $oc_credit_cre=0;
        $oc_credit_hyper=0;
        $oc_credit_other=0;
        $oc_debit=0;
        $oc_credit=0;
        $ocredit=0;

        foreach ($oc_rows_1->get() as $o) {
            if ($o->mode=="debit") {
                $oc_debit=$oc_debit+$o->value;
                switch ($o->source) {
                    case 'smm':
                        $oc_debit_smm+=$o->value;
                        break;
                    case 'openwish':
                        # code...
                        $oc_debit_owish+=$o->value;
                        break;
                    case 'cre':
                        # code...
                        $oc_debit_cre+=$o->value;
                        break;
                    case 'hyper':
                        # code...
                        $oc_debit_hyper+=$o->value;
                        break;
                    default:
                        # code...
                        $oc_debit_other+=$o->value;
                        break;
                }
            }
            if ($o->mode=="credit") {
                $oc_credit=$oc_credit+$o->value;
                switch ($o->source) {
                    case 'smm':
                        $oc_credit_smm+=$o->value;
                        break;
                    case 'openwish':
                        # code...
                        $oc_credit_owish+=$o->value;
                        break;
                    case 'cre':
                        # code...
                        $oc_credit_cre+=$o->value;
                        break;
                    case 'hyper':
                        # code...
                        $oc_credit_hyper+=$o->value;
                        break;
                    default:
                        # code...
                        $oc_credit_other+=$o->value;
                        break;
                }
            }

        }
        foreach ($oc_rows_2->get() as $o) {
            if ($o->mode=="debit") {
                $oc_debit=$oc_debit+$o->value;
                switch ($o->source) {
                    case 'smm':
                        $oc_debit_smm+=$o->value;
                        break;
                    case 'openwish':
                        # code...
                        $oc_debit_owish+=$o->value;
                        break;
                    case 'cre':
                        # code...
                        $oc_debit_cre+=$o->value;
                        break;
                    case 'hyper':
                        # code...
                        $oc_debit_hyper+=$o->value;
                        break;
                    default:
                        # code...
                        $oc_debit_other+=$o->value;
                        break;
                }
            }
            if ($o->mode=="credit") {
                $oc_credit=$oc_credit+$o->value;
                switch ($o->source) {
                    case 'smm':
                        $oc_credit_smm+=$o->value;
                        break;
                    case 'openwish':
                        # code...
                        $oc_credit_owish+=$o->value;
                        break;
                    case 'cre':
                        # code...
                        $oc_credit_cre+=$o->value;
                        break;
                    case 'hyper':
                        # code...
                        $oc_credit_hyper+=$o->value;
                        break;
                    default:
                        # code...
                        $oc_credit_other+=$o->value;
                        break;
                }
            }

        }
        // Ocredit = Debit-Credit? How do we check it's not negative
        $ocredit=$oc_credit-$oc_debit;
		if ($ocredit < 0) $ocredit = 0;

        return compact('oc_debit_smm',
			'oc_debit_owish',
			'oc_debit_cre',
			'oc_debit_hyper',
			'oc_debit_other',
			'oc_credit_smm',
			'oc_credit_owish',
			'oc_credit_cre',
			'oc_credit_hyper',
			'oc_credit_other',
			'oc_debit',
			'oc_credit',
			'ocredit');
    }	
	
    public static function ocredit($uid=null)
    {
        # Will calculate total Ocredit balance for a User
        if (!Auth::check()) {
            return 0;
        }
        
        if (!is_null($uid) and Auth::user()->hasRole('adm')) {
            $user_id=$uid;

        }else{
            $user_id=Auth::user()->id;
        }
     

        $oc_rows_1=Ocredit::leftJoin('porder','ocredit.porder_id','=','porder.id')
                ->leftJoin('openwish','ocredit.openwish_id','=','openwish.id')
                ->leftJoin('owarehouse','ocredit.owarehouse_id','=','owarehouse.id')
                ->leftJoin('smmout','ocredit.smmout_id','=','smmout.id')
                ->leftJoin('cre','ocredit.cre_id','=','cre.id')
                ->leftJoin('merchant','merchant.id','=','ocredit.merchant_id')
                ->where('cre.user_id','=',$user_id)
                ->orWhere('openwish.user_id','=',$user_id)
                ->orWhere('smmout.user_id','=',$user_id)
                ->orWhere('porder.user_id','=',$user_id)
                ->orWhere('merchant.user_id','=',$user_id)
                ->select('ocredit.mode as mode',
                    'ocredit.value as value',
                    'ocredit.source as source'
                    )

                ;

        $oc_rows_2=Ocredit::join('porderrefno as prr','ocredit.ref_no','=','prr.ref_no')
                            ->join('porder','prr.porder_id','=','porder.id')

                            ->where('porder.user_id','=',$user_id)
                            ->groupBy('prr.ref_no')

                            ->select('ocredit.mode as mode',
                            'ocredit.value as value',
                            'ocredit.source as source'
                    );
            
        // $oc_rows= $oc_rows_1->union($oc_rows_2)->get();
        
        // Set variables
        $oc_debit_smm=0;
        $oc_debit_owish=0;
        $oc_debit_cre=0;
        $oc_debit_hyper=0;
        $oc_debit_other=0; //could be used for anything in future!
        $oc_credit_smm=0;
        $oc_credit_owish=0;
        $oc_credit_cre=0;
        $oc_credit_hyper=0;
        $oc_credit_other=0;
        $oc_debit=0;
        $oc_credit=0;
        $ocredit=0;
        $oc=OpenCreditController::get_ocredit($user_id);
        foreach ($oc as $o) {
            if ($o->mode=="debit") {
                $oc_debit=$oc_debit+$o->value;
                switch ($o->source) {
                    case 'SMM':
                        $oc_debit_smm+=$o->value;
                        break;
                    case 'Openwish':
                        # code...
                        $oc_debit_owish+=$o->value;
                        break;
                    case 'CRE':
                        # code...
                        $oc_debit_cre+=$o->value;
                        break;
                    case 'Purchase':
                        # code...
                        $oc_debit_purchase+=$o->value;
                        break;
                    default:
                        # code...
                        $oc_debit_other+=$o->value;
                        break;
                }
            }
            if ($o->mode=="credit") {
                $oc_credit=$oc_credit+$o->value;
                switch ($o->source) {
                    case 'SMM':
                        $oc_credit_smm+=$o->value;
                        break;
                    case 'Openwish':
                        # code...
                        $oc_credit_owish+=$o->value;
                        break;
                    case 'CRE':
                        # code...
                        $oc_credit_cre+=$o->value;
                        break;
                    case 'Purchase':
                        # code...
                        $oc_credit_purchase+=$o->value;
                        break;
                    default:
                        # code...
                        $oc_credit_other+=$o->value;
                        break;
                }
            }

        }
        // foreach ($oc_rows_2->get() as $o) {
        //     if ($o->mode=="debit") {
        //         $oc_debit=$oc_debit+$o->value;
        //         switch ($o->source) {
        //             case 'smm':
        //                 $oc_debit_smm+=$o->value;
        //                 break;
        //             case 'openwish':
        //                 # code...
        //                 $oc_debit_owish+=$o->value;
        //                 break;
        //             case 'cre':
        //                 # code...
        //                 $oc_debit_cre+=$o->value;
        //                 break;
        //             case 'hyper':
        //                 # code...
        //                 $oc_debit_hyper+=$o->value;
        //                 break;
        //             default:
        //                 # code...
        //                 $oc_debit_other+=$o->value;
        //                 break;
        //         }
        //     }
        //     if ($o->mode=="credit") {
        //         $oc_credit=$oc_credit+$o->value;
        //         switch ($o->source) {
        //             case 'smm':
        //                 $oc_credit_smm+=$o->value;
        //                 break;
        //             case 'openwish':
        //                 # code...
        //                 $oc_credit_owish+=$o->value;
        //                 break;
        //             case 'cre':
        //                 # code...
        //                 $oc_credit_cre+=$o->value;
        //                 break;
        //             case 'hyper':
        //                 # code...
        //                 $oc_credit_hyper+=$o->value;
        //                 break;
        //             default:
        //                 # code...
        //                 $oc_credit_other+=$o->value;
        //                 break;
        //         }
        //     }

        // }
        // Ocredit = Debit-Credit? How do we check it's not negative
        $ocredit=$oc_credit-$oc_debit;
		if ($ocredit < 0) $ocredit = 0;

        return compact('oc_debit_smm',
			'oc_debit_owish',
			'oc_debit_cre',
			'oc_debit_hyper',
			'oc_debit_other',
			'oc_credit_smm',
			'oc_credit_owish',
			'oc_credit_cre',
			'oc_credit_hyper',
			'oc_credit_other',
			'oc_debit',
			'oc_credit',
			'ocredit');
    }

    public static function porderMerchantId2($porder_id){
		$product_id=DB::table('orderproduct')->where('porder_id',$porder_id)->whereNull('deleted_at')->pluck('product_id');
        return SELF::productMerchantId($product_id);
	}
	
    public static function porderMerchantId($porder_id)
    {
        $product_id=DB::table('orderproduct')->where('porder_id',$porder_id)->whereNull('deleted_at')->pluck('product_id');
        return SELF::productMerchantId($product_id);
    }

    public static function productMerchantId($product_id)
    {
        // If type != "b2c" and merchantproduct has NO record and product.parent_id = merchantproduct.merchant_id, then product.parent_id is the merchant_id
        $merchant_id=0;
        $product=Product::find($product_id);
        if ($product->type!="b2c") {
            $mp= MerchantProduct::where('product_id',$product_id)->whereNull('deleted_at')->first();
            if (is_null($mp)) {
                $merchant_id=MerchantProduct::where('product_id',$product->parent_id)->whereNull('deleted_at')->first()->merchant_id;
            }else{
                $merchant_id=$mp->merchant_id;
            }


        }
        return $merchant_id;
    }

    public static function merchantSale($merchant_id)
    {
        // Returns total sale for a merchant
        $sales=0;

        $e= POrder::join('orderproduct as op','porder.id','=','op.porder_id')
        ->join('merchantproduct as mp','mp.product_id','=','op.product_id')
        ->where('mp.merchant_id','=',$merchant_id)
        ->groupBy('porder.id')->select('porder.payment_id')->get();
        
        foreach ($e as $k) {
            $sales+=DB::table('payment')->where('id',$k->payment_id)->first()->receivable;
        }
      
        return $sales;
    }
	
    public static function merchantuniqueid($city_id)
    {
        $stringtotal = "";
		$city = DB::table('ncityid')->where('city_id',$city_id)->first();
		if(!is_null($city)){
			$string =  $city->ncity_id; 
			
			$last = DB::table('nmerchantid')->whereRaw("SUBSTRING(nmerchantid.nmerchant_id,1,8) = '".$string."'")->orderBy('id','DESC')->first();

			if(is_null($last)){
				$num = 1;
				$stringtotal =  $city->ncity_id .
					str_pad($num,6,'0',STR_PAD_LEFT); 				
			} else {
				$last_uniqueid = $last->nmerchant_id;
				$last_num = substr($last_uniqueid, 8, 6);
				$num = intval($last_num);
				$num++;		
				$stringtotal =  $city->ncity_id .
					str_pad($num,6,'0',STR_PAD_LEFT); 
			}
			$stringtotal .= "00";			
		}
		
		return $stringtotal;
    }	
	
    public static function stationuniqueid($city_id, $type)
    {
        $stringtotal = "";
		$city = DB::table('ncityid')->where('city_id',$city_id)->first();
		if(!is_null($city)){
			$string =  $city->ncity_id; 
			$last = DB::table('nstationid')->whereRaw("SUBSTRING(nstationid.nstation_id,1,8) = '".$string."'")->orderBy('id','DESC')->first();
			if(is_null($last)){
				$num = 1;
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 				
			} else {
				$last_uniqueid = $last->nstation_id;
				$last_num = substr($last_uniqueid, 8, 6);
				$num = intval($last_num);
				$num++;		
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 
			}			
		}

		return $stringtotal;
    }	
	
    public static function branchuniqueid($city_id, $type)
    {
        $stringtotal = "";
		$city = DB::table('ncityid')->where('city_id',$city_id)->first();
		
		if(!is_null($city)){
			$string =  $city->ncity_id; 
		//	//dump("last: " . $string);
			$last = DB::table('nbranchid')->whereRaw("SUBSTRING(nbranchid.nbranch_id,1,8) = '".$string."'")->orderBy('id','DESC')->first();
			
			if(is_null($last)){
				$num = 1;
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 				
			} else {
			//	//dump("last: " . $last->nseller_id);
				$last_uniqueid = $last->nbranch_id;
				$last_num = substr($last_uniqueid, 8, 6);
				$num = intval($last_num);
				$num++;		
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 
			}			
		}

		return $stringtotal;
    }		
	
    public static function humancapuniqueid($city_id, $type)
    { 
        $stringtotal = "";
		$city = DB::table('ncityid')->where('city_id',$city_id)->first();
		
		if(!is_null($city)){
			$string =  $city->ncity_id; 
		//	//dump("last: " . $string);
			$last = DB::table('nhumancapid')->whereRaw("SUBSTRING(nhumancapid.nhumancap_id,1,8) = '".$string."'")->orderBy('id','DESC')->first();
			
			if(is_null($last)){
				$num = 1;
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 				
			} else {
			//	//dump("last: " . $last->nseller_id);
				$last_uniqueid = $last->nhumancap_id;
				$last_num = substr($last_uniqueid, 8, 6);
				$num = intval($last_num);
				$num++;		
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 
			}			
		}

		return $stringtotal;	
	}
	
	public static function selleruniqueid($city_id, $type)
    {
        $stringtotal = "";
		$city = DB::table('ncityid')->where('city_id',$city_id)->first();
		
		if(!is_null($city)){
			$string =  $city->ncity_id; 
		//	//dump("last: " . $string);
			$last = DB::table('nsellerid')->whereRaw("SUBSTRING(nsellerid.nseller_id,1,8) = '".$string."'")->orderBy('updated_at','DESC')->first();
			
			if(is_null($last)){
				$num = 1;
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 				
			} else {
			//	//dump("last: " . $last->nseller_id);
				$last_uniqueid = $last->nseller_id;
				$last_num = substr($last_uniqueid, 8, 6);
				$num = intval($last_num);
				$num++;		
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 
			}			
		}

		return $stringtotal;
    }	
	
    public static function sprovideruniqueid($city_id, $type)
    {
        $stringtotal = "";
		$city = DB::table('ncityid')->where('city_id',$city_id)->first();
		
		if(!is_null($city)){
			$string =  $city->ncity_id; 
		//	//dump("last: " . $string);
			$last = DB::table('nsproviderid')->whereRaw("SUBSTRING(nsproviderid.nsprovider_id,1,8) = '".$string."'")->orderBy('id','DESC')->first();
			
			if(is_null($last)){
				$num = 1;
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 				
			} else {
			//	//dump("last: " . $last->nseller_id);
				$last_uniqueid = $last->nsprovider_id;
				$last_num = substr($last_uniqueid, 8, 6);
				$num = intval($last_num);
				$num++;		
				$stringtotal =  $city->ncity_id . str_pad($num,6,'0',STR_PAD_LEFT) . str_pad($type,2,'0',STR_PAD_LEFT); 
			}			
		}

		return $stringtotal;
    }		
	
    public static function buyeruniqueid($city_id)
    {
        $stringtotal = "";
		$city = DB::table('ncityid')->where('city_id',$city_id)->first();
		if(!is_null($city)){
			$string =  $city->ncity_id; 
			
			$last = DB::table('nbuyerid')->whereRaw("SUBSTRING(nbuyerid.nbuyer_id,1,8) = '".$string."'" )->orderBy('updated_at','DESC')->first();

			if(is_null($last)){
				$num = 1;
				$stringtotal =  $city->ncity_id . str_pad($num,10,'0',STR_PAD_LEFT) . "00"; 
			} else {
				$last_uniqueid = $last->nbuyer_id;
				$last_num = substr($last_uniqueid, 8, 10);
				$num = ltrim($last_num, '0');
				$stringtotal =  $city->ncity_id . str_pad(($num+1),10,'0',STR_PAD_LEFT) ."00"; 
			}
		}
		return $stringtotal;
    }	
	
	public static function tproductuniqueid($merchant_id, $merchantunique_id, $type_pdr, $color, $id)
    {
		$last = DB::table('ntproductid')->join('tproduct','tproduct.id','=','ntproductid.tproduct_id')->join('merchanttproduct','tproduct.id','=','merchanttproduct.tproduct_id')->where('merchanttproduct.merchant_id',$merchant_id)->select('ntproductid.*')->orderBy('ntproductid.id','DESC')->first();
		$type = 8;
		if(is_null($last)){
			$num = 1;
			$stringtotal =  $merchantunique_id . str_pad($num,5,'0',STR_PAD_LEFT) . str_pad($color, 2, '0', STR_PAD_LEFT) . "00" . $type; 
		} else {
			$last_uniqueid = $last->ntproduct_id;
			$last_num = substr($last_uniqueid, 16, 5);
			$num = ltrim($last_num, '0');
			$stringtotal =  $merchantunique_id . str_pad(($num+1),5,'0',STR_PAD_LEFT). str_pad($color, 2, '0', STR_PAD_LEFT)  . "00" . $type; 
		}
		return $stringtotal;
	}
	
    public static function productuniqueid($merchant_id, $merchantunique_id, $type_pdr, $color, $id)
    {
        $last = DB::table('nproductid')->join('product','product.id','=','nproductid.product_id')->join('merchantproduct','product.parent_id','=','merchantproduct.product_id')->where('merchantproduct.merchant_id',$merchant_id)->select('nproductid.*')->orderBy('nproductid.id','DESC')->first();
		$type = 0;
		if($type_pdr == 'b2c'){
			$type = 1;
		}
		if($type_pdr == 'b2b'){
			$type = 2;
		}
		if($type_pdr == 'special'){
			$type = 3;
		}
		if($type_pdr == 'hyper'){
			$type = 4;
		}		
		if($type_pdr == 'voucher'){
			$type = 5;
		}	
		if($type_pdr == 'discount'){
			$type = 6;
		}	
		if($type_pdr == 'openwish'){
			$type = 7;
		}	
		if($type_pdr == 'smm'){
			$type = 8;
		}			
		if(is_null($last)){
			$num = 1;
			$stringtotal =  $merchantunique_id . str_pad($num,5,'0',STR_PAD_LEFT) . str_pad($color, 2, '0', STR_PAD_LEFT) . "00" . $type; 
		} else {
			$last_uniqueid = $last->nproduct_id;
			$last_num = substr($last_uniqueid, 16, 5);
			$num = ltrim($last_num, '0');
			$stringtotal =  $merchantunique_id . str_pad(($num+1),5,'0',STR_PAD_LEFT). str_pad($color, 2, '0', STR_PAD_LEFT)  . "00" . $type; 
		}
		return $stringtotal;
    }	
	/*Generates And Saves.*/ 
    public static function smm_unique_id($smm_id)
    {

        $newid = SELF::generaluniqueid($smm_id,"8","1",date('Y-m-d H:i:s'),'nsmmid','nsmm_id');
        DB::table('nsmmid')->insert([
                'nsmm_id'=>$newid,
                'created_at'=>date('Y-m-d H:i:s') ,
                'updated_at'=>date('Y-m-d H:i:s'),
                "smm_id"=>$smm_id
            ]);
        
    }

    public static function generaluniqueid($id, $type, $subtype, $last, $table, $field)
    {
		$time = strtotime($last);		
		$newformat = date('Y-m-d H:i:s',$time);		
		$tocompare = date('y',$time) .
					   date('m',$time) .
					   date('d',$time) .
					   date('H',$time) .
					   str_pad($type,2,'0',STR_PAD_LEFT) .
					   str_pad($subtype,2,'0',STR_PAD_LEFT);
		$lastid = DB::table($table)->whereRaw("SUBSTRING(".$table.".".$field.",1,12) = '".$tocompare."'" )->orderBy('id','DESC')->first();
		if(is_null($lastid)){
			$num = 1;
		} else {
			$last_uniqueid = $lastid->$field;
			$last_num = substr($last_uniqueid, 12);
			$num = ltrim($last_num, '0');
			$num++;
		}		
		$time = strtotime($last);		
		$newformat = date('Y-m-d H:i:s',$time);
		$stringtotal = date('y',$time) .
					   date('m',$time) .
					   date('d',$time) .
					   date('H',$time) .
					   str_pad($type,2,'0',STR_PAD_LEFT) .
					   str_pad($subtype,2,'0',STR_PAD_LEFT) .
					   str_pad(($num),8,'0',STR_PAD_LEFT);
		return $stringtotal;
    }	
	
    public static function porderuniqueid($porderid, $type)
    {
		$last = DB::table('porder')->where('id',$porderid)->first();
		$time = strtotime($last->created_at);		
		$newformat = date('Y-m-d H:i:s',$time);
		$stringtotal = date('y',$time) .
					   date('m',$time) .
					   date('d',$time) .
					   date('H',$time) .
					   $type .
					   str_pad($porderid,7,'0',STR_PAD_LEFT);
		return $stringtotal;
    }	
	
    public static function deliveryorderuniqueid($dorderid, $type)
    {
		$last = DB::table('deliveryorder')->where('id',$dorderid)->first();
		$time = strtotime($last->created_at);		
		$newformat = date('Y-m-d H:i:s',$time);		
		$stringtotal = date('y',$time) . date('m',$time) . date('d',$time) . date('H',$time). $type . str_pad($dorderid,7,'0',STR_PAD_LEFT);
		return $stringtotal;
    }

    public static function autolinkuniqueid($autolinkid, $type)
    {
		$last = DB::table('autolink')->where('id',$autolinkid)->first();
		$time = strtotime($last->created_at);		
		$newformat = date('Y-m-d H:i:s',$time);		
		$stringtotal = date('y',$time) . date('m',$time) . date('d',$time) . date('H',$time) . $type . str_pad($autolinkid,7,'0',STR_PAD_LEFT);
		return $stringtotal;
    }	

	public static function createBarcode($role_id, $table,$qrinfo){
		$filepath = public_path('/salesmemobarcode/' . $role_id);
		if (!file_exists($filepath)) {
			mkdir($filepath, 0777, true);
		}
        $base64 = DNS1D::getBarcodePNG($qrinfo, 'C128', 1.7);
        $img = base64_decode($base64);
        file_put_contents($filepath . "/barcode.png", $img);
		return "OK";
	}
	
    public static function createQr($role_id, $table,$qrinfo){
		$qr_store_path=public_path()."/images/qr/". $table ."/". $role_id ."/";
		if (!file_exists($qr_store_path)) {
			mkdir($qr_store_path, 0775, true);
		}
		$qr_info=$qrinfo;
		$qr_image_name='BY-'.str_random(10);
		QrCode::format('png')->
			encoding('UTF-8')->
			size(400)->
			generate($qr_info,$qr_store_path.$qr_image_name.".png");

		$qr= new QR;
		$qr->type='qr';
		$qr->image_path=$qr_image_name;
		$qr->save();

		DB::table($table . 'qr')->insert(['qr_management_id'=>$qr->id,
			$table . '_id' => $role_id,
			'created_at'=>date('Y-m-d H:i:s') ,
			'updated_at'=>date('Y-m-d H:i:s')]);
		return "OK";
	}

    /*Wether logged in user is hybrid or not*/ 
    public static function is_hybrid()
    {
        $ret=False;
        if (Auth::user()->hasRole('sto') and Auth::user()->hasRole('mer')) {
            $ret=True;
        }
        return $ret;
    }

    public static function realPrice($product_id)
    {
        $p=Product::find($product_id);
        $price=$p->retail_price;
        if ($p->discounted_price!=0 and $price>$p->discounted_price) {
            $price= $p->discounted_price;
        }
        // return price in cents not MYR
        return $price;
    }
	

	public static function gen_uuid($date){
		$uuid_natural =  sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
		
		$year_hex = str_pad(dechex(date('y')),2,'0',STR_PAD_LEFT);
		$month_hex = str_pad(dechex(date('m')),2,'0',STR_PAD_LEFT);
		$day_hex = str_pad(dechex(date('d')),2,'0',STR_PAD_LEFT);
		$hour_hex = str_pad(dechex(date('H')),2,'0',STR_PAD_LEFT);
		
		/*$arr_def = Array();
		$year_rand = rand(1, 31);
		$arr_def[0] = $year_rand;
		while( in_array( ($month_rand = rand(1,31)), $arr_def ) );
		$arr_def[1] = $month_rand;
		while( in_array( ($day_rand = rand(1,31)), $arr_def ) );	
		$arr_def[2] = $day_rand;
		while( in_array( ($hour_rand = rand(1,31)), $arr_def ) );	*/	
		$numbers = DB::table('idlog')->where('created_at','>=',$date . ' 00:00:00')->where('created_at','<=',$date . ' 23:59:59')->first();
		if(!is_null($numbers)){
			$original = $uuid_natural;
			$uuid_natural = substr_replace($uuid_natural, $year_hex, ($numbers->yr_pos - 1), 2);
			$uuid_natural = substr_replace($uuid_natural, $month_hex, ($numbers->mth_pos - 1), 2);
			$uuid_natural = substr_replace($uuid_natural, $day_hex, ($numbers->day_pos - 1), 2);
			$uuid_natural = substr_replace($uuid_natural, $hour_hex, ($numbers->hr_pos - 1), 2);
			$uuid_new = $uuid_natural;			
		} else {
			$uuid_new = null;
		}

		
		return $uuid_new;
	}

    public static function getOpenWishLeftDuration($created_at)
    {
        $global=DB::table('global')->first();
        $ow_duration=$global->openwish_duration;
        // Get current date
        $ow_ctime = new Carbon($created_at);
        $ow_etime=$ow_ctime->addDays($ow_duration);
        $now=Carbon::now();
        if($now->gt($ow_etime)){
            return  '00d 00h 00m';
        }else {
            // return the difference
            $difference_time=$ow_etime->diff($now)->format('%Dd %Hh %im');
            return  $difference_time;
        }
        
    }
    public function getPorderValue($porder_id)
    {
        $ret=0;
        try {
            $ret=DB::table('orderproduct')
            ->where('porder_id',$porder_id)
            ->select(DB::raw("
                SUM((orderproduct.quantity*orderproduct.order_price)) as amount
                "))
            ->pluck('amount');

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $ret;
    }
    /*
        How much merchant should get from an porder after subtracting all the charges and commission
    */ 
    public function getMerchantValue($porder_id)
    {
        $ret=0;
        $glob=DB::table('global')->first();
        $osmall_commission_percentage=$glob->osmall_commission;
        
        /*Total Porder*/ 
        $porderValue=$this->getPorderValue($porder_id);

        try {
            $osmall_commission=($osmall_commission_percentage*$porderValue)/100;
            $adminFee=$glob->order_administration_fee;
            // $deliveryAdminFee=$glob->delivery_administration_fee;
            $payment_gateway_percentage=$glob->payment_gateway_commission;
            

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $ret;
    }
    public  function getMerchantCarryOver($month,$year)
    {
        $ret=0;
        try {
            
        } catch (\Exception $e) {
            
        }
        return $ret;
    }

    /*
        Get commission value for a product
    */ 
    public static function varComm($product,$merchant_id)
    {
        
        $commission=99;
        switch ($prod->segment) {
            case 'b2c':
                $commission=$prod->osmall_commission;
                if (is_null($commission) or $commission == 0.00) {
            
                    $commission=Merchant::find($merchant_id)->osmall_commission;
                    if (is_null($commission) or $commission == 0.00) {
                      
                        $commission=0.00;
                    }
                }
                break;
            case 'b2b':
                $commission=$prod->b2b_osmall_commission;
                if (is_null($commission) or $commission == 0.00) {
            
                    $commission=Merchant::find($merchant_id)->osmall_commission;
                    if (is_null($commission) or $commission == 0.00) {
                      
                        $commission=0.00;
                    }
                }
                break;
            default:
                $commission=0.00;
                break;
        }
        
        
        return $commission;
    }
    public static function getCommission($product_id)
    {
        $global=DB::table('global')->first();
        $commission=0.00;
        $product=Product::find($product_id);
        try {
            $merchantId=UtilityController::productMerchantId($product_id);
            $merchant=Merchant::find($merchantId);
            switch ($product->segment) {
                case 'b2b':
                    # code...
                    break;
                
                default:
                    # code...
                    break;
            }
            if ($merchant->commission_type=="std") {
                $commission=$merchant->osmall_commission;
               
            }
            if($merchant->commission_type=="var "){

                
                $commission=UtilityController::varComm($product,$merchantId);
                
                
            }
            if ($commission == 0.00) {
                    // Fallback

                    $commission = $global->osmall_commission;
                    //dump("All the above commission method resulted in 0.0 or null. Fall back from global ".$commission);
                }


        } catch (\Exception $e) {

            $commission=999;
        }

        return $commission;

    }
    /*
        For primary use in cart.
        none -> No change
        merchant-> Merchant delivery_waiver_min_amt is free
        product->product delivery_waiver

        returns key-value array

    */ 
    public static function deliveryStatus($product_id)
    {
        $ret=["type"=>"none","amount"=>0];
        try {
            $merchant_id=UtilityController::productMerchantId($product_id);

            $merchant=Merchant::find($merchant_id);
            if ($merchant->delivery_waiver_min_amt > 0) {
                $ret["type"]="merchant";
                $ret["amount"]=$merchant->delivery_waiver_min_amt;
            }
            else{
                $product=Product::find($product_id);
                if ($product->free_delivery_with_purchase_amt>0) {
                    $ret["type"]="product";
                    $ret["amount"]=$product->free_delivery_with_purchase_amt;
                }
            }
        } catch (\Exception $e) {
           
        }
        return $ret;
    }

    public static function getKeyWithHighestValue($array)
    {
        $max = max($array);
        foreach ($array as $key => $val)
        {
            if ($val == $max) return $key;
        }
    }

    public static function getPorderSegment($porder_id)
    {
        $b2b=false;
        $b2c=false;
        $token=false;
        $mixed=false;
        $ret="NA";
        $ops=DB::table('orderproduct')->where('porder_id',$porder_id)->get();
        foreach ($ops as $op) {
            $p=Product::find($op->product_id);
			if($p){
				if ($p->segment == "b2c") {
					$b2c=true;
					if($b2b == true || $token == true){
						$mixed=true;
					}
				}elseif ($p->segment == "b2b") {
					$b2b=true;
					if($b2c == true || $token == true){
						$mixed=true;
					}
				}elseif ($p->segment == "token") {
					if($b2b == true || $b2c == true){
						$mixed=true;
					}
					$token=true;
				}
			} else {
				$b2c=true;
			}
        }
        if ($mixed == true) {
            $ret="mixed";
        }elseif ($b2b == true) {
            $ret="b2b";
        }elseif ($b2c == true) {
            $ret="b2c";
        }elseif ($token == true) {
            $ret="token";
        }
        return $ret;
    }

    public static  function sameBrandMerchants($merchant_id)
    {
        // Get all product 
       $merchantBrands=  DB::table('merchantproduct')
        ->join('product','product.id','=','merchantproduct.product_id')
        ->join('merchant','merchant.id','=','merchantproduct.merchant_id')
        ->where('merchant.id',$merchant_id)
        ->whereNull('merchant.deleted_at')
        ->whereNull('merchantproduct.deleted_at')
        ->whereNull('product.deleted_at')
        ->lists('product.brand_id');
        $merchantBrands=array_unique($merchantBrands);
        // $queryCompatible=implode(",",$merchantBrands);
        $allMerchantsWithSameBrands= DB::table('merchantproduct')
        ->join('product','product.id','=','merchantproduct.product_id')
        ->join('merchant','merchant.id','=','merchantproduct.merchant_id')
        ->where('merchant.id','!=',$merchant_id)
        ->whereNull('merchant.deleted_at')
        ->whereNull('merchantproduct.deleted_at')
        ->whereNull('product.deleted_at')
        ->whereIn('product.brand_id',$merchantBrands)
        ->select('merchant.id','merchant.company_name')
    
        ->groupBy('merchant.id')
        ->get();
        return $allMerchantsWithSameBrands;
    }

    public static function same_brand_oshops($oshop_id)
    {
        $oshopBrands= DB::table('oshopproduct')
        ->join('product','product.id','=','oshopproduct.product_id')
        ->join('oshop','oshop.id','=','oshopproduct.oshop_id')
        ->where('oshop.id',$oshop_id)
        ->whereNull('oshop.deleted_at')
        ->whereNull('oshopproduct.deleted_at')
        ->whereNull('product.deleted_at')
        ->lists('product.brand_id');
        $oshopBrands=array_unique($oshopBrands);
        $allOshopsWithSameBrands= DB::table('oshopproduct')
        ->join('product','product.id','=','oshopproduct.product_id')
        ->join('oshop','oshop.id','=','oshopproduct.oshop_id')
        ->where('oshop.id','!=',$oshop_id)
        ->whereNull('oshop.deleted_at')
        ->whereNull('oshopproduct.deleted_at')
        ->whereNull('product.deleted_at')
        ->whereIn('product.brand_id',$oshopBrands)
        ->select('oshop.id','oshop.oshop_name')
        ->groupBy('oshop.id')
        ->get();
        return $allOshopsWithSameBrands;

    }
    /*
        This is a utility function which will create a minimal buyer record based on user_id

    */ 
    public static function create_minimal_buyer($user_id)
    {
        $user=User::join('address','users.default_address_id','=','address.id')
                ->where('users.id',$user_id)
                ->select('address.city_id')
                ->first();

        if (is_null($user)) {
            return -1;
        }
        try {
            $buyer= new Buyer;
            $buyer->user_id=$user_id;
            $buyer->status="active";
            $buyer->save();
            $nbuyer_id = UtilityController::buyeruniqueid($user->city_id);
            DB::table('nbuyerid')->insert([
                "user_id"=>$user_id,
                "buyer_id"=>$buyer->id,
                "nbuyer_id"=>$nbuyer_id
                ]);
            return 1;
        } catch (\Exception $e) {
            return ($e->getMessage());
            return 0;
        }
    }

    public static function forget_session()
    {
        /* Any session variables you wish to be forgotten over page reload */ 
        Session::forget('checkout');
        Session::forget('amount_due');
        Session::forget('logistic_called_by_merchant');
    }

    public static function dynamic_save_loose($variables, $table,
		$prefix="",$suffix="")
    {
        $ret=0;
        $ignore=[];

        try {
            $insert=array();
            foreach ($variables as $key => $value) {
                $key=$prefix.$key.$suffix;
                if (Schema::hasColumn($table,$key) and
					!in_array($key,$ignore)) {
                    $insert[$key]=$value;
                }
            }
            $insert['created_at']=date("Y-m-d H:i:s");
            $insert['updated_at']=date("Y-m-d H:i:s");

            $ret=DB::table($table)->insertGetId($insert);

        } catch (\Exception $e) {
            
        }
        return $ret;

    }


    public static function dynamic_save($variables, $table)
    {
        $ret=0;
        $ignore=["fpx_buyerName","fpx_buyerBankBranch","fpx_buyerId"];

		if ($table == 'fpx_AC') {
			/*
			DB::table('stuff')->
				insert(['note'=>'dynamic_save:'.$table.":".
					json_encode($variables)]);
			*/
		}

        try {
            $insert=array();
            foreach ($variables as $key => $value) {
                if (Schema::hasColumn($table,$key) and
					!in_array($key,$ignore)) {
                    $insert[$key]=$value;
                }
            }
            $insert['created_at']=date("Y-m-d H:i:s");
            $insert['updated_at']=date("Y-m-d H:i:s");

			if ($table == 'fpx_AC') {
				/*
				DB::table('stuff')->
					insert(['note'=>'dynamic_save: INSERT:'.
						json_encode($insert)]);
				*/
			}

			$ret=DB::table($table)->insertGetId($insert);

        } catch (\Exception $e) {
            
        }
        return $ret;
    }

	/*
    public static function get_session_id($key)
    {
        # code...
        $first_key=0;
        session_start();
        $cart=unserialize($_SESSION[$key]);
        session_write_close();
        reset($cart);
        $first_key = key($cart);
        return $first_key;
    }

    public static function cart_session_id()
    {
        return SELF::get_session_id('cart');
    }
	*/

    public static function delete_cart($session_id)
    {
        try {
			/***** These 2 lines CRITICAL to clear the cart ****/
			session_id($session_id);
            session_start();
			/***************************************************/

			$_SESSION['cart']=NULL;
			self::log2file('delete_cart:'.$session_id, UTILLOG);
			self::log2file('delete_cart:Cart set to null', UTILLOG);

        } catch (\Exception $e) {
            dump($e);
			self::log2file('delete_cart: EXCEPTION:'.json_encode($e), UTILLOG);
        }
    }

    public function save_image($url,$destination)
    {
        try {
            $contents=file_get_contents($url);
            file_put_contents($destination,$contents);
        } catch (\Exception $e) {
            
        }
    }

    /*Get ip of the client visiit our site. Should work behind loadbalancers also.*/ 

    public static function get_ip()
    {
       
        $clientIpaddress = $_SERVER['REMOTE_ADDR'];

        // get ip address
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) 
        {
        // get ip address
        $clientIpaddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        // echo ip address
        $ret= $clientIpaddress;
        }else{
            $ret=$clientIpaddress;
        }
        return (string)$ret;
    }

    /*Returns true if the client is a mobile device*/ 
    public static function is_mobile()
    {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        $ret=false;
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
        {
             $ret=true;
        }
           
           return $ret;
    }
}
