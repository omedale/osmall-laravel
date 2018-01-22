<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\FPX;
use App\Classes\MasterCard;
use App\Classes\Delivery;
use App\Models\User;
use App\Models\POrder;
use App\Models\SMMout;
use App\Models\Channel;
use App\Models\OAcc;
use App\Models\Globals;
use App\Http\Requests;
use App\Classes\NinjaVan;
use App\Classes\CityLinkConnection as CL;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\APICapsOcreditController;
use App\Http\Controllers\PriceController;
use App\Classes\StationIntelligence;
use App\Http\Controllers\CityLinkController;
use App\Models\Address;
use App\Models\FPXAC;
use Moltin\Cart\Storage\LaravelSession;
use Moltin\Cart\Storage\Session as SessionStore;
// use App\Classes\StationIntelligence;
use Cart;
use Carbon;
use Auth;
use DB;
use PDF;
use PDO;
use Session;
use Image;
use SnappyImage;
// use Barryvdh\Snappy\ImageWrapper as SnappyImage;
use Knp\Snappy\Image as KImage;
class TestController extends Controller
{
	public function jnv1()
    {
		$nvclass = new NinjaVan;
		$access_token = $nvclass->get_access_token();
		dump($access_token);
		//return $access_token;
	}
	
    public function fbtoken() {
        return view('tests.fbtoken');
    }

    public function change_password($user_id, $new_password) {
		echo bcrypt($new_password);
        $u=User::find($user_id);
    	if (!is_null($u)) {
    		$u->password= bcrypt($new_password);
            echo bcrypt($new_password);
    		$u->save();
    		return $u->email;
    	}
    }

    public function getPDO($table) {
		$host      = env('DB_HOST', 'localhost');
		$port      = env('DB_PORT', 3306);
		$database  = env('DB_DATABASE', 'osmalldb');
		$username  = env('DB_USERNAME', 'homestead');
		$password  = env('DB_PASSWORD', 'secret');
	 
        //Instantiate the PDO object and connect to MySQL.
        $pdo = new PDO(
                'mysql:host=' . $host . ';dbname=' . $database, 
                $username, 
                $password
        );
         
        //The name of the table that we want the structure of.
        $tableToDescribe =$table;
         
        //Query MySQL with the PDO objecy.
        //The SQL statement is: DESCRIBE [INSERT TABLE NAME]
        $statement = $pdo->query('DESCRIBE ' . $tableToDescribe);
         
        //Fetch our result.
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
         
        //The result should be an array of arrays,
        //with each array containing information about the columns
        //that the table has.
        // var_dump($result);
         
        //For the sake of this tutorial, I will loop through the result
        //and print out the column names and their types.
        foreach($result as $column){
            echo $column['Field'] . ' - ' . $column['Type'], '<br>';
        }
    }

    public function a()
    {
        return redirect("b");
    }

    public function b()
    {
        return view('tests.nvsimul');
    }

