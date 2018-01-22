<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellerHelpRequest;
use App\Models\SellerHelp;
use App\Models\Merchant;
use App\Models\Station;
use App\Models\Receipt;
use App\Models\Invoice;
use App\Models\Buyer;
use App\Models\POrder;
use App\Models\SMMout;
use App\Models\Address;
use Illuminate\Http\Request;
use Validator;
use Input;
use View;
use DB;
use Auth;
use App\Models\Product;
use App\Models\Globals;
use App\Models\Currency;
use App\Models\MerchantProduct;
use App\Models\User;
use App\OWish;
use App\Models\RoleUser;
use Carbon;
use Cart;
use App\Http\Controllers\BuyerController;
use Yajra\Datatables\Facades\Datatables;

class SellerHelpController extends Controller {

    protected $repo;
    protected $orepo;

//    function __construct(SellerHelpRepo $repo, OccupationRepo $orepo) {
//        $this->repo = $repo;
//        $this->orepo = $orepo;
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $object = $this->repo->index();
        $professional = $this->orepo->lists()->toArray();
        \View::share("directories", $this->createIndex($object));
        \View::share("professional", $professional);
        return view('sellerHelp.sellerHelp');
    }

	 public function add_employee(Request $r, $type = "member")
    {
		try{
			$sword = "Staff";
			if($type != "member"){
				if($type == "fair"){				
					$sword = "Member";	
				} else {
					$sword = "Customer";	
				}
					
			}
			if($type == "fair"){
				$type = "customer";
			}
			$email=$r->email;
			$email = str_replace(" ","",$email);
			$user = DB::table('users')->where('email',$email)->first();	
			//dd($r->userid);
			$company = DB::table('company')->where('owner_user_id',$r->userid)->first();
			$recruiter = $r->recruiter;
			//dd($recruiter);
			if(!is_null($company)){
				$emailexists = DB::table('member')->where('email',$email)->where('type',$type)->where('company_id',$company->id)->first();
				if(is_null($emailexists)){
					if(is_null($user)){
						if(is_null($recruiter)){
							$mid = DB::table('member')->insertGetId(['email'=>$email,'type'=>$type,'user_id'=>0,'company_id'=>$company->id,'member_status'=>'not exists','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
						} else {
							$mid = DB::table('member')->insertGetId(['email'=>$email,'type'=>$type,'user_id'=>0,'recruiter_id'=>$recruiter,'company_id'=>$company->id,'member_status'=>'not exists','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
						}
						$newmember = DB::table('member')->where('id',$mid)->first();
						$id= new IdController;
						$newid = $id->nB(0);
						$newemployee = array('id'=>$newid,'member_id'=>$mid,'user_id'=>0, 'name' => "", 'status' => $newmember->status, 'role' => '', 'email'=>$email);
						
						return response()->json(['status'=>'success',
						'long_message'=>'New ' . $sword . ' stored!','employee'=>$newemployee]);	
					} else {
						if(is_null($recruiter)){
							$mid = DB::table('member')->insertGetId(['email'=>$email,'type'=>$type,'user_id'=>$user->id,'company_id'=>$company->id,'member_status'=>'tagged','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
						} else {
							$mid = DB::table('member')->insertGetId(['email'=>$email,'type'=>$type,'user_id'=>$user->id,'recruiter_id'=>$recruiter,'company_id'=>$company->id,'member_status'=>'tagged','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
						}
						$roles = DB::table('roles')->where('memberlist',1)->get();
						foreach($roles as $rolee){
							$roleuserexists = DB::table('role_users')->where('user_id',$user->id)->where('role_id',$rolee->id)->first();
							if(is_null($roleuserexists)){
								$role = new RoleUser;
								$role->user_id = $user->id;
								$role->role_id = $rolee->id;
								$role->save();	
							}	
						}			
						
						$newmember = DB::table('member')->where('id',$mid)->first();
						$id= new IdController;
						$newid = $id->nB($user->id);
						$newemployee = array('id'=>$newid,'member_id'=>$mid,'user_id'=>$user->id, 'name' => $user->first_name . " " . $user->last_name, 'status' => $newmember->status, 'role' => 'Member', 'email'=>$email);
						
						return response()->json(['status'=>'success',
						'long_message'=> $sword . ' is Tagged!','employee'=>$newemployee]);						
					}
				} else {
					return response()->json(['status'=>'warning',
						'long_message'=>'Existing Tagged ' . $sword . '!']);
				}
			} else {
				return response()->json(['status'=>'error',
				'long_message'=>"Company doesn't exist! Please contact OpenSupport"]);
			}
		} catch (\Exception $e) {
			//dd($e);
			return response()->json(['status'=>'error',
				'long_message'=>'An unexpected error ocurred! Please contact OpenSupport']);
        }
	}	
	
	public function deletecampaign(Request $r){
		DB::table('companycampaign')->where('id',$r->id)->delete();
		return response()->json(['status'=>'success']);
	}	
	
	public function c_campaigns($id, $owner_id) {
		$campaigns=DB::table('companycampaign')->join('companycampaignmember','companycampaign.id','=','companycampaignmember.companycampaign_id')
					->where('companycampaignmember.member_id',$id)
					->where('companycampaign.owner_id',$owner_id)
					->orderBy('companycampaign.created_at')->get();
		 return view('seller.customer_campaign')	
            ->with('campaigns', $campaigns);
	}	
	
	public function lasttemplate($uid) {
		$campaigns=DB::table('companycampaign')->where('owner_id',$uid)->orderBy('created_at')->get();
		$campaignexists = false;
		$campaign_tosend = null;
		foreach($campaigns as $campaign){
			$campaigns_members = DB::table('companycampaignmember')->where('companycampaign_id',$campaign->id)->count();
			$campaign->customers = $campaigns_members;
			if($campaigns_members == 0){
				$campaignexists = true;	
				$campaign_tosend = DB::table('companycampaign')->where('id',$campaign->id)->first();
			}
		}
		$template = "Template is not defined.";
		if($campaignexists){
			if(!is_null($campaign_tosend->template) && $campaign_tosend->template != ""){
				$template = $campaign_tosend->template;
			}
		}
		return $template;
	}	
	
	public function stationterm(Request $r)
    {
		$user_id = $r->selluser;
		$station_id = $r->station_id;
		$termsexist = DB::table('stationterm')->where('creditor_user_id', $user_id)->where('station_id',$station_id)->first();
		$returnresp['term_limit'] = 0;
		$returnresp['term_days'] = 0;
		if(!is_null($termsexist)){
			$returnresp['term_limit'] = $termsexist->credit_limit;
			$returnresp['term_days'] = $termsexist->term_duration;
		}
		return $returnresp;
	}
	
	public function createterms(Request $r)
    {
		$user_id = $r->selluser;
		$station_id = $r->station_id;
		$term_days = $r->term_days;
		$term_limit = $r->term_limit;
		$termsexist = DB::table('stationterm')->where('creditor_user_id', $user_id)->where('station_id',$station_id)->first();
		if(is_null($termsexist)){
			DB::table('stationterm')->insert(['creditor_user_id'=>$user_id,'station_id'=>$station_id,'term_duration'=>$term_days,'credit_limit'=>$term_limit * 100,'salesman_id'=>0, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]);
		} else {
			DB::table('stationterm')->where('id',$termsexist->id)->update(['term_duration'=>$term_days,'credit_limit'=>$term_limit * 100, 'updated_at'=>date('Y-m-d H:i:s')]);
		}
		
		return response()->json(['status'=>'success']);
	}
	
	public function send_emails(Request $r, $type = "member")
    {
		
		try{
			foreach ($r->emails as $key => $email) {
				$company = DB::table('company')->where('owner_user_id',$r->userid)->first();
				$user = DB::table('users')->where('email',$email)->first();
				$e= new EmailController;		
				if($type == "fair"){
					if(is_null($user)){
						$e->fairRequest($email, $company);
					} else {
						$e->fairAdded($email, $user, $company);
					}
				} else {	
					if(is_null($user)){
						$e->employeeRequest($email, $company);
					} else {
						$role = DB::table('roles')->whereIn('id',[15,16,18,19,20,21])->join('role_users','roles.id','=','role_users.role_id')->where('role_users.user_id',$user->id)->select('roles.*')->first();
						if(is_null($role)){
							$e->employeeAdded($email, $user, $company);
						} else {
							$e->roleAdded($email, $user, $role, $company);
						}
					}
				}	
			}
			return response()->json(['status'=>'success']);
		} catch (\Exception $e) {
			//dd($e);
			return response()->json(['status'=>'error',
				'long_message'=>'An unexpected error ocurred! Please contact OpenSupport']);
        }	
	}	
	
	public function save_campaign(Request $r)
    {
		for($i = 0; $i < count($r->emails); $i++){
			$member = DB::table('companycampaignmember')->where('member_id',$r->emails[$i]['id'])->where('companycampaign_id',$r->campaign_id)->first();
			if($r->emails[$i]['action'] ==  "true"){
				if(!is_null($member)){
					DB::table('companycampaignmember')->where('id',$member->id)->update(['send'=>true, 'updated_at' => date('Y-m-d H:i:s')]);
				}
			} else {
				if(!is_null($member)){
					DB::table('companycampaignmember')->where('id',$member->id)->update(['send'=>false, 'updated_at' => date('Y-m-d H:i:s')]);
				}
			}
		}
	//	dd("DONE");
		return response()->json(['status'=>'success']);
	} 	
	
	public function campaigncustomers($id, $uid = null) {
		if ($uid != null) {
			$user_id= $id;
        } else {
			$user_id= Auth::user()->id;	
        }
		$selluser = User::find($user_id);	
		$company = DB::table('company')->where('owner_user_id',$user_id)->pluck('id');
	//	dd($user_id);
		$campaign=DB::table('companycampaign')->where('id',$id)->first();
		$customers=DB::table('member')
        ->leftJoin('users','users.id','=','member.user_id')
//		->leftJoin('companycampaignmembersegment','companycampaignmembersegment.id','=','companycampaignmember.osmallmembersegment_id')
        ->where('member.type','customer')
        ->where('member.company_id',$company)
        ->select(DB::raw("
            member.*,
            users.first_name as users_first_name,
            users.last_name as users_last_name
          "))
        ->get();		
		foreach($customers as $customer){
			$campaigns_members = DB::table('companycampaignmember')->where('member_id',$customer->id)->where('companycampaign_id',$campaign->id)->count();
			$campaignsm= DB::table('companycampaignmember')->where('member_id',$customer->id)->where('companycampaign_id',$campaign->id)->first();
			$customer->incampaign = $campaigns_members;
			$customer->send = 0;
			if(!is_null($campaignsm)){
				$customer->send = $campaignsm->send;
			}
		}
        return view('seller.campaigncustomers')	
            ->with('selluser', $selluser)		
            ->with('customers', $customers)		
            ->with('campaign', $campaign);			
	}		
	
	public function send_campaign(Request $r)
    {	
		//dd($r->campaings);
		for($i = 0; $i < count($r->campaings); $i++){
			$campaign = DB::table('companycampaign')->where('id',$r->campaings[$i])->first();
			DB::table('logcompanycampaign')->insert(['companycampaign_id'=>$r->campaings[$i]
					,'name'=>$campaign->name,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
			$members = DB::table('companycampaignmember')->where('companycampaign_id',$r->campaings[$i])->where('send',true)->get();
			foreach($members as $member){
				try{
					
					$customer = DB::table('member')->where('id',$member->member_id)->first();
					$e= new EmailController;
					$e->sendCampaign($customer->email, $r->campaings[$i]);
					
					
				} catch(\Exception $e){
				//	dd($e);
				}
			}
		}
		return response()->json(['status'=>'success']);
	} 	
	
	public function send_emails_c(Request $r)
    {
		
		try{
			$campaigns=DB::table('companycampaign')->where('owner_id',$r->userid)->orderBy('created_at')->get();
			
			$campaignexists = false;
			$campaign_tosend = null;
			$company = DB::table('company')->where('owner_user_id',$r->userid)->pluck('id');
			foreach($campaigns as $campaign){
				$campaigns_members = DB::table('companycampaignmember')->where('companycampaign_id',$campaign->id)->count();
				$campaign->customers = $campaigns_members;
				if($campaigns_members == 0){
					$campaignexists = true;	
					$campaign_tosend = DB::table('companycampaign')->where('id',$campaign->id)->first();
				}
			}
			$companyt = DB::table('company')->where('owner_user_id',$r->userid)->first();
			if(!is_null($campaign_tosend) && $campaignexists){
				DB::table('logcompanycampaign')->insert(['companycampaign_id'=>$campaign_tosend->id
					,'name'=>$campaign_tosend->name,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
				foreach ($r->emails as $key => $email) {
					$user = DB::table('users')->where('email',$email)->first();
					
					if(is_null($user)){
						$e= new EmailController;
						$e->customerRequest($email, $companyt);
					} else {
						$r=$this->send_campaign_facebook($campaign_tosend->id,$user->id);
						$member = DB::table('member')->where('email',$email)->where('company_id',$company)->where('type','customer')->first();
					//	dd($company);
						if(!is_null($member)){
							DB::table('companycampaignmember')->insert(['member_id'=>$member->id, 'send'=>true
							, 'companycampaign_id' => $campaign_tosend->id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
						}
						//$customer = DB::table('osmallmember')->where('id',$member->osmallmember_id)->first();
						$e= new EmailController;
						$e->sendCampaign($email, $campaign_tosend->id);
					}
				}
				
			} else {			
				foreach ($r->emails as $key => $email) {
					$company = DB::table('company')->where('owner_user_id',$r->userid)->first();
					$user = DB::table('users')->where('email',$email)->first();
					if(is_null($user)){
						$e= new EmailController;
						$e->customerRequest($email, $company);
					} else {
						$role = DB::table('roles')->whereIn('id',[26])->join('role_users','roles.id','=','role_users.role_id')->where('role_users.user_id',$user->id)->select('roles.*')->first();
						if(is_null($role)){
							$e= new EmailController;
							$e->customerAdded($email, $user, $company);
						} else {
							$e= new EmailController;
							$e->roleAdded($email, $user, $role, $company);
						}
					}	
				}
			}
			return response()->json(['status'=>'success']);
		} catch (\Exception $e) {
			dd($e);
			return response()->json(['status'=>'error',
				'long_message'=>'An unexpected error ocurred! Please contact OpenSupport']);
        }	
	}	
	
	public function changememberrole(Request $r, $user_id)
    {
		//dd($r->data);
		foreach ($r->data as $key => $value) {
			$isrole = DB::table('role_users')->where('role_id',$key)->where('user_id',$user_id)->first();
			if($value == "false"){
				DB::table('role_users')->where('role_id',$key)->where('user_id',$user_id)->delete();
			} else {
				if(is_null($isrole)){
					$role = new RoleUser;
					$role->user_id = $user_id;
					$role->role_id = $key;
					$role->save();
				}
			}
		}
		return response()->json(['status'=>'success']);
	}
	
	public function osmallcampaign(Request $r){
		$campaigns=DB::table('osmallcampaign')->orderBy('created_at')->get();
		$cancreate = true;
		foreach($campaigns as $campaign){
			$campaigns_members = DB::table('osmallcampaignmember')->where('osmallcampaign_id',$campaign->id)->count();
			$campaign->customers = $campaigns_members;
			if($campaigns_members == 0){
				$cancreate = false;	
			}
		}
		if($cancreate){
			$campaign_id = DB::table('osmallcampaign')->insertGetId(['name'=>'Campaign Name','template_name'=>'Template','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
			return response()->json(['status'=>'success','id'=>$campaign_id,'date'=>UtilityController::s_date(date('Y-m-d H:i:s'))]);
		} else {
			return response()->json(['status'=>'error']);
		}
		
	}
	
	public function companycampaign(Request $r){
		$campaigns=DB::table('companycampaign')->where('owner_id',$r->owner_id)->orderBy('created_at')->get();
		$cancreate = true;
		foreach($campaigns as $campaign){
			$campaigns_members = DB::table('companycampaignmember')->where('companycampaign_id',$campaign->id)->count();
			$campaign->customers = $campaigns_members;
			if($campaigns_members == 0){
				$cancreate = false;	
			}
		}
		if($cancreate){
			$campaign_id = DB::table('companycampaign')->insertGetId(['name'=>'Campaign Name','template_name'=>'Template','owner_id'=>$r->owner_id
			,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
			return response()->json(['status'=>'success','id'=>$campaign_id,'date'=>UtilityController::s_date(date('Y-m-d H:i:s'))]);
		} else {
			return response()->json(['status'=>'error']);
		}
		
	}	
	
	public function getcompanycampaignfrecuency($id){
		$frecuencies = DB::table('logcompanycampaign')->where('companycampaign_id',$id)->get();
		return view('seller.campaign_frecuency')	
            ->with('frecuencies', $frecuencies);
	}
	public function getcompanycampaigntemplate($id){
		$template = DB::table('companycampaign')->where('id',$id)->pluck('template');
		$template_name = DB::table('companycampaign')->where('id',$id)->pluck('template_name');
		return response()->json(['status'=>'success','template'=>$template,'template_name'=>$template_name]);
	}
	
	public function getosmallcampaigntemplate($id){
		$template = DB::table('osmallcampaign')->where('id',$id)->pluck('template');
		$template_name = DB::table('osmallcampaign')->where('id',$id)->pluck('template_name');
		return response()->json(['status'=>'success','template'=>$template,'template_name'=>$template_name]);
	}
	public function companycampaigntemplate(Request $r, $id){
		DB::table('companycampaign')->where('id',$id)->update(['template'=>$r->data,'template_name'=>$r->name]);
		return response()->json(['status'=>'success']);
	}
	
	public function osmallcampaigntemplate(Request $r, $id){
		DB::table('osmallcampaign')->where('id',$id)->update(['template'=>$r->data,'template_name'=>$r->name]);
		return response()->json(['status'=>'success']);
	}
	
	public function osmallcampaignname(Request $r, $id){
		DB::table('osmallcampaign')->where('id',$id)->update(['name'=>$r->data]);
		return response()->json(['status'=>'success']);
	}
	
	public function membername(Request $r, $id){
		DB::table('member')->where('id',$id)->update(['name'=>$r->data]);
		return response()->json(['status'=>'success']);
	}
	
	public function companycampaignname(Request $r, $id){
		DB::table('companycampaign')->where('id',$id)->update(['name'=>$r->data]);
		return response()->json(['status'=>'success']);
	}	

	public function companysegmentname(Request $r, $id){
		$exist = DB::table('companymembersegment')->where('id','!=',$id)->where('owner_id',$r->owner_id)
		->whereRaw("UPPER(description) = '" .strtoupper(trim($r->data)) . "'")->first();
		if(!is_null($exist)){
			$response = 'exists';
		} else {
			$response = 'success';
			DB::table('companymembersegment')->where('id',$id)->update(['description'=>$r->data]);
		}
		return response()->json(['status'=>$response]);	
	}
	
	public function osmallsegmentname(Request $r, $id){
		$exist = DB::table('osmallmembersegment')->where('id','!=',$id)->whereRaw("UPPER(description) = '" .strtoupper(trim($r->data)) . "'")->first();
		if(!is_null($exist)){
			$response = 'exists';
		} else {
			$response = 'success';
			DB::table('osmallmembersegment')->where('id',$id)->update(['description'=>$r->data]);
		}
		return response()->json(['status'=>$response]);
	}

	public function companysegmentdelete(Request $r){
		$segment = DB::table('companymembersegment')->where('id',$r->id)->first();
		$response = 'success';
		if(!is_null($segment)){
			$customers=DB::table('member')
			->leftJoin('users','users.id','=','member.user_id')
			->leftJoin('companymembersegment','companymembersegment.id','=','member.companymembersegment_id')
			->where('member.type','customer')
			->where('companymembersegment.id',$segment->id)
			->select(DB::raw("
				member.*,
				companymembersegment.description as segment,
				users.first_name as users_first_name,
				users.last_name as users_last_name
			  "))
			->count();
			if($customers > 0){
				$response = 'error';
			} else {
				DB::table('companymembersegment')->where('id',$r->id)->delete();
			}
		}
		return response()->json(['status'=>$response]);		
	}
	
	public function osmallsegmentdelete(Request $r){
		$segment = DB::table('osmallmembersegment')->where('id',$r->id)->first();
		$response = 'success';
		if(!is_null($segment)){
			$customers=DB::table('osmallmember')
			->leftJoin('users','users.id','=','osmallmember.user_id')
			->leftJoin('osmallmembersegment','osmallmembersegment.id','=','osmallmember.osmallmembersegment_id')
			->where('osmallmember.type','customer')
			->where('osmallmembersegment.id',$segment->id)
			->select(DB::raw("
				osmallmember.*,
				osmallmembersegment.description as segment,
				users.first_name as users_first_name,
				users.last_name as users_last_name
			  "))
			->count();
			if($customers > 0){
				$response = 'error';
			} else {
				DB::table('osmallmembersegment')->where('id',$r->id)->delete();
			}
		}
		return response()->json(['status'=>$response]);
	}

	public function companysegmentadd(Request $r){
		$exist = DB::table('companymembersegment')->where('owner_id',$r->owner_id)
		->whereRaw("UPPER(description) = '" .strtoupper(trim($r->description)) . "'")->first();
		$html = '';
		if(!is_null($exist)){
			$response = 'exists';
		} else {
			$segment = DB::table('companymembersegment')->insertGetId(['description'=>$r->description,'owner_id'=>$r->owner_id,'created_at'=>date('Y-m-d H:i:s')
																		,'updated_at'=>date('Y-m-d H:i:s')]);
			$nsegment = DB::table('companymembersegment')->where('id',$segment)->first();
			$response = 'success';
			$html = '<div class="row" id="segment' . $segment  . '">
				<div class=" col-sm-2">
					<a  href="javascript:void(0);" class="text-danger delete_segment" rel="' . $segment  . '"><i style="margin-top: -3px;" class="fa fa-minus-circle fa-2x"></i></a>
				</div>	
				<div class=" col-sm-10">
					<span class="segment_name" id="spansegment' . $segment  . '" rel="' . $segment  . '">' . $nsegment->description . '</span>
					<span id="inputsegment' . $segment  . '" style="display: none;">
						<input type="text" value="' . $nsegment->description . '" rel="' . $segment  . '" class="segment_input" id="inputsegmentv' . $segment  . '" />
					</span>	
				</div>
				<div class="clearfix"></div>
			</div>';
		}
		return response()->json(['status'=>$response,'html'=>$html]);		
	}
	
	public function osmallsegmentadd(Request $r){
		$exist = DB::table('osmallmembersegment')->whereRaw("UPPER(description) = '" .strtoupper(trim($r->description)) . "'")->first();
		$html = '';
		if(!is_null($exist)){
			$response = 'exists';
		} else {
			$segment = DB::table('osmallmembersegment')->insertGetId(['description'=>$r->description,'created_at'=>date('Y-m-d H:i:s')
																		,'updated_at'=>date('Y-m-d H:i:s')]);
			$nsegment = DB::table('osmallmembersegment')->where('id',$segment)->first();
			$response = 'success';
			$html = '<div class="row" id="segment' . $segment  . '">
				<div class=" col-sm-2">
					<a  href="javascript:void(0);" class="text-danger delete_segment" rel="' . $segment  . '"><i style="margin-top: -3px;" class="fa fa-minus-circle fa-2x"></i></a>
				</div>	
				<div class=" col-sm-10">
					<span class="segment_name" id="spansegment' . $segment  . '" rel="' . $segment  . '">' . $nsegment->description . '</span>
					<span id="inputsegment' . $segment  . '" style="display: none;">
						<input type="text" value="' . $nsegment->description . '" rel="' . $segment  . '" class="segment_input" id="inputsegmentv' . $segment  . '" />
					</span>	
				</div>
				<div class="clearfix"></div>
			</div>';
		}
		return response()->json(['status'=>$response,'html'=>$html]);
	}

	public function changeosmallmembersegment(Request $r, $user_id)
    {
		//dd($r->data);
		$nsegment = "";
		foreach ($r->data as $key => $value) {
			
			if($value == "false"){
				DB::table('osmembersegment')->where('segment_id',$key)->where('osmallmember_id',$user_id)->delete();
			} else {
				$segment = DB::table('osmembersegment')->where('segment_id',$key)->where('osmallmember_id',$user_id)->first();
				if(is_null($segment)){
					DB::table('osmembersegment')->insert(['segment_id'=>$key,'osmallmember_id'=>$user_id, 'created_at'=>date('Y-m-d H:i:s')
					, 'updated_at'=>date('Y-m-d H:i:s')]);
				}
				$rsegment = DB::table('osmallmembersegment')->where('id',$key)->first();
				if(!is_null($rsegment)){
					$nsegment .= $rsegment->description;
				}				
			}
		}
		return response()->json(['status'=>'success','description'=>$nsegment]);
	}	
	
	public function changemembersegment(Request $r, $user_id, $owner_id)
    {
		$company = DB::table('company')->where('owner_user_id',$owner_id)->pluck('id');
		$customer = DB::table('member')->where('type','customer')->where('company_id',$company)->where('id',$user_id)->first();
		$nsegment = "";
		foreach ($r->data as $key => $value) {
			
			if($value == "false"){
				DB::table('membersegment')->where('segment_id',$key)->where('member_id',$customer->id)->delete();
			} else {
				$segment = DB::table('membersegment')->where('segment_id',$key)->where('member_id',$customer->id)->first();
				if(is_null($segment)){
					DB::table('membersegment')->insert(['segment_id'=>$key,'member_id'=>$customer->id, 'created_at'=>date('Y-m-d H:i:s')
					, 'updated_at'=>date('Y-m-d H:i:s')]);
				}
				$rsegment = DB::table('companymembersegment')->where('id',$key)->first();
				if(!is_null($rsegment)){
					$nsegment .= $rsegment->description;
				}
			}
		}
		return response()->json(['status'=>'success','description'=>$nsegment]);
	}		
	
	public function sellermemberrole($user_id)
    {
		$memberroles = DB::table('roles')->where('memberlist',true)->get();
		$asroles = array();
		foreach($memberroles as $memberroles){
			$asroles[$memberroles->id] = DB::table('role_users')->where('role_id',$memberroles->id)->where('user_id',$user_id)->count();
		}
		return json_encode(['asroles'=>$asroles]);
	}
	
	public function sellermembersegment($user_id, $owner_id)
	{
		$html = "";
		$company = DB::table('company')->where('owner_user_id',$owner_id)->pluck('id');
		$member = DB::table('member')->where('company_id',$company)->where('type','customer')->where('id',$user_id)->first();
		$membersegments = DB::table('companymembersegment')->where('owner_id',$owner_id)->get();
		$membersegmentos = DB::table('membersegment')->where('member_id',$member->id)->get();
		foreach($membersegments as $membersegment){
			$checked = '';
			foreach($membersegmentos as $membersegmento){
				if($membersegmento->segment_id == $membersegment->id){
					$checked = 'checked';
				}
			}
				
			$html .= '<p><input type="checkbox" '.$checked.' class="customersegment" rel="'.$membersegment->id.'" /> '.$membersegment->description.'</p>';
		}
		$html .= "<div>
		</div>
		<a class='btn btn-primary savesegments pull-right' href='javascript:void(0)' > Save</a>
		<br>
		<br>";
		$html .= '<input type="hidden" value="'.$user_id.'" id="user_idrolesegment" />';
		
		return $html;		
	}
	
	public function osmallsellermembersegment($user_id)
    {
		$osmallmember = DB::table('osmallmember')->where('type','customer')->where('id',$user_id)->first();
		$html= "";
		$membersegments = DB::table('osmallmembersegment')->get();
		$membersegmentos = DB::table('osmembersegment')->where('osmallmember_id',$user_id)->get();
		foreach($membersegments as $membersegment){
			$checked = '';
			foreach($membersegmentos as $membersegmento){
				if($membersegmento->segment_id == $membersegment->id){
					$checked = 'checked';
				}
			}
			$html .= '<p><input type="checkbox" '.$checked.' class="customersegment" rel="'.$membersegment->id.'" /> '.$membersegment->description.'</p>';
		}
		$html .= "<div>
		</div>
		<a class='btn btn-primary savesegments pull-right' href='javascript:void(0)' > Save</a>
		<br>
		<br>";
		$html .= '<input type="hidden" value="'.$user_id.'" id="user_idrolesegment" />';
		
		return $html;
	}	
	
	public function deletemember(Request $r)
    {
		$company = DB::table('company')->where('owner_user_id',$r->userid)->first();
		DB::table('member')->where('email',$r->email)->where('company_id', $company->id)->delete();
		return response()->json(['status'=>'success']);
	}
	
	public function add_role(Request $r)
    {
		DB::table('role_users')->whereIn('role_id',[15,16,18,19,20,21])->where('user_id',$r->user_id)->delete();
		$valrole = DB::table('roles')->where('name',$r->val)->first();
		if(!is_null($valrole)){
			//DB::table('role_users')->insert(['user_id')
			$role = new RoleUser;
			$role->user_id = $r->user_id;
			$role->role_id = $valrole->id;
			$role->save();		
		} 
		return response()->json(['response'=>$valrole]);
	}	

	public function campaign($id=null) {
		if ($id != null) {
			$user_id= $id;
        } else {
			$user_id= Auth::user()->id;	
        }
		$selluser = User::find($user_id);		
		$campaigns=DB::table('companycampaign')->where('owner_id',$selluser->id)->orderBy('created_at')->get();
		foreach($campaigns as $campaign){
			$campaigns_members = DB::table('companycampaignmember')->join('member','member.id','=','companycampaignmember.member_id')
			->where('send',true)->where('companycampaign_id',$campaign->id)->count();
			$campaign->customers = $campaigns_members;
		}
        return view('seller.campaign')	
            ->with('selluser', $selluser)			
            ->with('campaigns', $campaigns);			
	}
	
	public function members($id=null) {
	
		if ($id != null) {
			$user_id= $id;
        } else {
			$user_id= Auth::user()->id;	
        }
		$selluser = User::find($user_id);
		$members=DB::table('member')->
			leftJoin('users','users.id','=','member.user_id')->
			join('company','member.company_id','=','company.id')->
			where('company.owner_user_id',$user_id)->
			where('member.type','member')->
			select(DB::raw("
				member.*,
				users.first_name as users_first_name,
				users.last_name as users_last_name,
				users.id as user_id
			"))
			->orderBy('created_at','DESC')
			->get();
		foreach ($members as $m) {
			$conn=SMMout::where('user_id',$m->user_id)->
			pluck('connections');
			$m->connections=$conn;
		}
     //   ->leftJoin('role_users','users.id','=','role_users.user_id')
       // ->leftJoin('roles','roles.id','=','role_users.role_id')
      //  ->join('logistic','logistic.company_id','=','company.id')


		$memberroles = DB::table('roles')->where('memberlist',true)->get();
		
		$customers=DB::table('member')
        ->leftJoin('users','users.id','=','member.user_id')
     //   ->leftJoin('role_users','users.id','=','role_users.user_id')
       // ->leftJoin('roles','roles.id','=','role_users.role_id')
        ->join('company','member.company_id','=','company.id')
		->leftJoin('companymembersegment','companymembersegment.id','=','member.companymembersegment_id')
      //  ->join('logistic','logistic.company_id','=','company.id')
        ->where('company.owner_user_id',$user_id)
        ->where('member.type','customer')
        ->select(DB::raw("
            member.*,
			companymembersegment.description as segment,
            users.first_name as users_first_name,
            users.last_name as users_last_name
          "))
		->orderBy('created_at','DESC')
        ->get();
		
		foreach($customers as $customer){
			$campaigns_members = DB::table('companycampaignmember')->where('member_id',$customer->id)->count();
			$customer->countcamp = $campaigns_members;
			
			$member_segments = DB::table('membersegment')->join('companymembersegment','companymembersegment.id','=','membersegment.segment_id')
			->where('member_id',$customer->id)->get();
			$segments = "";
			foreach($member_segments as $member_segment){
				$segments .= $member_segment->description;
			}
			$customer->segments = $segments;
		}
		
		$customerroles = DB::table('roles')->where('customerlist',true)->get();
		$campaigns=DB::table('companycampaign')->where('owner_id',$selluser->id)->orderBy('created_at')->get();
		$campaignexists = false;
		$campaign_tosend = null;
		foreach($campaigns as $campaign){
			$campaigns_members = DB::table('companycampaignmember')->where('companycampaign_id',$campaign->id)->count();
			$campaign->customers = $campaigns_members;
			if($campaigns_members == 0){
				$campaignexists = true;	
				$campaign_tosend = DB::table('companycampaign')->where('id',$campaign->id)->first();
			}
		}
        return view('seller.members')
            ->with('selluser',$selluser)
            ->with('memberroles',$memberroles)
            ->with('campaignexists',$campaignexists)
            ->with('campaign_tosend',$campaign_tosend)
            ->with('customerroles',$customerroles)
            ->with('members', $members)		
            ->with('customers', $customers);		
	}
	
	public function segments($company_user_id) {
		$segments=DB::table('companymembersegment')->where('owner_id',$company_user_id)->get();
		return view('seller.segments')	
            ->with('company_user_id', $company_user_id)
            ->with('segments', $segments);
	}	
	
	public function smmarmy_exposer($user_id)
	{
		$conn=0;
		try {
			$conn=SMMout::where('user_id',$user_id)->
			pluck('connections');
		} catch (\Exception $e) {
			return $e->getMessage();
		}

		return "<tr><td>1</td><td class='text-center'>Facebook</td><td class='text-center'>".$conn."</td></tr>";

	}

    public function ageingbalance($oid, $uid, $sellid = null) {
		if(is_null($sellid)){
			$selluser = User::find($uid);
		} else {
			$selluser = User::find($sellid);
		}
		$invoice = DB::table('invoice')->where('porder_id',$oid)->first();
		$porder = DB::table('porder')->where('id',$oid)->first();
		$station = DB::table('station')->where('user_id',$porder->user_id)->first();
		$stationterm = DB::table('stationterm')->where('creditor_user_id',$uid)->where('station_id',$station->id)->first();
		$merchantuid = $uid;
		if(is_null($stationterm)){
			$merchant = DB::table('merchant')->join('merchanttproduct','merchanttproduct.merchant_id','=','merchant.id')
			->join('ordertproduct','ordertproduct.tproduct_id','=','merchanttproduct.tproduct_id')
			->where('ordertproduct.porder_id',$oid)->pluck('merchant.user_id');
			$station = DB::table('station')->where('user_id',$uid)->first();
			$stationterm = DB::table('stationterm')->where('creditor_user_id',$merchant)->where('station_id',$station->id)->first();
			$merchantuid = $merchant;			
		}
		$merchant = DB::table('merchant')->where('user_id',$merchantuid)->first();
		$term_limit = $stationterm->credit_limit/100;
		$total_owned = 0;
		$total_now = 0;
		$current_pos = DB::table('porder')->where('mode','term')->join('invoice','invoice.porder_id','=','porder.id')
		->join('ordertproduct','ordertproduct.porder_id','=','porder.id')
		->join('merchanttproduct','ordertproduct.tproduct_id','=','merchanttproduct.tproduct_id')
		->where('invoice.status','!=','completed')
		->where('merchanttproduct.merchant_id',$merchant->id)
		->where('user_id',$station->user_id)->select('porder.*','invoice.id as invoice_id')->distinct()->get();
		//dd($current_pos);
		foreach($current_pos as $current_po){
			$tproducts_pos = DB::table('ordertproduct')->join('tproduct','tproduct.id','=','ordertproduct.tproduct_id')
			->where('porder_id',$current_po->id)->get();
			foreach($tproducts_pos as $tproducts_po){
				$total_owned += ($tproducts_po->order_price/100)*$tproducts_po->quantity;
			}
			$payments_pos = DB::table('invoicepayment')->where('invoice_id',$current_po->invoice_id)->get();
			foreach($payments_pos as $payments_po){
				$total_owned -= $payments_po->amount/100;
			}
		}
		$diff = $term_limit - $total_owned;
		return view('seller.balance_report')
			->with('selluser',$selluser)
            ->with('term_limit',$term_limit)
            ->with('diff',$diff)
            ->with('total_owned',$total_owned);		
	}
    public function ageingstatus($oid, $uid, $sellid = null) {
		if(is_null($sellid)){
			$selluser = User::find($uid);
		} else {
			$selluser = User::find($sellid);
		}
		$invoice = DB::table('invoice')->where('porder_id',$oid)->first();
		$porder = DB::table('porder')->where('id',$oid)->first();
		$station = DB::table('station')->where('user_id',$porder->user_id)->first();
		$stationterm = DB::table('stationterm')->where('creditor_user_id',$uid)->where('station_id',$station->id)->first();
		if(is_null($stationterm)){
			$merchant = DB::table('merchant')->join('merchanttproduct','merchanttproduct.merchant_id','=','merchant.id')
			->join('ordertproduct','ordertproduct.tproduct_id','=','merchanttproduct.tproduct_id')
			->where('ordertproduct.porder_id',$oid)->pluck('merchant.user_id');
			$station = DB::table('station')->where('user_id',$uid)->first();
			$stationterm = DB::table('stationterm')->where('creditor_user_id',$merchant)->where('station_id',$station->id)->first();
			
		}
		$due_date = date('Y-m-d H:i:s', strtotime($invoice->created_at . ' ' . $invoice->duration . ' days'));
		$date = $invoice->created_at;
		$term_duration = $invoice->duration;
		$diff = abs(strtotime(date('Y-m-d H:i:s')) - strtotime($due_date));
		$dtF = new \DateTime('@0');
		$dtT = new \DateTime("@$diff");
		$diffformat = $dtF->diff($dtT)->format('%a day %h hour %i min');
		//dd($diffformat);
		return view('seller.ageing_report')
            ->with('diffformat',$diffformat)
            ->with('due_date',$due_date)
			->with('selluser',$selluser)
            ->with('date',$date)
            ->with('term_duration',$term_duration);
	}
	
    public function creditor_balance($id, $user_id) {
		if (!Auth::check() ) {
             return view('common.generic')
            ->with('message_type','error')
            ->with('message','Please login to access the page')
            ->with('redirect_to_login',1);
        }
		$selluser = User::find($user_id);
		$globals=Globals::first(); 
		$invoicesp= DB::table('invoice')
				->join('invoicepayment', 'invoice.id', '=', 'invoicepayment.invoice_id')
				->leftJoin('bank', 'invoicepayment.bank_id', '=', 'bank.id')
				->where('invoice.porder_id', $id)
				->select('invoicepayment.*','bank.name as bname')
				->orderBy('invoicepayment.created_at','DESC')
				->get();
		
		return view('seller.creditor_balance')
			->with('selluser',$selluser)
			->with('porder_id',$id)
            ->with('invoices',$invoicesp);
	}	
	

    public function debtor_balance($id, $user_id, $sellid = null) {
		if (!Auth::check() ) {
             return view('common.generic')
            ->with('message_type','error')
            ->with('message','Please login to access the page')
            ->with('redirect_to_login',1);
        }
		if(is_null($sellid)){
			$selluser = User::find($user_id);
		} else {
			$selluser = User::find($sellid);
		}
		$globals=Globals::first(); 
		$invoicesp= DB::table('invoice')
				->join('invoicepayment', 'invoice.id', '=', 'invoicepayment.invoice_id')
				->leftJoin('bank', 'invoicepayment.bank_id', '=', 'bank.id')
				->where('invoice.porder_id', $id)
				->select('invoicepayment.*','bank.company_name as bname')
				->orderBy('invoicepayment.created_at','DESC')
				->get();	
		return view('seller.debtor_balance')
			->with('selluser',$selluser)
            ->with('porder_id',$id)
            ->with('invoices',$invoicesp);
	}
	
    public function cageinreport($id=null) {
		if (!Auth::check() ) {
             return view('common.generic')
            ->with('message_type','error')
            ->with('message','Please login to access the page')
            ->with('redirect_to_login',1);
        }
		$globals=Globals::first(); 
        
        if ($id != null) {
			$user_id= $id;
        } else {
			$user_id= Auth::user()->id;	
        }
		$selluser = User::find($user_id);
		
		$invoices = array();
		$invoices2= DB::table('porder')
			->join('ordertproduct', 'porder.id', '=', 'ordertproduct.porder_id')
			->join('tproduct', 'ordertproduct.tproduct_id', '=', 'tproduct.id')
			->join('invoice', 'invoice.porder_id', '=', 'porder.id')
			->join('merchanttproduct',
				'merchanttproduct.tproduct_id', '=', 'tproduct.id')
			->join('merchant',
				'merchanttproduct.merchant_id', '=', 'merchant.id')
			->where('porder.user_id', $user_id)
			->where('porder.status', '!=','cancelled')
			->select('invoice.*','porder.*','ordertproduct.*','merchanttproduct.*')
			->orderBy('porder.created_at','DESC')
			->get();	

		//dd($invoices2);
		$product_invoices2= $this->get_invoice_payment($invoices2,true);
		//dd($invoices2);
		$oid = 0;
		// return $product_orders2[0];
		foreach ($product_invoices2 as $po2) {
			$rcv_date = null;
			$due_date = null;
			$ex=DB::table('porder')->where('id',$po2['oid'])->first();
			//dd($ex);
			if(isset($ex)){
				$rcv_date = $ex->created_at;
				if($rcv_date != null and $rcv_date != ''){
					$date = Carbon::parse($rcv_date);
					$day = $date->format('d');
					$month = $date->format('m');
					$year = $date->format('Y');
					$day_after_seven_days = $day + 7;

					if ($day_after_seven_days <= 15){
						$due_date = Carbon::parse($year.'-'.$month.'-15')->format('dMy h:m');
					}else{
						$due_date = Carbon::parse($year.'-'.$month.'-30')->format('dMy h:m');
					}
				}else{
					$due_date= '';
				}
				$po2['due_date'] =   $due_date;
				$po2['rcv_date'] =   Carbon::parse($rcv_date)->format('dMy h:m');
			}
			$po2['status']=$ex->status;
			$po2['o_exec']=$ex->created_at;
			$po2['o_upd']=$ex->updated_at;

			if($oid != $po2['oid']){
				$oid = $po2['oid'];
				array_push($invoices, $po2);
			}
		}	

		return view('seller.creditor_ageing')
			->with('selluser',$selluser)
			->with('invoices',$invoices);		
	}	
	
    public function dageinreport($id=null, $station_id = null) {
		
		if (!Auth::check() ) {
             return view('common.generic')
            ->with('message_type','error')
            ->with('message','Please login to access the page')
            ->with('redirect_to_login',1);
        }
		$globals=Globals::first(); 
        
        if ($id != null && $id != 0) {
			$user_id= $id;
        } else {
			$user_id= Auth::user()->id;	
        }
		$selluser = User::find($user_id);
		$merchant_id= DB::table('merchant')->
		where('user_id',$user_id)->
		pluck('id');
		$station=null;
		if(!is_null($station_id)){
			$station= DB::table('station')->
			where('user_id',$station_id)->
			first();
		}
		$invoices = array();
		if(!is_null($merchant_id)){
			$invoices2= DB::table('porder')
				->join('ordertproduct', 'porder.id', '=', 'ordertproduct.porder_id')
				->join('tproduct', 'ordertproduct.tproduct_id', '=', 'tproduct.id')
				->join('invoice', 'invoice.porder_id', '=', 'porder.id')
				->join('merchanttproduct',
					'merchanttproduct.tproduct_id', '=', 'tproduct.id')
				->join('merchant',
					'merchanttproduct.merchant_id', '=', 'merchant.id')
				->where('merchant.id', $merchant_id)
				->where('porder.status', '!=','cancelled')
				->whereRaw('NOW() > DATE_ADD(porder.created_at,INTERVAL ' . $globals->buyer_cancellation_window . ' MINUTE)')
				->orderBy('porder.created_at','DESC');
				
			if(!is_null($station)){
				$invoices2= $invoices2->where('porder.user_id', $station->user_id);
			}
			$invoices2= $invoices2->get();
			$product_invoices2= $this->get_invoice_payment($invoices2,true);
			//dd($invoices2);
			$oid = 0;
			// return $product_orders2[0];
			foreach ($product_invoices2 as $po2) {
				$rcv_date = null;
				$due_date = null;
				$ex=DB::table('porder')->where('id',$po2['oid'])->first();

				if(isset($ex)){
					$rcv_date = $ex->created_at;
					if($rcv_date != null and $rcv_date != ''){
						$date = Carbon::parse($rcv_date);
						$day = $date->format('d');
						$month = $date->format('m');
						$year = $date->format('Y');
						$day_after_seven_days = $day + 7;

						if ($day_after_seven_days <= 15){
							$due_date = Carbon::parse($year.'-'.$month.'-15')->format('dMy h:m');
						}else{
							$due_date = Carbon::parse($year.'-'.$month.'-30')->format('dMy h:m');
						}
					}else{
						$due_date= '';
					}
					$po2['due_date'] =   $due_date;
					$po2['rcv_date'] =   Carbon::parse($rcv_date)->format('dMy h:m');
				}
				$po2['status']=$ex->status;
				$po2['o_exec']=$ex->created_at;

				if($oid != $po2['oid']){
					$oid = $po2['oid'];
					array_push($invoices, $po2);
				}
			}	
			//dd($invoices);
		}
		
		return view('seller.debtor_ageing')
            ->with('selluser',$selluser)
            ->with('station',$station)
            ->with('invoices',$invoices);		
	}
	
    public function sales_order_paginate($user_id, $start=0) {
		
		$end=$start+30;
		$globals=Globals::first(); 
        $ret=array();
		$selluser = User::find($user_id);
		$merchant_id= DB::table('merchant')->
		where('user_id',$user_id)->
		pluck('id');
		
		$station_id= DB::table('station')->
		where('user_id',$user_id)->
		pluck('id');
        if (!Auth::check()) {
            return $ret;
        }
        try {
			if(!is_null($merchant_id)){
            $ret=POrder::join('orderproduct', 'porder.id', '=', 'orderproduct.porder_id')
					->leftJoin('nporderid', 'nporderid.porder_id', '=', 'porder.id')
				->join('product', 'orderproduct.product_id', '=', 'product.id')
				->join('merchantproduct',
					'merchantproduct.product_id', '=', 'product.parent_id')
				->join('merchant',
					'merchantproduct.merchant_id', '=', 'merchant.id')
				->select(DB::raw("porder.*, SUM(orderproduct.order_price * orderproduct.quantity) as total,  DATE_FORMAT(porder.created_at,'%d%b%y %H:%i') as received, IFNULL(nporderid.nporder_id,LPAD(porder.id,16,'E')) as order_id,DATE_FORMAT(porder.updated_at,'%d%b%y %H:%i') as completed"))
				->where('merchant.id', $merchant_id)
				->where('porder.status', '!=','cancelled')
				->where('porder.mode', '=','cash')
				->whereRaw('NOT EXISTS(
					SELECT sorder.id
					FROM sorder
					WHERE sorder.porder_id = porder.id)')
				->whereRaw('NOW() > DATE_ADD(porder.created_at,INTERVAL ' . $globals->buyer_cancellation_window . ' MINUTE)')
				->groupBy("porder.id")
				->orderBy('porder.created_at','DESC'); 
			} else if(!is_null($station_id)){
				$ret=POrder::join('sorder', 'porder.id', '=', 'sorder.porder_id')
				->leftJoin('nporderid', 'nporderid.porder_id', '=', 'porder.id')
				->join('orderproduct', 'porder.id', '=', 'orderproduct.porder_id')
				->join('sproduct', 'sproduct.product_id', '=', 'orderproduct.product_id')
				->join('stationsproduct', 'stationsproduct.sproduct_id', '=', 'sproduct.id')
				->join('station', 'stationsproduct.station_id', '=', 'station.id')
				->select(DB::raw("porder.*, SUM(orderproduct.order_price * orderproduct.quantity) as total,  DATE_FORMAT(porder.created_at,'%d%b%y %H:%i') as received, IFNULL(nporderid.nporder_id,LPAD(porder.id,16,'E')) as order_id,DATE_FORMAT(porder.updated_at,'%d%b%y %H:%i') as completed"))
				->where('sorder.station_id', $station_id)
				->orderBy('porder.created_at','DESC')
				->groupBy("porder.id");
				
			}
        } catch (\Exception $e) {
            // dd($e);
        }
        return Datatables::eloquent($ret)->make(true);	
	}
    public function dashboard($id=null) {

        if (!Auth::check() ) {
             return view('common.generic')
            ->with('message_type','error')
            ->with('message','Please login to access the page')
            ->with('redirect_to_login',1);
        }
		$crereasons = DB::table('crereasons')->get();
		$globals=Globals::first(); 
        
        if ($id != null) {
			$user_id= $id;
        } else {
			$user_id= Auth::user()->id;	
        }
		$selluser = User::find($user_id);
		$merchant_id= DB::table('merchant')->
		where('user_id',$user_id)->
		pluck('id');
		
		$station_id= DB::table('station')->
		where('user_id',$user_id)->
		pluck('id');			
				
		$b= new BuyerController();
		$ordersb= array();
		/************************** SALES ORDERS *********************/
		if(!is_null($merchant_id)){
			$invoices = DB::table('porder')
				->join('ordertproduct', 'porder.id', '=', 'ordertproduct.porder_id')
				->join('tproduct', 'ordertproduct.tproduct_id', '=', 'tproduct.id')
				->join('invoice', 'invoice.porder_id', '=', 'porder.id')
				->join('merchanttproduct',
					'merchanttproduct.tproduct_id', '=', 'tproduct.id')
				->join('merchant',
					'merchanttproduct.merchant_id', '=', 'merchant.id')
				->select('porder.*','ordertproduct.*','merchanttproduct.*','merchant.*','porder.created_at as pocreated_at')
				->where('merchant.id', $merchant_id)
				->where('porder.status', '!=','cancelled')
				->whereRaw('NOW() > DATE_ADD(porder.created_at,INTERVAL ' . $globals->buyer_cancellation_window . ' MINUTE)');

			// 
			$porders2= DB::table('porder')
				->join('orderproduct', 'porder.id', '=', 'orderproduct.porder_id')
				->join('product', 'orderproduct.product_id', '=', 'product.id')
				->join('merchantproduct',
					'merchantproduct.product_id', '=', 'product.parent_id')
				->join('merchant',
					'merchantproduct.merchant_id', '=', 'merchant.id')
				->select('porder.*','orderproduct.*','merchantproduct.*','merchant.*','porder.created_at as pocreated_at')
				->where('merchant.id', $merchant_id)
				->where('porder.status', '!=','cancelled')
				->where('porder.mode', '=','cash')
				->whereRaw('NOT EXISTS(
					SELECT sorder.id
					FROM sorder
					WHERE sorder.porder_id = porder.id)')
				->whereRaw('NOW() > DATE_ADD(porder.created_at,INTERVAL ' . $globals->buyer_cancellation_window . ' MINUTE)')
				->orderBy('pocreated_at','DESC')
				->get();	
			// $porders2=DB::select(DB::raw("
			// 		SELECT 
			// 		porder.*,
			// 		op.*,
			// 		merchantproduct.*,
			// 		merchant.*,
			// 		porder.created_at as pocreated_at
			// 		FROM
			// 		porder
			// 		join orderproduct as op on op.porder_id=porder.id
			// 		join product on product.id = op.product_id
			// 		join merchantproduct as mp on mp.product_id=product.parent_id
					 
			// 		WHERE
			// 		porder.id NOT IN  (SELECT sorder.id
			// 		FROM sorder
			// 		WHERE sorder.porder_id = porder.id)
			// 		AND
			// 		NOW() > DATE_ADD(porder.created_at,INTERVAL " . $globals->buyer_cancellation_window . " MINUTE)

			// 	"));	
			// 	//$querySql = $porders2->toSql();
				//$query2 = DB::table(DB::raw("($querySql order by created_at desc) as a"))->mergeBindings($porders2);
				//dd($query2);
			$product_orders2= $this->get_porder_payment($porders2,true);
			$oid = 0;
			// return $product_orders2[0];
			foreach ($product_orders2 as $po2) {
				$rcv_date = null;
				$due_date = null;
				$ex=DB::table('porder')->where('id',$po2['oid'])->first();
				//dump($ex);
				if(isset($ex)){
					$rcv_date = $ex->created_at;
				//	dump($rcv_date);
					if($rcv_date != null and $rcv_date != ''){
						$date = Carbon::parse($rcv_date);
						$day = $date->format('d');
						$month = $date->format('m');
						$year = $date->format('Y');
						$day_after_seven_days = $day + 7;

						if ($day_after_seven_days <= 15){
							$due_date = Carbon::parse($year.'-'.$month.'-15')->format('dMy H:m');
						}else{
							$due_date = Carbon::parse($year.'-'.$month.'-30')->format('dMy H:m');
						}
					}else{
						$due_date= '';
					}
					$po2['due_date'] =   $due_date;
				//	dump($rcv_date);
					$po2['rcv_date'] = $rcv_date;
				//	dump($po2['rcv_date']);
					
				}
				$po2['status']=$ex->status;
				$po2['o_exec']=$ex->updated_at;
				// Check for return/cancel status?
				$cre=DB::table('cre')->where('porder_id',$po2['oid'])->where('status','pending')->first();
				$cre_op=null; //Orderproduct for a cre, only to be used if a return has been requested
				if (!is_null($cre)) {
					if ($cre->type=="return") {
					#Get the porder_id and get all the subsequent products where return has been requested
						$cre_op=DB::table('orderproduct')->where('porder_id',$cre->porder_id)->where('status','b-returning1')->get();
					}
				}
				$po2['cre']=$cre;
				$po2['cre_op']=$cre_op;
				if($oid != $po2['oid']){
					$oid = $po2['oid'];
					array_push($ordersb, $po2);
				}
			}			
		}
		
		if(!is_null($station_id)){
			$porders2= DB::table('porder')
			->join('sorder', 'porder.id', '=', 'sorder.porder_id')
			->join('orderproduct', 'porder.id', '=', 'orderproduct.porder_id')
			->join('sproduct', 'sproduct.product_id', '=', 'orderproduct.product_id')
			->join('stationsproduct', 'stationsproduct.sproduct_id', '=', 'sproduct.id')
			->join('station', 'stationsproduct.station_id', '=', 'station.id')
			->select('porder.*')
			->orderBy('porder.created_at','DESC')
			->where('sorder.station_id', $station_id)
			->get();			
			$product_orders2= $this->get_porder_payment($porders2,true);
			$oid = 0;
			// return $product_orders2[0];
			foreach ($product_orders2 as $po2) {
				$rcv_date = null;
				$due_date = null;
				$ex=DB::table('porder')->where('id',$po2['oid'])->first();

				if(isset($ex)){
					$rcv_date = $ex->created_at;
					if($rcv_date != null and $rcv_date != ''){
						$date = Carbon::parse($rcv_date);
						$day = $date->format('d');
						$month = $date->format('m');
						$year = $date->format('Y');
						$day_after_seven_days = $day + 7;

						if ($day_after_seven_days <= 15){
							$due_date = Carbon::parse($year.'-'.$month.'-15')->format('dMy h:m');
						}else{
							$due_date = Carbon::parse($year.'-'.$month.'-30')->format('dMy h:m');
						}
					}else{
						$due_date= '';
					}
					$po2['due_date'] =   $due_date;
					$po2['rcv_date'] =   Carbon::parse($rcv_date)->format('dMy h:m');
				}
				$po2['status']=$ex->status;
				$po2['o_exec']=$ex->created_at;
				// Check for return/cancel status?
				$cre=DB::table('cre')->where('porder_id',$po2['oid'])->where('status','pending')->first();
				$cre_op=null; //Orderproduct for a cre, only to be used if a return has been requested
				if (!is_null($cre)) {
					if ($cre->type=="return") {
					#Get the porder_id and get all the subsequent products where return has been requested
						$cre_op=DB::table('orderproduct')->where('porder_id',$cre->porder_id)->where('status','b-returning1')->get();
					}
				}
				$po2['cre']=$cre;
				$po2['cre_op']=$cre_op;
				if($oid != $po2['oid']){
					$oid = $po2['oid'];
					array_push($ordersb, $po2);
				}
			}			
		}
        $mautolink = true;
        $ishybrid = false;
		$autolinksb=null;
		if(!is_null($station_id) && !is_null($merchant_id)){
			$ishybrid = true;
		}
		if(!is_null($merchant_id)){
			$autolinks= DB::table('autolink')->
				select('autolink.id as id',
					'autolink.sproperty_id as sproperty_id',
					'autolink.linked_since as l_s',
					'autolink.status as status',
					'users.first_name as first_name',
					'users.last_name as last_name',
					'users.id as user_id',
					'buyer.photo_1 as photo_1',
					'autolink.created_at')->
				join('users','autolink.initiator','=','users.id')->
					leftJoin('buyer','buyer.user_id','=','users.id')->
					where('responder',$merchant_id)->
					where('autolink.status', '!=','unlinked')->
					where('autolink.status', '!=','')->
					orderBy('autolink.created_at','DESC')->
					distinct()->
					get();	
			$autolinksb= $b->get_autolink($user_id);
		} else {
			$mautolink = false;
			$autolinks= $b->get_autolink($user_id);
		}

        $o= new OpenWishController();
		if(!is_null($merchant_id)){
			$openwish= $o->get_openwish('merchant',$user_id);
		} else {
			$openwish= $o->get_openwish('station',$user_id);
		}
        
		
        $currency = Currency::where('active', 1)->first();
        $merchantvouchers= DB::table('merchantvoucher')->where('merchant_id', $merchant_id)->get();
        //return $merchantvouchers;
        $voucher_data = [];
        if (!empty($merchantvouchers)){
            foreach ($merchantvouchers as $v){
                $vouchers = Voucher::where('id','=',$v->voucher_id)->orderBy('created_at', 'desc')->get();

                foreach ($vouchers as $i) {

                    $temp['voucher_id'] = $v->voucher_id;
                    $temp['merchant_id'] = $v->voucher_id;
                    $temp['current_merchant_id'] = $merchant_id;
                    $temp['voucher'] = $i;
                    //$temp['voucher_timeslot']=$i->timeSlots;
                    $product_id = $i->product_id;
                    $product = DB::table('product')->where('product.id', '=', $product_id)->first();
                    if (!is_null($product)) {
                        # code...
                        $temp['voucher_product'] = $product;
                    }
                    
                    $temp['voucher_timeslot'] = DB::table('timeslot')->select('*')->where('voucher_id', $i->id)->first();;
                    $temp['voucher_address'] = $i->address;
                    $voucher_data[] = $temp;
                }
            }
        }        
		
		/**************** STATEMENT ***************/
		$stmt = "sov";
		$st_controller = new StatementController;
		$arr = $st_controller->get_all($user_id, $stmt);
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
			if(!is_null($actual_year) && count($actual_year) > 0){		
				$actual_year = $actual_year->created_at;
				if($actual_year->year != $today->year){
					$current_year = 0;
				}else{
					$current_year = 1;
				}
			}
        }
		/************************ Receipt *******************************/
		$arrrec = $st_controller->get_all($user_id, $stmt);
		$ireturnrec = $arrrec['ireturn'];
		$todayrec = $arrrec['today'];
		$myreturnrec = $arrrec['myreturn'];
		$merrec = $arrrec['mer'];
		$idrec = $arrrec['id'];
		$companyrec = $arrrec['company'];
		$srec = $arrrec['s'];
		$yearsrec = $arrrec['years'];
        $actual_yearrec = $arrrec['actual_year'];
		
        $current_yearrec = 0;
        if(isset($actual_yearrec)){
			if(count($actual_yearrec) > 0){
				$actual_yearrec = $actual_yearrec->created_at;
				if($actual_yearrec->year != $todayrec->year){
					$current_yearrec = 0;
				}else{
					$current_yearrec = 1;
				}
			}
        }		
		// return $cre;
		// Statement
		// Statemement Ends
        return view('seller.dashboard',compact('ireturn','today','myreturn','current_year','ireturnrec','todayrec','myreturnrec','current_yearrec'))
			->with('mer',$mer)->with('id',$user_id)->with('company',$company)->with('s' , $s)->with('years',$years)->with('title','Statement')
			->with('merrec',$merrec)->with('companyrec',$companyrec)->with('srec' , $srec)->with('yearsrec',$yearsrec)->with('titlerec','Receipt/Tax Invoice Issued')
            ->with('product_orders2',$product_orders2)
            ->with('ordersb',$ordersb)
            ->with('ishybrid',$ishybrid)
            ->with('autolinks',$autolinks)
            ->with('autolinksb',$autolinksb)
            ->with('mautolink',$mautolink)
            ->with('selluser',$selluser)
            ->with('voucher_data',$voucher_data)
            ->with('openwish',$openwish)
			->with('currency_code' , $currency->code)
            ->with('merchant_id',$merchant_id)
            ->with('station_id',$station_id)
            ->with('crereasons', $crereasons)
            ->with('globals', $globals);
    }
	
	public function merchantsubscribe(){
		$formData = Input::all();
		$tokenuser = $formData['tokenuser'];
		$tokentied = $formData['tokentied'];
		$qty = DB::table('userstoken')->where('user_id',$tokenuser)->pluck('qty');
		$facility = DB::table('facility')->where('id',$tokentied)->pluck('token_subscription_fee');
		$facility_ecom = DB::table('facility')->where('id',$tokentied)->where('name','ecomm')->first();
		if($qty < $facility){
			$arrres['status'] = 'error';
			$arrres['tokens'] = $facility;
		} else {
			DB::table('sellerfacility')->insert(['user_id'=>$tokenuser,'facility_id'=>$tokentied
			,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
			$facilityss = DB::table('facility')->where('id',$tokentied)->first();
			$token = DB::table('userstoken')->where('user_id',$tokenuser)->first();
			$ntoken = $token->qty;
			if($facility> 0){
				
				$ntoken = $token->qty - $facility;
				DB::table('userstoken')->where('user_id',$tokenuser)->update(['qty'=>$ntoken]);
				DB::table('tokenlog')->insert([
					'user_id'=>$tokenuser,
					'token_id'=>$token->id,
					'facility_id'=>$facilityss->id,
					'value'=>$facility,
					'created_at'=>date('Y-m-d H:i:s'),
					'updated_at'=>date('Y-m-d H:i:s')]);
			}
			if(!is_null($facility_ecom)){
				$mrole = DB::table('roles')->where('slug','mer')->first();
				if(!is_null($mrole)){
					$ism = DB::table('role_users')->where('user_id',$tokenuser)->where('role_id',$mrole->id)->count();
					if($ism == 0){
						DB::table('role_users')->insert(['role_id'=>$mrole->id,'user_id'=>$tokenuser,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
					}
				}
			}
			$arrres['status'] = 'success';
			$arrres['tokens'] = $facility;
			$arrres['ntoken'] = $ntoken;
			
		}
		return response()->json($arrres);
	}
	public function merchanttoken(){
		$formData = Input::all();
		$glob = DB::table('global')->first();
		$product_id = $formData['product_id'];
		$product = Product::where('id', $product_id)->first();	
		$merchant_min = $glob->min_merchant_token_value;
		$tokenuser = $formData['tokenuser'];
		$tokenvalue = $formData['tokenvalue'];
		$tokentied = $formData['tokentied'];
		$merchant_id = DB::table('merchant')->where('user_id',$tokenuser)->pluck('id');
		if(is_null($product)){
			$arrres['status'] = 'error';
		} else {
			$price = $product->discounted_price;
			$tokenquantity = $product->retail_price;
			$image = $product->photo_1;
			$mp=DB::table('merchantproduct')->where('product_id',$product->id)->whereNull('deleted_at')->first();
			Cart::insert(array(
				'id' => $product->id,
				'parent_id' => $product->id,
				'name' => 'Token',
				'price' => $price,
				'merchant_id' => $mp->merchant_id,
				'delivery_price' =>0,
				'actual_delivery_price'=>0,
				'quantity' => 1,
				'image' => $product->photo_1,
				'gst'=>0,
				'mode'=>'token',
				'tokenquantity'=>$tokenquantity,
                'page'=> $tokentied
			));
			$arrres['status'] = 'success';
		}
		return response()->json($arrres);
	}
	
	public function updatemerchantminorder($id){
		$formData = Input::all();
		$value = $formData['value'];
		DB::table('merchant')->where('id',$id)->update(['min_order'=>$value]);
		$arrresult['result'] = $value;
		return response()->json($arrresult);
	}
	public function merchantminorder($id){
		$product_max = DB::table('product')->join('merchantproduct','product.id','=','merchantproduct.product_id')->where('merchantproduct.merchant_id',$id)->max('product.osmall_commission');
		if(is_null($product_max) || $product_max == 0){
			$product_max = DB::table('merchant')->where('id',$id)->pluck('osmall_commission');
			if(is_null($product_max) || $product_max == 0){
				$product_max = DB::table('global')->pluck('osmall_commission');
			}
		}
		$arrmax['max'] = $product_max;
		return response()->json($arrmax);
	}
		
	public function get_all($id, $stmt){
            $merchant = Merchant::where('user_id',$id)
                    ->first();
            $station = Station::where('user_id' , $id)
                ->first();

			if($stmt == "sov"){
				$query = POrder::where('user_id' , $id)
					->select('user_id','created_at')
					->orderBy('created_at','desc')
					->get();
                $actual_year = POrder::where('user_id' , $id)
                    ->select('created_at')
                    ->orderBy('created_at','desc')
                    ->first();
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
                    $id = $merchant->user_id;
                    $s = $merchant_address;
                    $name = $merchant->oshop_name;
                    $company = $merchant->company_name;
					$ireturn = 	$merchant;
            } else {
				if(isset ($station)){
					$station_address = Address::where('id' , $station->address_id)
						->first(array('line1','line2','line3','line4'));
						$mer = "Station ID";
						$id = $station->id;
						$s = $station_address;
						$name = $station->company_name;
						$company = $station->station_name;
						$ireturn = 	$station;
				}
			}
			return (array('ireturn'=>$ireturn, 'myreturn' => $query, 'today' => $today, 'mer' => $mer, 'id' => $id, 'name' => $name, 'company'=>$company, 's'=>$s, 'years'=>$years, 'actual_year'=>$actual_year));
	}	

	public function get_invoice_payment($invoices,$payment) {
        // dd($invoices);
        $products = array();
		
        foreach ($invoices as $order) {
            try {
				//dd($order);
                $odata = DB::table('ordertproduct')->where('porder_id', $order->porder_id)->get();
				//
               // $pdata = DB::table('product')->where('id', $odata->product_id)->first();//product detail
				$total = 0;
				foreach ($odata as $opd) {
					$amount = $opd->quantity * ($opd->order_price);
					$total += $amount;
				}
				$inv = DB::table('invoice')->where('porder_id', $order->porder_id)->first();
				$pdata = DB::table('invoicepayment')->where('invoice_id', $inv->id)->get();
				$paid = 0;
				
				foreach ($pdata as $ppd) {
					$amount = $ppd->amount;
					$paid += $amount;
				}
				$porder = DB::table('porder')->where('id', $order->porder_id)->first();
				
                $temp = array();
				$temp['total'] = $total;
				//dd($total);
				$temp['paid'] = $paid;
				$temp['mode'] = $order->mode;
                $temp['oid'] = $order->porder_id; //Order ID
                $temp['invoice_no'] = $inv->invoice_no; //Order ID
                $temp['merchant_id'] = $order->merchant_id; //Order ID
				$merchant = DB::table('merchant')->where('id',$order->merchant_id)->first();
				$temp['merchant_name'] = $merchant->company_name; //Order ID
                $temp['invoice_status'] = $inv->status; //Order ID
                $temp['invoice_payment'] = $inv->payment; //Order ID
                $temp['o_rcv'] = $order->delivery_tstamp;
                $temp['o_exec'] = $order->created_at;
                $temp['uid'] = $porder->user_id;
				$user = DB::table('users')->where('id',$porder->user_id)->first();
				if(!is_null($user)){
					$temp['name']=$user->first_name . " " . $user->last_name;
				} else {
					$temp['name']="";
				}
				
                $temp['comm']=0;
                $temp['desc']=$order->description;
				//dd("HOLA");
                array_push($products, $temp);

            } catch (\Exception $e) {
				//dd($e);
                echo "<script> console.log('Exception:Product not found' ); </script>";
            }
        }
        return $products;
    }	
	
	public function get_porder_payment($porders,$payment) {
        // dd($porders);
        $products = array();

        foreach ($porders as $order) {
            try {
				//dd($order);
				if($order->mode == "cash"){
					$odata = DB::table('orderproduct')->where('porder_id', $order->porder_id)->get();
				} else {
					$odata = DB::table('ordertproduct')->where('porder_id', $order->porder_id)->get();
				}
               // $pdata = DB::table('product')->where('id', $odata->product_id)->first();//product detail
				$total = 0;
				foreach ($odata as $opd) {
					$amount=($opd->quantity*$opd->order_price)+$opd->order_delivery_price;
					// $amount = $opd->quantity * ($opd->order_price + $opd->order_delivery_price);
					$total += $amount;
				}

                $temp = array();
				$temp['total'] = $total;
                $temp['oid'] = $order->porder_id; //Order ID
                $temp['mode'] = $order->mode; //Order ID
                $temp['o_rcv'] = $order->delivery_tstamp;
                $temp['o_exec'] = $order->created_at;
                $temp['uid'] = $order->user_id;
                $temp['source'] = $order->source;
                $temp['comm']=0;
                $temp['desc']=$order->description;

                if ($payment==true) {
                
					$pay = DB::table('payment')->
						where('id',$order->payment_id)->first();

                }
                $temp['payment']=$pay;
                array_push($products, $temp);

            } catch (\Exception $e) {
                echo "<script> console.log('Exception:Product not found' ); </script>";
            }
        }
        return $products;
    }	
	
	public function likes($uid = null){
		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}
		$selluser = User::find($user_id);	
		$merchant = DB::table('merchant')->where('user_id',$user_id)->first();
		$product_likes = [];
		//dd($merchant);
		if(!is_null($merchant)){
			$product_likes = DB::table('usersproduct')->join('product','product.id','=','usersproduct.product_id')->join('merchantproduct','product.id','=','merchantproduct.product_id')->where('merchantproduct.merchant_id', '=', $merchant->id)->select(DB::raw('product.*,COUNT(usersproduct.id) as wish'))->groupBy('product.id')->get();	
			//dd($product_likes);		
		}
		return view('seller.likes')
			->with('selluser',$selluser)
            ->with('product_likes',$product_likes);
	}
	
	public function border($uid = null){
		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}	
		$selluser = User::find($user_id);
		$b= new BuyerController();
		$porders = DB::table('porder')->
			where('user_id', $user_id)->
			orderBy('porder.created_at','DESC')->get();		
		$product_orders= $b->products($porders,true);
		$orders= array();

        foreach ($product_orders as $po) {
            $po['user_name']=Auth::user()->first_name." ".Auth::user()->last_name;
             $rcv_date = null;
             $due_date = null;
            $ex=DB::table('porder')->where('id',$po['oid'])->first();
             if(isset($ex)){
                 $rcv_date = $ex->created_at;
                 if($rcv_date != null and $rcv_date != ''){
                    $date = Carbon::parse($rcv_date);
                     $day = $date->format('d');
                     $month = $date->format('m');
                     $year = $date->format('Y');
                    $day_after_seven_days = $day + 7;

                    if ($day_after_seven_days <= 15){
                         $due_date = Carbon::parse($year.'-'.$month.'-15')->format('dMy h:m');
                     }else{
                         $due_date = Carbon::parse($year.'-'.$month.'-30')->format('dMy h:m');
                     }
                 }else{
                     $due_date= '';
                }
                 $po['due_date'] =   $due_date;
                 $po['rcv_date'] =   Carbon::parse($rcv_date)->format('dMy h:m');
            }
            
           $po['description']= $ex->description;
             $total= DB::table('payment')->where('id',$ex->payment_id)->pluck('receivable');
             $po['total']=$total;
 			$po['status']=$ex->status;
             // $po['']
            array_push($orders, $po);

         }

		return view('seller.buying-order')
			->with('selluser',$selluser)
            ->with('orders',$orders);			 
	}
	public function merchantdiscount($uid = null){
		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}
		$selluser = User::find($user_id);
		$merchant_id= DB::table('merchant')->
			where('user_id',$user_id)->
			pluck('id');
			$merchant_products=$merchant_products=DB::table('merchant as m')
								->select('p.id as id','p.name as name')
								->join('merchantproduct as oshop','oshop.merchant_id','=','m.id')
								->join('product as p','oshop.product_id','=','p.id')
								->where('m.id',$merchant_id)
								->where('p.status','active')
								->where('p.available','>',0)
								->where('p.oshop_selected','=',1)
								->get();
		return view('seller.create_discount_form')->with('merchant_id',$merchant_id)->with('selluser',$selluser)
            ->with('merchant_products',$merchant_products);						
	}
	public function breceipt($uid = null){
		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}
		$selluser = User::find($user_id);
			$query = Receipt::join('porder', 'porder.id', '=', 'receipt.porder_id')
			->where('user_id' , $user_id)
			->select('porder.user_id','porder.created_at')
			->orderBy('porder.created_at','desc')
			->get();
			
		//dd($query);

        $actual_year = Receipt::join('porder', 'porder.id', '=', 'receipt.porder_id')
            ->where('user_id' , $user_id)
            ->select('porder.user_id','porder.created_at')
            ->orderBy('porder.created_at','desc')
            ->first();


		$years = Array();$months = Array();$y = Array();$index = 0;
		foreach($query as $que){
			$years[$que->created_at->year][$index] = $que->created_at->month;
			$index++;
		}
	//	dd($years);
		$today = Carbon::today();
        $current_year = 0;
        if(isset($actual_year)){
            $actual_year = $actual_year->created_at;
            if($actual_year->year != $today->year){
                $current_year = 0;
            }else{
                $current_year = 1;
            }
        }
		
		return response()->view('seller.buying-receipt',        
		['myreturn' => $query,
        'today' => $today,
        'years' => $years,
        'id' => $user_id,
        'selluser' => $selluser,
        'current_year' => $current_year]);
	}
	public function merchantinventory($uid = null){
		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			if (Auth::user()->hasRole('adm')) {
				$user_id = $uid;
			} else {
				return view("common.generic")
				->with('message_type','error')
				->with('message','You do not have permission to view this page. Please contact OpenSupport #001');
			}
		}

		$selluser = User::find($user_id);
		$merchant_id= DB::table('merchant')->
			where('user_id',$user_id)->
			pluck('id');

		$merchant = Merchant::find($merchant_id);
		$merchant_pro = [];
		$merchant_prot = [];
		if(!is_null($merchant)){
        $merchant_pro = $merchant->products()->whereNull('product.deleted_at')
						->leftJoin('product as productb2b', function($join) {
							 $join->on('product.id', '=', 'productb2b.parent_id')
							 ->where('productb2b.segment','=','b2b');
						 })
						 ->leftJoin('product as producthyper', function($join) {
							 $join->on('product.id', '=', 'producthyper.parent_id')
							 ->where('producthyper.segment','=','hyper');
						 })
						 ->leftJoin('tproduct as tproduct', function($join) {
							 $join->on('product.id', '=', 'tproduct.parent_id');
						 })
						->select(DB::raw('product.id ,product.name ,product.photo_1,product.available,productb2b.available as availableb2b,SUM(producthyper.available) as availablehyper, tproduct.available as warehouse_available, product.stock'))
						->groupBy('product.id')
						->orderBy('product.created_at','DESC')->get();	
		$merchant_prot = DB::table('tproduct')->join('merchanttproduct','merchanttproduct.tproduct_id','=','tproduct.id')
							->join('twholesale','twholesale.tproduct_id','=','tproduct.id')	
							->leftJoin('product','product.id','=','tproduct.product_id')
							->leftJoin('product as parent','product.parent_id','=','parent.id')
							->where('tproduct.status', '=', 'active')
							->whereNull('product.id')
							->where('merchanttproduct.merchant_id',$merchant_id)
							->select('tproduct.*')
							->distinct()
							->get();
		}
	//	dd($merchant_prot);
		return view('merchant.inventory')->with('merchant_id',$merchant_id)
            ->with('selluser',$selluser)
            ->with('merchant_pro',$merchant_pro)
            ->with('merchant_prot',$merchant_prot);
	}
	
	public function purchase_order(){
		$purorders = DB::table('porder')->leftJoin('invoice','invoice.porder_id','=','porder.id')->join('ordertproduct','ordertproduct.porder_id','=','porder.id')
							->join('merchanttproduct','ordertproduct.tproduct_id','=','merchanttproduct.tproduct_id')
							->where('porder.user_id',Auth::user()->id)->whereNull('invoice.id')->select('porder.*')->distinct()->get();
	//	dd($purorders);
		$definite_purchase = array();
		$i = 0;
		$selluser = User::find(Auth::user()->id);
		$termfacility = DB::table('facility')->where('name','term')->pluck('id');
		$facilitysubs = DB::table('facility')->where('name','term')->pluck('token_subscription_fee');
		$facilityadmin = DB::table('facility')->where('name','term')->pluck('token_admin_fee');
		foreach($purorders as $purorder){
			$merchant = DB::table('porder')->join('ordertproduct','ordertproduct.porder_id','=','porder.id')
										->join('merchanttproduct','ordertproduct.tproduct_id','=','merchanttproduct.tproduct_id')
										->join('merchant','merchanttproduct.merchant_id','=','merchant.id')
										->leftJoin('userstoken', 'userstoken.user_id', '=', 'merchant.user_id')
										->leftJoin('sellerfacility', function($join2) use ($termfacility)
										  {
											  $join2->on('sellerfacility.user_id', '=', 'merchant.user_id')->where('sellerfacility.facility_id', '=',$termfacility);
										  })
										->where('porder.id',$purorder->id)->
										select('merchant.*','userstoken.qty as tokenqty','sellerfacility.id as sellerfacilityid')->first();
		//	dd($merchant);
			if(!is_null($merchant)){
				$station = DB::table('station')->where('user_id',Auth::user()->id)->first();
				if(!is_null($station )){
					$stationterm = DB::table('stationterm')->where('creditor_user_id',$merchant->user_id)->where('station_id',$station->id)->first();
					if(!is_null($station )){
						$total_owned = 0;
						$total_now = 0;
						$tproducts = DB::table('ordertproduct')->join('tproduct','tproduct.id','=','ordertproduct.tproduct_id')
						->where('porder_id',$purorder->id)->get();
						$current_pos = DB::table('porder')->where('mode','term')->join('invoice','invoice.porder_id','=','porder.id')
						->join('ordertproduct','ordertproduct.porder_id','=','porder.id')
						->join('merchanttproduct','ordertproduct.tproduct_id','=','merchanttproduct.tproduct_id')
						->where('invoice.status','!=','paid')
						->where('merchanttproduct.merchant_id',$merchant->id)
						->where('user_id',Auth::user()->id)->distinct()->select('porder.*','invoice.id as invoice_id')->get();
					//	dd($current_pos);
						foreach($current_pos as $current_po){
							$tproducts_pos = DB::table('ordertproduct')->join('tproduct','tproduct.id','=','ordertproduct.tproduct_id')
							->where('porder_id',$current_po->id)->get();
							foreach($tproducts_pos as $tproducts_po){
								$total_owned += ($tproducts_po->order_price/100)*$tproducts_po->quantity;
							}
							$payments_pos = DB::table('invoicepayment')->where('invoice_id',$current_po->invoice_id)->get();
							foreach($payments_pos as $payments_po){
								$total_owned -= $payments_po->amount/100;
							}
						}
						foreach($tproducts as $tproduct){
							$total_now += ($tproduct->order_price/100)*$tproduct->quantity;
						}
						$porder= DB::table('porder')->where('id',$purorder->id)->first();
						$cansave = true;
						$error_msg = "";
						if(is_null($merchant->tokenqty) || is_null($merchant->sellerfacilityid)){
							$cansave = false;
							$error_msg = "Seller not available for term at the moment";
						} else {
							if($facilityadmin >= $merchant->tokenqty){
								$cansave = false;
								$error_msg = "Seller not available for term at the moment";
							}
						}
						
						if((($stationterm->credit_limit/100) - $total_owned - $total_now) < 0){
							$cansave = false;
							$error_msg = "You don't have enough credit for this purchase";
						}
						
						$definite_purchase[$i]['porder'] = $porder;
						$definite_purchase[$i]['tproducts'] = $tproducts ;
						$definite_purchase[$i]['stationterm'] = $stationterm;
						$definite_purchase[$i]['cansave'] = $cansave;
						$definite_purchase[$i]['merchant'] = $merchant;
						$definite_purchase[$i]['total_owned'] = $total_owned;
						$definite_purchase[$i]['total_now'] = $total_now;
						$definite_purchase[$i]['error_msg'] = $error_msg;
						//dd($definite_purchase);
						$i++;
					}
				}
				
			}
		}
		//dd($definite_purchase);
		return view('purchase_order')->with('definite_purchase',$definite_purchase)->with('selluser',$selluser);
	}
	
	public function sdocuments($uid = null){
		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}
		$globals=Globals::first(); 
		$selluser = User::find($user_id);
			$query = Invoice::join('porder', 'porder.id', '=', 'invoice.porder_id')
			->join('ordertproduct', 'ordertproduct.porder_id', '=', 'invoice.porder_id')
			->join('merchanttproduct', 'ordertproduct.tproduct_id', '=', 'merchanttproduct.tproduct_id')
			->where('porder.user_id' , $user_id)
			->select('porder.user_id','invoice.created_at','merchant_id')
			->orderBy('invoice.created_at','desc')
			->get();
			
		//dd($query);

			$actual_year = Invoice::join('porder', 'porder.id', '=', 'invoice.porder_id')
			->join('ordertproduct', 'ordertproduct.porder_id', '=', 'invoice.porder_id')
			->join('merchanttproduct', 'ordertproduct.tproduct_id', '=', 'merchanttproduct.tproduct_id')
			->where('porder.user_id' , $user_id)
			->select('porder.user_id','invoice.created_at','merchant_id')
			->orderBy('invoice.created_at','desc')
			->first();


			$years = Array();$months = Array();$y = Array();$index = 0;
			foreach($query as $que){
				$years[$que->created_at->year][$index] = $que->created_at->month;
				$index++;
			}
		//	dd($years);
			$today = Carbon::today();
			$current_year = 0;
			if(isset($actual_year)){
				$actual_year = $actual_year->created_at;
				if($actual_year->year != $today->year){
					$current_year = 0;
				}else{
					$current_year = 1;
				}
			}

			return view('seller.station_documents')
            ->with('selluser',$selluser)
            ->with('myreturn',$query)
            ->with('today',$today)
            ->with('years',$years)
            ->with('id',$user_id)
            ->with('current_year',$current_year);			
	}
	
	public function location_company(Request $request){
		$id= $request->get('id');
		$company_name= $request->get('company');
		DB::table('fairlocation')->where('id',$id)->update(['company_name'=>$company_name,'updated_at'=>date('Y-m-d H:i:s')]);
		return "OK";
	}
	
	public function location_loc(Request $request){
		$id= $request->get('id');
		$location= $request->get('location');
		DB::table('fairlocation')->where('id',$id)->update(['location'=>$location,'updated_at'=>date('Y-m-d H:i:s')]);
		return "OK";
	}
	
	public function deletelocation(Request $request){
		$id= $request->get('id');
		DB::table('fairlocation')->where('id',$id)->delete();
		return "OK";
	}
	public function addlocation(Request $request){
		$user_id= $request->get('user_id');
		$location = DB::table('fairlocation')->insertGetId(['company_name'=>'Company Name', 'user_id'=>$user_id, 'address_id'=>0,'location'=>'Location','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
		return "OK";
	}
	
	public function savelocationaddress(Request $request){
		$input = $request->all();
		//dd($input);
		$city_id= $request->get('city_id');
		$area_id= $request->get('area_id');
		if($area_id == ""){
			$area_id = 0;
		}
		$line1= $request->get('line1');
		$line2= $request->get('line2');
		$line3= $request->get('line3');
		$line4= $request->get('line4');
		$postcode= $request->get('postcode');
		$address_id= $request->get('address_id');
		$location_id= $request->get('location_id');
		if($address_id == 0){
			$newaddress = DB::table('address')->insertGetId(['city_id'=>$city_id,'area_id'=>$area_id,'line1'=>$line1,'line2'=>$line2,'line3'=>$line3,'line4'=>$line4,'postcode'=>$postcode,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
			DB::table('fairlocation')->where('id',$location_id)->update(['address_id'=>$newaddress]);
		} else {
			DB::table('address')->where('id',$address_id)->update(['city_id'=>$city_id,'area_id'=>$area_id,'line1'=>$line1,'line2'=>$line2,'line3'=>$line3,'line4'=>$line4,'postcode'=>$postcode,'updated_at'=>date('Y-m-d H:i:s')]);
		}
	}
	
	public function locationaddress(Request $request){
		$id= $request->get('id');
		$address_id = $request->get('address_id');
		$states = DB::table('state')->where('country_code','MYS')->get();
		$cities = [];
		$areas = [];
		$address_state = 0;
		$address_city = 0;
		$address_area = 0;
		$line1 = "";
		$line2 = "";
		$line3 = "";
		$line4 = "";
		$postcode = "";
		if($address_id != 0){
			$raddress = DB::table('address')->where('id',$address_id)->first();
			if(!is_null($raddress)){
				$line1 = $raddress->line1;
				$line2 = $raddress->line2;
				$line3 = $raddress->line3;
				$line4 = $raddress->line4;
				$postcode = $raddress->postcode;
				$address_city = $raddress->city_id;
				$address_area = $raddress->area_id;
				$city_obj = DB::table('city')->where('id',$raddress->city_id)->first();
				if(!is_null($city_obj)){
					$state_obj = DB::table('state')->where('code',$city_obj->state_code)->first();
					$areas = DB::table('area')->where('city_id',$raddress->city_id)->get();
					if(!is_null($state_obj)){
						$address_state = $state_obj->id;
						$cities = DB::table('city')->where('state_code',$state_obj->code)->get();
					}
				}
			}
		}
		
		return json_encode(['states'=>$states,'cities'=>$cities,'areas'=>$areas,'location_id'=>$id,'address_id'=>$address_id,'address_state'=>$address_state,'address_city'=>$address_city,'address_area'=>$address_area,'line1'=>$line1,'line2'=>$line2,'line3'=>$line3,'line4'=>$line4,'postcode'=>$postcode]);
	}
	public function getlocation(Request $request)
    {
		$user_id= $request->get('user_id');
		$locations = DB::table('fairlocation')->where('user_id',$user_id)->get();
		return json_encode(['data'=>$locations]);
	}
	
	public function fairmode($uid = null){
		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}
		$query=array();
		$globals=Globals::first(); 
		$selluser = User::find($user_id);
		$locations = DB::table('fairlocation')->where('user_id',$user_id)
		->orderBy(DB::raw("
			  CASE fairlocation.company_name
				WHEN 'Warehouse' THEN 1
				ELSE 10
			  END"))->get();
		return view('seller.fairmode')->with('locations',$locations)
            ->with('selluser',$selluser);
	}
	public function tproducts($uid = null){

		if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}
		$query=array();
		$globals=Globals::first(); 
		$selluser = User::find($user_id);
		$merchant_id= DB::table('merchant')->
			where('user_id',$user_id)->
			pluck('id');
		$tproducts = DB::table('tproduct')->join('merchanttproduct','merchanttproduct.tproduct_id','=','tproduct.id')
							->leftJoin('product','product.id','=','tproduct.product_id')
							->where('merchanttproduct.merchant_id',$merchant_id)
							->where('product.status','active')
							->select('tproduct.*','product.id as original_id', 'product.warehouse_available as original_warehouse_available')
							->get();
		$notproducts = DB::table('product')->join('merchantproduct','merchantproduct.product_id','=','product.parent_id')
							->leftJoin('tproduct','product.id','=','tproduct.product_id')
							->where('product.segment','b2b')
						//	->where('product.term','=','0')
							->join('product as parent','parent.id','=','product.parent_id')
							->whereRaw("(tproduct.id IS NULL OR tproduct.status = 'deleted')")
							->where('merchantproduct.merchant_id',$merchant_id)
							->where('parent.status','active')
							->select('product.*', 'tproduct.id as tid','parent.photo_1 as real_photo_1')
							->distinct()
							->get();
		//dd($notproducts);
		$invoices = array();
		$invoices_total= DB::table('porder')
				->join('ordertproduct', 'porder.id', '=', 'ordertproduct.porder_id')
				->join('tproduct', 'ordertproduct.tproduct_id', '=', 'tproduct.id')
				->join('invoice', 'invoice.porder_id', '=', 'porder.id')
				->leftJoin('invoicepayment', 'invoice.id', '=', 'invoicepayment.invoice_id')
				->leftJoin('station', 'station.user_id', '=', 'porder.user_id')
				->join('merchanttproduct',
					'merchanttproduct.tproduct_id', '=', 'tproduct.id')
				->join('merchant',
					'merchanttproduct.merchant_id', '=', 'merchant.id')
				->where('merchant.id', $merchant_id)
				->where('porder.mode', 'term')
				->where('porder.status', '!=','cancelled')
				->select(DB::raw("station.company_name, station.user_id, station.id, porder.created_at"))
				->groupBy('station.id')
				->orderBy('porder.created_at','DESC')
				->get();
		//
		foreach($invoices_total as $totalinv){
			$total_buy = DB::table('porder')
				->join('ordertproduct', 'porder.id', '=', 'ordertproduct.porder_id')
				->join('tproduct', 'ordertproduct.tproduct_id', '=', 'tproduct.id')
				->join('invoice', 'invoice.porder_id', '=', 'porder.id')
				->join('merchanttproduct',
					'merchanttproduct.tproduct_id', '=', 'tproduct.id')
				->join('merchant',
					'merchanttproduct.merchant_id', '=', 'merchant.id')
				->where('merchant.id', $merchant_id)
				->where('porder.mode', 'term')
				->where('porder.user_id', $totalinv->user_id)
				->where('porder.status', '!=','cancelled')
				->select(DB::raw("SUM(ordertproduct.order_price * ordertproduct.quantity) as total_buy"))
				->orderBy('porder.created_at','DESC')
				->first();
			$totalb = 0;
			if(!is_null($total_buy->total_buy)){
				$totalb = $total_buy->total_buy;
			}
			//dd($totalb);
			$totalinv->totalb = $totalb;
			$total_pay = DB::table('porder')
				->join('ordertproduct', 'porder.id', '=', 'ordertproduct.porder_id')
				->join('tproduct', 'ordertproduct.tproduct_id', '=', 'tproduct.id')
				->join('invoice', 'invoice.porder_id', '=', 'porder.id')
				->leftJoin('invoicepayment', 'invoice.id', '=', 'invoicepayment.invoice_id')
				->join('merchanttproduct',
					'merchanttproduct.tproduct_id', '=', 'tproduct.id')
				->join('merchant',
					'merchanttproduct.merchant_id', '=', 'merchant.id')
				->where('merchant.id', $merchant_id)
				->where('porder.mode', 'term')
				->where('porder.user_id', $totalinv->user_id)
				->where('porder.status', '!=','cancelled')
				->select(DB::raw("invoicepayment.id,invoicepayment.amount as total_pay"))
				->distinct()
				->orderBy('porder.created_at','DESC')
				->get();
			$totalp = 0;
			foreach($total_pay as $total_pa){
				$totalp += $total_pa->total_pay;
			}
			//dd($totalp);
			$totalinv->totalp = $totalp;
		}
	//	dd($invoices_total);
		if(!is_null($merchant_id)){
			$invoices2= DB::table('porder')
				->join('ordertproduct', 'porder.id', '=', 'ordertproduct.porder_id')
				->join('tproduct', 'ordertproduct.tproduct_id', '=', 'tproduct.id')
				->join('invoice', 'invoice.porder_id', '=', 'porder.id')
				->join('merchanttproduct',
					'merchanttproduct.tproduct_id', '=', 'tproduct.id')
				->join('merchant',
					'merchanttproduct.merchant_id', '=', 'merchant.id')
				->where('merchant.id', $merchant_id)
				->where('porder.mode', 'term')
				->where('porder.status', '!=','cancelled')
				->orderBy('porder.created_at','DESC')
				->get();	
			
			$product_invoices2= $this->get_invoice_payment($invoices2,true);
			//dd($invoices2);
			$oid = 0;
			// return $product_orders2[0];
			foreach ($product_invoices2 as $po2) {
				$rcv_date = null;
				$due_date = null;
				$ex=DB::table('porder')->where('id',$po2['oid'])->first();

				if(isset($ex)){
					$rcv_date = $ex->created_at;
					if($rcv_date != null and $rcv_date != ''){
						$date = Carbon::parse($rcv_date);
						$day = $date->format('d');
						$month = $date->format('m');
						$year = $date->format('Y');
						$day_after_seven_days = $day + 7;

						if ($day_after_seven_days <= 15){
							$due_date = Carbon::parse($year.'-'.$month.'-15')->format('dMy h:m');
						}else{
							$due_date = Carbon::parse($year.'-'.$month.'-30')->format('dMy h:m');
						}
					}else{
						$due_date= '';
					}
					$po2['due_date'] =   $due_date;
					$po2['rcv_date'] =   Carbon::parse($rcv_date)->format('dMy h:m');
				}
				$po2['status']=$ex->status;
				$po2['o_exec']=$ex->created_at;

				if($oid != $po2['oid']){
					$oid = $po2['oid'];
					array_push($invoices, $po2);
				}
			}	

			$query = Invoice::join('porder', 'porder.id', '=', 'invoice.porder_id')
			->join('ordertproduct', 'ordertproduct.porder_id', '=', 'invoice.porder_id')
			->join('merchanttproduct', 'ordertproduct.tproduct_id', '=', 'merchanttproduct.tproduct_id')
			->where('merchant_id' , $merchant_id)
			->select('porder.user_id','invoice.created_at','merchant_id')
			->orderBy('invoice.created_at','desc')
			->get();
			
		//dd($query);

			$actual_year = Invoice::join('porder', 'porder.id', '=', 'invoice.porder_id')
			->join('ordertproduct', 'ordertproduct.porder_id', '=', 'invoice.porder_id')
			->join('merchanttproduct', 'ordertproduct.tproduct_id', '=', 'merchanttproduct.tproduct_id')
			->where('merchant_id' , $merchant_id)
			->select('porder.user_id','invoice.created_at','merchant_id')
			->orderBy('invoice.created_at','desc')
			->first();


			$years = Array();$months = Array();$y = Array();$index = 0;
			foreach($query as $que){
				$years[$que->created_at->year][$index] = $que->created_at->month;
				$index++;
			}
		//	dd($years);
			$today = Carbon::today();
			$current_year = 0;
			if(isset($actual_year)){
				$actual_year = $actual_year->created_at;
				if($actual_year->year != $today->year){
					$current_year = 0;
				}else{
					$current_year = 1;
				}
			}
		}		

		return view('seller.termproducts')->with('merchant_id',$merchant_id)
            ->with('selluser',$selluser)
            ->with('myreturn',$query)
            ->with('today',$today)
            ->with('years',$years)
            ->with('id',$merchant_id)
            ->with('current_year',$current_year)
            ->with('tproducts',$tproducts)
            ->with('invoices',$invoices)
            ->with('invoices_total',$invoices_total)
            ->with('notproducts',$notproducts);
	}
	
	public function balance_delpayment(Request $request) {
		$invp = DB::table('invoicepayment')->where('id',$request['id'])->first();
		DB::table('invoicepayment')->where('id',$request['id'])->delete();
		$invoice = DB::table('invoice')->where('id',$invp->invoice_id)->first();
		$tproducts_pos = DB::table('ordertproduct')->join('tproduct','tproduct.id','=','ordertproduct.tproduct_id')
		->where('porder_id',$invoice->porder_id)->get();
		$total_owned = 0;
		$total_payments = 0;
		foreach($tproducts_pos as $tproducts_po){
			$total_owned += ($tproducts_po->order_price/100)*$tproducts_po->quantity;
		}
		$payments_pos = DB::table('invoicepayment')->where('invoice_id',$invoice->id)->get();
		foreach($payments_pos as $payments_po){
			$total_owned -= $payments_po->amount/100;
			$total_payments++;
		}
		if($total_payments <= 0){
			DB::table('invoice')->where('id',$invoice->id)->update(['status'=>'active','payment'=>'unpaid']);
		} else{
			if($total_owned <= 0){
				DB::table('invoice')->where('id',$invoice->id)->update(['status'=>'completed','payment'=>'full']);
			} else {
				DB::table('invoice')->where('id',$invoice->id)->update(['status'=>'active','payment'=>'partial']);
			}
		}
		return "OK";
	}
	public function balance_payment(Request $request) {
		//dd("JOLA");
		$date_paid = $request['date_paid'];
		$date_paid = str_replace("/","-",$date_paid);
		$amount_paid = $request['amount_paid'] * 100;
		$method = $request['method'];
		$payment_bank = $request['payment_bank'];
		$payment_note = $request['payment_note'];
		$note = $request['note'];
		$porder_id = $request['porder_id'];
		$invoice = DB::table('invoice')->where('porder_id',$porder_id)->first();
		$tproducts_pos = DB::table('ordertproduct')->join('tproduct','tproduct.id','=','ordertproduct.tproduct_id')
		->where('porder_id',$porder_id)->get();
		$total_owned = 0;
		$count_pay = 0;
		foreach($tproducts_pos as $tproducts_po){
			$total_owned += ($tproducts_po->order_price/100)*$tproducts_po->quantity;
		}	
		$payments_pos = DB::table('invoicepayment')->where('invoice_id',$invoice->id)->get();
		foreach($payments_pos as $payments_po){
			$total_owned -= $payments_po->amount/100;
			if($payments_po->amount > 0){
				$count_pay++;
			}
		}
		$total_owned -= ($amount_paid/100);
		if($total_owned < 0){
			$invpay = "error";
		} else {	
			$invpay = DB::table('invoicepayment')->insertGetId(['invoice_id'=>$invoice->id,'amount'=>$amount_paid,'date_paid'=>$date_paid,
			'method'=>$method,'bank_id'=>$payment_bank,'note'=>$note . $payment_note]);


			if($total_owned <= 0){
				DB::table('invoice')->where('id',$invoice->id)->update(['status'=>'completed','payment'=>'full']);
			} else {
				$status = 'partial';
				if($count_pay == 0 && $amount_paid == 0){
					$status = 'unpaid';
				}
				DB::table('invoice')->where('id',$invoice->id)->update(['status'=>'active','payment'=>$status]);
			}
		}
		return $invpay;
	}
	
	public function twholesale($id, $merchant_id, $seller_id) {
		$selluser = User::find($seller_id);
		$twholesale = DB::table('twholesale')->where('tproduct_id',$id)->get();
		$tproduct = DB::table('tproduct')->where('id',$id)->first();
		return view('seller.twholesale')->with('merchant_id',$merchant_id)
			->with('twholesale',$twholesale)
			->with('selluser',$selluser)
            ->with('tproduct',$tproduct);
	}
	
    public function fetchFieldt(Request $request)
    {
		$id = $request['id'];
		$val = $request['pre'];
        $curr = DB::table('currency')->where('active', 1)->first()->code;
        return view('fetchFieldt')->with('id', $id)->with('val', $val)->with('curr', strtoupper($curr));
    }	
	
	public function routegetdealerst(Request $request)
    {
        $pid = $request['pid'];
        $userid = $request['userid'];
		
		$merchant_iidd = DB::table('merchant')->where('id',$userid)->first()->id;
		if($pid == 0){
			$dealers = DB::table('users')->select('users.id as id','users.first_name as first_name','users.last_name as last_name')->join('autolink','users.id','=','autolink.initiator')->where('autolink.responder',$merchant_iidd)->where('autolink.status','linked')->get();
		} else {
			$dealers = DB::table('users')->select('users.id as id','users.first_name as first_name','users.last_name as last_name')->join('autolink','users.id','=','autolink.initiator')->where('autolink.responder',$merchant_iidd)->where('autolink.status','linked')->whereNotExists(function($query) use ($pid)
            {
                $query->select(DB::raw(1))
                      ->from('tproductdealer')
                      ->whereRaw('tproductdealer.dealer_id = users.id AND tproductdealer.tproduct_id = ' . $pid);
            })->get();				
		}
		foreach($dealers as $dealer){
			$dealer->nbid = IdController::nB($dealer->id);
		}
		
		return json_encode($dealers);
    }	
	
	public function pdstprices($did, $pid, $merchant_id)
    {
		$current_product = DB::table('tproduct')->where('id',$pid)->first();
		$sprices = DB::table('tproductdealer')->where('dealer_id',$did)->where('tproduct_id',$pid)->get();
		return view('pdstprices')->with('current_product',$current_product)->with('sprices',$sprices)->with('merchant_id',$merchant_id)->with('did',$did)->with('pid',$pid);
    }		
	
    public function fetchFieldsForSpecialPricet(Request $request)
    {
        $id = $request['id'];
        $val = $request['val'];
        $lastid = $request['lastid'];
        $merchant_id = $request['merchant_id'];
        $curr = DB::table('currency')->where('active', 1)->first()->code;
		$merchant_iidd = DB::table('merchant')->where('id',$merchant_id)->first()->id;
		$dealers = DB::table('users')->select('users.id as id','users.first_name as first_name','users.last_name as last_name')->join('autolink','users.id','=','autolink.initiator')->where('autolink.responder',$merchant_iidd)->where('autolink.status','linked')->get();		
        return view('fetchFieldsForSpecialPricet')->with('id', $id)->with('lastid', $lastid)->with('val', $val)->with('dealers', $dealers)->with('curr', strtoupper($curr));
    }	
	
	public function tspecial($id, $merchant_id, $seller_id) {
		$dealers = DB::table('users')->select('users.id as id','users.first_name as first_name','users.last_name as last_name')->join('autolink','users.id','=','autolink.initiator')->where('autolink.responder',$merchant_id)->where('autolink.status','linked')->get();
		$tproduct = DB::table('tproduct')->where('id',$id)->first();
		$selluser = User::find($seller_id);
		$specialprod = DB::table('tproductdealer')->
						select('users.*','tproductdealer.dealer_id')->
						where('tproduct_id',$id)->
						join('users', 'users.id', '=', 'tproductdealer.dealer_id')->
						distinct()->get();
		return view('seller.tspecial')->with('merchant_id',$merchant_id)
			->with('tproduct',$tproduct)
			->with('specialprod',$specialprod)
			->with('selluser',$selluser)
            ->with('dealers',$dealers);
	}
	public function postAddtpr(Request $request) {
		$merchant_id= DB::table('merchant')->
			where('user_id',$request['merchant_id'])->
			pluck('id');
		$merchantuniqueq = DB::table('nsellerid')->where('user_id',$request['merchant_id'])->first();

		$prodarray = $request['pattr'];
		if(!is_null($merchant_id)){
			for($ui = 0; $ui < count($prodarray); $ui++){
				$b2bprod = DB::table('product')->where('id',$prodarray[$ui])->first();
				if(!is_null($b2bprod)){
					$tpexist = DB::table('tproduct')->where('product_id',$b2bprod->id)->first();
					if(!is_null($tpexist)){
						DB::table('tproduct')->where('product_id',$b2bprod->id)->update(['status'=>'active']);
					} else {
						$b2bdetail = DB::table('productdetail')->where('id',$b2bprod->productdetail_id)->first();
						$tpdid = 0;
						if(!is_null($b2bdetail)){
							$tpdid = DB::table('tproductdetail')->insertGetId(['data'=>$b2bdetail->data,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
						}
						$tp = DB::table('tproduct')->insertGetId(['name'=>$b2bprod->name,'parent_id'=>$b2bprod->parent_id,'product_id'=>$b2bprod->id
						,'segment'=>$b2bprod->segment,'photo_1'=>$b2bprod->photo_1,
						'description'=>$b2bprod->description,'free_delivery'=>$b2bprod->free_delivery
						,'free_delivery_with_purchase_qty'=>$b2bprod->free_delivery_with_purchase_qty
						,'del_worldwide'=>$b2bprod->del_worldwide,'del_west_malaysia'=>$b2bprod->del_west_malaysia
						,'del_sabah_labuan'=>$b2bprod->del_sabah_labuan,'del_sarawak'=>$b2bprod->del_sarawak,'cov_country_id'=>$b2bprod->cov_country_id
						,'cov_state_id'=>$b2bprod->cov_state_id,'cov_city_id'=>$b2bprod->cov_city_id
						,'cov_area_id'=>$b2bprod->cov_area_id,'del_pricing'=>$b2bprod->del_pricing,'del_width'=>$b2bprod->del_width
						,'del_lenght'=>$b2bprod->del_lenght,
						'del_height'=>$b2bprod->del_height,'del_weight'=>$b2bprod->del_weight,'weight'=>$b2bprod->weight,'height'=>$b2bprod->height,
						'width'=>$b2bprod->width,'length'=>$b2bprod->length,
						'del_option'=>$b2bprod->del_option,'available'=>$b2bprod->available
						,'warehouse_available'=>$b2bprod->warehouse_available,
						'tproductdetail_id'=>$tpdid
						,'osmall_commission'=>$b2bprod->osmall_commission,'b2b_osmall_commission'=>$b2bprod->b2b_osmall_commission
						,'status'=>'active'
						,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
						$mtp = DB::table('merchanttproduct')->insert(['tproduct_id'=>$tp,'merchant_id'=>$merchant_id,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
						if(!is_null($merchantuniqueq)){
							$newid = UtilityController::tproductuniqueid($merchant_id,$merchantuniqueq->nseller_id,'term',0, $tp);
							if($newid != ""){
								DB::table('ntproductid')->insert(['ntproduct_id'=>$newid, 'tproduct_id'=>$tp, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
							}
							
						}	
						$wholesale = DB::table('wholesale')->where('product_id',$prodarray[$ui])->get();
						foreach($wholesale as $whole){
							DB::table('twholesale')->insert(['tproduct_id'=>$tp,'funit'=>$whole->funit,'unit'=>$whole->unit,'price'=>$whole->price, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
						}
						$productdealer = DB::table('productdealer')->where('product_id',$prodarray[$ui])->get();
						foreach($productdealer as $productde){
							DB::table('tproductdealer')->insert(['tproduct_id'=>$tp,'special_funit'=>$productde->special_funit,'special_unit'=>$productde->special_unit,'special_price'=>$productde->special_price,'dealer_id'=>$productde->dealer_id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
						}
					}
				}
		}
		}
		return "OK";
		
	} 	
	
	public function merchanthyper($uid = null){
        if(is_null($uid)){
			$user_id = Auth::id();
		} else {
			$user_id = $uid;
		}
		$selluser = User::find($user_id);
		$merchant_id= DB::table('merchant')->
			where('user_id',$user_id)->
			pluck('id');
		
		//$products= Product::all();  
        // dd($currency);
        // Hyper
            // Preventing not isset error
            $hypers= array();
            $merchant_products=DB::table('merchantproduct')->join('product','product.parent_id','=','merchantproduct.product_id')->where('segment','hyper')
            	->where('merchantproduct.merchant_id',$merchant_id)
            	->select('product.*')
            	->get();;
            
            // Maybe inefficient . Needs to be re-written!
            // markeer zxcv
            $product_ids=array();
            $product_hids=array();

            try {
                foreach ($merchant_products as $mp) {
					array_push($product_hids,$mp->id);
				}
            
            } catch (\Exception $e) {
             return $e;   
            }
		//	dd($product_hids);
        // Hyper
        try {
			$hypers_ple = Product::join('owarehouse as o',
				'product.id','=','o.product_id')
            ->leftJoin('owarehousepledge as op', function($join) {
				 $join->on('o.id', '=', 'op.owarehouse_id')
				 ->where('op.status','=','executed');
			 })
            ->where('op.user_id',$user_id)
            ->where('product.oshop_selected','1')
            ->where('product.available','>','0')
			->where('o.status','=','active')
			->select(DB::raw('product.*, product.parent_id as product_id,
				o.id as owarehouse_id,o.collection_price,o.collection_units,
				o.created_at as odate,
				GROUP_CONCAT(op.pledged_qty) as pledged_qty'))
            ->groupBy('product.id')
            ->get();

		} catch (\Exception $e) {
			dump($e);
            // return $e;
            $hypers_ple=array();  //Fallback ! Just in case something happens
		}			

        // dd($currency);
		// $product_hids=[3401];
		try {
			$hypers = Product::join('owarehouse as o',
				'product.id','=','o.product_id')
                ->leftJoin('owarehousepledge as op', function($join) {
					 $join->on('o.id', '=', 'op.owarehouse_id')
						 ->where('op.status','=','executed');
				})
                ->whereIn('product.id',$product_hids)
				->where('o.status','=','active')
				->where('product.owarehouse_price','>',0)
				->select(DB::raw('product.*,o.id as owarehouse_id,
					o.collection_price,o.collection_units,
					product.parent_id as product_id,
					o.created_at as odate,
					GROUP_CONCAT(op.pledged_qty) as pledged_qty'))
                ->groupBy('product.id')
                ->get();
                // return $hyper;
   
            } catch (\Exception $e) {
            	// dump($e);
                $hypers=array(); 
                // Fallback
            }	
			//dd("");
			return view('seller.hyper')->with('merchant_id',$merchant_id)
            ->with('selluser',$selluser)
            ->with('ow_product',$hypers)
            ->with('ow_product_ple',$hypers_ple);
	}
	
    public function createIndex($object)
    {
        $final = array();
        foreach ($object->toArray() as $key => $value) {
            if (isset($value['occupation_name'])) {
                if (strtolower($value['occupation_name'][0]) == "a" ||
                    strtolower($value['occupation_name'][0]) == "b" ||
                    strtolower($value['occupation_name'][0]) == "c" ||
                    strtolower($value['occupation_name'][0]) == "d"
                ) {
                    $final['A-D'][] = $value;
                } else if (strtolower($value['occupation_name'][0]) == "e" || strtolower($value['occupation_name'][0]) == "f" || strtolower($value['occupation_name'][0]) == "g" || strtolower($value['occupation_name'][0]) == "h") {
                    $final['E-H'][] = $value;
                } else if (strtolower($value['occupation_name'][0]) == "i" || strtolower($value['occupation_name'][0]) == "j" || strtolower($value['occupation_name'][0]) == "k" || strtolower($value['occupation_name'][0]) == "l") {
                    $final['I-L'][] = $value;
                } else if (strtolower($value['occupation_name'][0]) == "m" || strtolower($value['occupation_name'][0]) == "n" || strtolower($value['occupation_name'][0]) == "o" || strtolower($value['occupation_name'][0]) == "p") {
                    $final['M-P'][] = $value;
                } else if (strtolower($value['occupation_name'][0]) == "q" || strtolower($value['occupation_name'][0]) == "r" || strtolower($value['occupation_name'][0]) == "s" || strtolower($value['occupation_name'][0]) == "t") {
                    $final['Q-T'][] = $value;
                } else if (strtolower($value['occupation_name'][0]) == "u" || strtolower($value['occupation_name'][0]) == "v" || strtolower($value['occupation_name'][0]) == "w" || strtolower($value['occupation_name'][0]) == "x") {
                    $final['U-X'][] = $value;
                } else if (strtolower($value['occupation_name'][0]) == "y" || strtolower($value['occupation_name'][0]) == "z") {
                    $final['Y-Z'][] = $value;
                }
            }
        }
        return $final;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Get all sellerHelps
     *
     * @method Ajax Get
     */
    public function getSellerHelp() {
        $sellerHelp = sellerHelp::all('id', 'name', 'phone', 'remarks', 'email','company_name','business_reg_no')->toArray();
        $sellerHelps = array();
        foreach ($sellerHelp as $key => $value) {
            if (empty($sellerHelps[$key])) {
                $sellerHelps[$key] = array();
            }
            $sellerHelps[$key]['text'] = $value['name'];
            $sellerHelps[$key]['name'] = $value['name'];
            $sellerHelps[$key]['company_name'] = $value['company_name'];
            $sellerHelps[$key]['business_reg_no'] = $value['business_reg_no'];
            $sellerHelps[$key]['name'] = $value['name'];
            $sellerHelps[$key]['phone'] = $value['phone'];
            $sellerHelps[$key]['remarks'] = $value['remarks'];
            $sellerHelps[$key]['email'] = $value['email'];
            $sellerHelps[$key]['data-sellerHelp-id'] = $value['id'];
        }
        return response()->json($sellerHelps);
    }

    public function getSellerHelpTable() {
        return view('admin/sellerHelpTree');
    }

    /**
     * Add new sellerHelp or subsellerHelp
     *
     * @method Ajax POST
     */
    public function postNewsellerHelp(Request $request) {

        $formData = Input::all();

        $now = \Carbon\Carbon::now()->toDateTimeString();
        if (!empty($formData)) {

            $sellerHelpData = array(
                'name' => $formData['name'],
                'company_name' => $formData['company_name'],
                'business_reg_no' => $formData['business_reg_no'],
                'phone' => $formData['phone'],
                'remarks' => $formData['remarks'],
                'email' => $formData['email'],
                'created_at' => $now,
                'updated_at' => $now,
            );

            sellerHelp::insert($sellerHelpData);
        }

        echo json_encode(array('success' => true));
    }

    /**
     * Edit new sellerHelp or subsellerHelp
     *
     * @method Ajax POST
     */
    public function postEditsellerHelp(Request $request) {

        $formData = Input::all();

        $sellerHelpData = array(
            'name' => $formData['name'],
            'company_name' => $formData['company_name'],
            'business_reg_no' => $formData['business_reg_no'],
            'phone' => $formData['phone'],
            'remarks' => $formData['remarks'],
            'email' => $formData['email'],
        );

        sellerHelp::where('id', '=', $formData['data-sellerHelp-id'])->update($sellerHelpData);

        echo json_encode(array('success' => true));
    }

    /**
     * Delete new sellerHelp or subsellerHelp
     *
     * @method Ajax POST
     */
    public function removeSellerHelp() {
        $formData = Input::all();
        sellerHelp::where('id', '=', $formData['data-sellerHelp-id'])->delete();

        echo "success";
        exit();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerHelpRequest $request) {
        $input = $request->except("_token");
        $messages = array(
            'occupation_id.required' => 'The professional field is required.',
            'occupation_id.numeric' => 'The professional field is must be a numeric.',
        );
        $validator = Validator::make($input, [
        'company' => 'required',
        'business_reg_no' => 'required|integer|min:0',
        'email' => 'required|email',
        'phone' => 'required',
        'address' => 'required',
        'occupation_id' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect('sellerHelp')
                            ->withErrors($validator)
                            ->withInput();
        }
        $status = $this->repo->create($input);
        return redirect('sellerHelp')->with('success', "Thank You We Will Contact You.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SellerHelpRequest $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
	
	public function send_campaign_facebook($campaign_id,$customer_user_id)
	{
		$campaign_url=url('campaign',$campaign_id);
		$data = [
					'link' => $campaign_url,
					'from' => "Opensupermall"
				];
		$o= new OWish;
		$result=$o->facebook($data,$customer_user_id);

		return $result;
	}	

}