    public function stuff($id=1) {  

  
        
		switch ($id) {
            case 1:
                $s=new SnappyImage;
                return SnappyImage::loadFile(url("/"))->download("test.jpg");
                break;
            case 2:
                $m= new MasterCard;
                return $m->test();
                break;
            case 3:

                dump(UtilityController::is_mobile());
                break;
            case 4:
                # code...
                // Auth::loginUsingId(360);
          
                break;
            case 5:
                $o=new OcreditvalidationController;
                return $o->get_matches(100,360);
                break;
            case 6:
               return UtilityController::get_ip();
                break;
            case 7:
                $del=new Delivery;
                return $del->get_delivery_price(2088,2);
                break;
            case 8:
                dump(Cart::contents());
                break;
            case 9:
                $p=POrder::find(9);
                $p->payment_id=$p->payment_id+1;
                $p->save();
                # code...
                break;
            case 10:
                $fpx=new FPX;
                $banks = $fpx->get_banks();
				dump($banks);
                break;
			case 11:
				$globals = DB::table('global')->first();
				$fpx= new FPX;
            
				$r=(object)array();
				$r->seller_exorderno=time();
				$r->reference_number="00112233";
				$r->amount=1;
				if ($fpx->check_production()) {
					$bank = "MB2U0227";
				} else {
					$bank = "TEST0021";
				}
				$r->buyer_bankid=$bank;
				//$r->buyer_bankbranch="SBI BANK A";

				$msg=$fpx->get_ar_msg($r);
				if ($msg == 0) {
					echo "Please log in";
					break;
				}
				dump($msg);
				//$html = "<form action='".$fpx->fpx_url_test."' type='POST'>";
				$url = $fpx->get_primary_url($globals);
				dump($url);
				$html = "<form action='".$url."' type='POST'>";
				$html .= "<table>";
				foreach ($msg as $key => $value) {
					$html .= "<tr>";
					$html .= "<td><label>".$key."&nbsp;&nbsp;</label></td>";
					$html .= "<td><input name='".$key."' value='".$value."'></td>";
					$html .= "</tr>";
				}
				$html .= "<tr><td></td><td><input type='submit'/></td></tr>";
				$html .= "</table></form>";
				echo $html;
				break;

			case 13:
				$handle=fopen(getcwd()."../../app/Http/Controllers/UtilityController.php","r");
				echo "<pre>".fread($handle,10240000)."</pre>";
				break;

            case 14:
                $fpx=FPXAC::orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    echo "<pre>".$k."</pre>";
                }

                break;
            case 15:
                # code...
                $fpx=DB::table('stuff')->
					orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    try {
                        echo "<pre>".$k."</pre>";
                    } catch (\Exception $e) {
                        dump($k);
                    }
                }
                break;
            case 16:
                $fpx=DB::table('fpxref')->
					orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    try {
                        echo "<pre>".$k."</pre>";
                    } catch (\Exception $e) {
                        dump($k);
                    }
                }
                break;
            case 17:
                $fpx=DB::table('cart')->
					orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    try {
                        echo "<pre>".$k."</pre>";
                    } catch (\Exception $e) {
                        dump($k);
                    }
                }
                break;
            case 18:
                $fpx=DB::table('ctrans')->
					orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    try {
                        echo "<pre>".$k."</pre>";
                    } catch (\Exception $e) {
                        dump($k);
                    }
                }
                break;
            case 19:
                dump(Cart::contents());
                break;
            case 20:
                echo "Session Save Path: " . ini_get( 'session.save_path');
                dump(scandir(session_save_path()));
                break;
            case 21:
                $fpx= new FPX;
                $data= $fpx->create_be();
                dump($data);
        
                $result = $fpx->post_be($data);
                dump("result=".$result);
        
                echo "<textarea style='width:100%'>".$result."</textarea>";
                break;
            case 22:
                $fpx=DB::table('fpx_AC')->
					orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    try {
                        echo "<pre>".$k."</pre>";
                    } catch (\Exception $e) {
                        dump($k);
                    }
                    
                }
                break;

            case 23:
                # code...
                // dump(session()->all()) ;
                //session_start();
                dump(scandir(session_save_path()));
                dump(session_id());
                $cart=$_SESSION;
                reset($cart);
                // $first_key = key($cart);
                // foreach ($cart as $key => $value) {
                //     dump($key);
                //     dump($value);
                // }
                // dump($first_key);
                break;
            case 24:
                return view('ninjavan.label');
                # code...
                break;
            case 25:
                return UtilityController::cart_session_id();
                break;
            case 26:
                $fpx=DB::table('nv_order_create_req')->
                    orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    try {
                        echo "<pre>".$k."</pre>";
                    } catch (\Exception $e) {
                        dump($k);
                    }
                    
                }
                break;
            case 27:
                $fpx=DB::table('nv_order_create_resp')->
                    orderBy('id','DESC')->limit(10)->get();
                foreach ($fpx as $k) {
                    try {
                        echo "<pre>".$k."</pre>";
                    } catch (\Exception $e) {
                        dump($k);
                    }
                    
                }
                break;
            case 28:
            return view("tests.nvsimul");
            
            break;
            case 29:
                $fpx=new FPX;
                $banks=$fpx->get_banks();
                dump($banks);
                foreach ($banks as $key => $value) {
                    $v=DB::table('bank')->where('code',$key)->first();
                    if (is_null($v)) {
                        echo "Bank not found for code: ".$key." <br>";
                    }
                    else{
                        echo "<strong>Bank :".$v->name."    Code: ".$key." </strong><br>";
                    }
                }
                break;
            case 30:
                # code...
                $e=new EmailController;
                $e->sendRC("zurez4u@gmail.com",1);
                $e->sendRC("zurez4u@gmail.com",2);
                $e->sendRC("zurez4u@gmail.com",3);
                $e->sendRC("zurez4u@gmail.com",4);
                $e->sendRC("zurez4u@gmail.com",7);
                break;

            case 31:
                $mn=DB::table('userninjavan')->get();
                dump($mn);
                break;
            case 32:
                # co.
            return 
        SMMout::join('smmin as sin','sin.smmout_id','=','smmout.id')
            ->join('ocredit','ocredit.smmout_id','=','smmout.id')
            ->join('product','product.id','=','smmout.product_id')
            ->join('porder','porder.id','=','sin.porder_id')
            ->join('nporderid','nporderid.porder_id','=','porder.id')
            ->where('smmout.user_id',360)
            ->select(DB::raw("
                product.id as id,
                product.name as nporder_id,
                porder.id as porder_id,
                ocredit.mode as mode,
                'SMM' as source,
                ocredit.value as value,
                DATE_FORMAT(ocredit.created_at,'%d%b%y %h:%m') as cdate
                "))
            
            ->toSql()
            ;
                break;
            case 33:
                // session_start( );
                echo session_id();
                // session_start( );
                // echo session_id();
                break;
            case 34:

                $smmout=DB::table('smmout')->
                    orderBy('id','DESC')->get();
                foreach ($smmout as $s) {
                   $v=DB::table('nsmmid')->where('smm_id',$s->id)->first();

                    if (empty($v)) {
                        UtilityController::smm_unique_id($s->id);
                    }
                }
                break;
            case 35:
            $request=new Request;
            dump($this->request->session()->all());
			echo "<img src='/images/footer/footer-flourish.png'/>";
            break;

            case 36:

                $e=new EmailController;
                $e->sendCampaignOsmall(360,5);
                break;
            case 37:

                $channel= new Channel;
                $channel->name="email";
                $channel->description="Email";
                $channel->save();
                $channel= new Channel;
                $channel->name="fb";
                $channel->description="Facebook";
                $channel->save();
               
                break;
            case 666:
                DB::table('stuff')->delete();
                // DB::table('fpxref')->delete();
                // DB::table('cart')->delete();
                break;

			default:
				return UtilityController::delete_cart($id);
		}
    }   

    public function table($table,$id,$fk="")
    {
        $op=DB::table($table)->where('id',$id)->first();
        dump($op);

        dump("*******************");
        $op=DB::table($table)->where('porder_id',$id)->get();
        dump($op);
    }
    public function nv($nporder_id)
    {
        # code...
    }
    public function testMail($email) {
        $e=new  EmailController;
        // $e->sendRC($email,106);
        $result=$e->testMail($email);
        $img=Image::make(file_get_contents("https://opensupermall.com/o/nikon"));
        $res=$img->response('png',70);
        // $result=$e->testMail("zurez4u+test@gmail.com");
        // return $result;
        if ($result==1) {
            # code...
            return "Mail Sent to ".$email;

        } else {
            return $result;
        }
    }

    /*
        FPX Payment Test Methods
    */ 
    public function fpx_webview() {
        return view("payment.fpx.test.webview");
    }

    public function nv_data($type,$tracking_id,$porder_id)
    {
        switch ($type) {
            case "pickup":
                $ret='{ 
                "shipper_id":921,   
                "status":"Successful Delivery",
                "previous_status":"On Vehicle for Delivery",
                "shipper_order_ref_no": "'.$porder_id.'",
                "timestamp":"2015-01-20 20:21:09",
                "order_id":"3b7327b9-54bf-417f-3104-f4e134ed22308",
                "tracking_id":"'.$tracking_id.'",   
                "pod": {
                    "type": "SUBSTITUTE",
                    "name": "Sarah",
                    "identity_number": "S8987615J",
                    "contact": "9987976",
                    "uri": "https://link.to/pod.jpeg",
                    "left_in_safe_place": false
                }
             }';
                break;
            case 'parcel_weight':
                $ret='{ 
                    "shipper_id":921,   
                    "status":"Parcel Size",
                    "shipper_order_ref_no": "'.$porder_id.'",
                    "timestamp":"2015-01-20 20:21:09",
                    "order_id":"3b7327b9-54bf-417f-3104-f4e134ed22308",
                    "previous_weight":"2",
                    "new_weight":"10",
                    "tracking_id":"'.$tracking_id.'", 
                 }';
                break;
            case 'parcel_size':
                $ret='{ 
                "shipper_id":921,   
                "status":"Parcel Size",
                "shipper_order_ref_no": "'.$porder_id.'",
                "timestamp":"2015-01-20 20:21:09",
                "order_id":"3b7327b9-54bf-417f-3104-f4e134ed22308",
                "previous_size":"SMALL",
                "new_size":"EXTRALARGE",
                "tracking_id":"'.$tracking_id.'", 
                }';
                break;
            case 'delivery':
                $ret='{ 
                "shipper_id":921,   
                "status":"Successful Delivery",
                "previous_status":"On Vehicle for Delivery",
                "shipper_order_ref_no": "'.$porder_id.'",
                "timestamp":"2015-01-20 20:21:09",
                "order_id":"3b7327b9-54bf-417f-3104-f4e134ed22308",
                "tracking_id":"'.$tracking_id.'",   
                "pod": {
                    "type": "SUBSTITUTE",
                    "name": "Sarah",
                    "identity_number": "S8987615J",
                    "contact": "9987976",
                    "uri": "https://link.to/pod.jpeg",
                    "left_in_safe_place": false
                }
             }';
                break;
            default:
                # code...
                break;
        }
    }

    public function nv_curl($data,$type)
    {
        $url="";
        switch ($type) {
            case 'delivery':
                $url=url('nv/sdelivery');
                break;
            case 'pickup':
                $url=url();
            case 'parcel_weight':
                $url=url('nv/parcelweight');
                # code...
                break;
            case 'pickup':
                $url=url('nv/ppickup');
                break;
            default:
                # code...
                break;
        }

        $ch = curl_init($url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'HTTP_X_NINJAVAN_HMAC_SHA256: 1234567' 
        ));                                                                                                                   
        $result = curl_exec($ch);
        return $result;
    }

    public function nv_webhook(Request $r)
    {
        // $order_id
        $ret=NULL;
        $order_id=$r->order_id;
        $type=$r->type;
        $tracking_id_array=DB::table('delivery')->
			where('porder_id',$order_id)->
			select('consignment_number')->get();

        foreach ($tracking_id_array as $key => $value) {
            $value=$value->consignment_number;
            $json=$this->nv_data($type,$value,$order_id);
          
            dump("DUMP FOR tracking_id: ".$value);
            try{
                  $ret=$this->nv_curl($json,$type);

            }catch(\Exception $e){
                dump($e);
            }
            dump($ret);
        }
    }
}
