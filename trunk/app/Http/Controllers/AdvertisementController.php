<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Repository\AdvertisementRepo;
use App\Http\Requests\AdvertisementRequest;
use App\Models\Advertisement;
use App\Models\AdTarget;
use Illuminate\Http\Request;
use Validator;
use Input;
use DB;
use File;

class AdvertisementController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	protected $repo;
	function __construct(AdvertisementRepo $repo) {
		$this->repo = $repo;
	}
	
	public function save_landing_slider(Request $request)
    {
		$AdTarget=AdTarget::where('route','/')->first();
		$name = $request->get('slider_name');
		$url = $request->get('slider_url');
		$price = $request->get('slider_price');
		$slider = $request->get('slider_number');
		$adslot_id = 3;
		$slot = DB::table('adslot')->where('adtarget_id','=',$AdTarget->id)->where('placement','A1')->first();
		if(!is_null($slot)){
			$adslot_id = $slot->id;
		}
		$advertexists = DB::table('advertisement')->where('adslot_id',$adslot_id)->where('slider',$slider)->first();
		if(is_null($advertexists)){
			$advert = DB::table('advertisement')->insertGetId(['name'=> $name, 'slider'=>$slider,'url'=> $url, 'price'=>$price * 100, 'adslot_id'=>$adslot_id, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')]);
		} else {
			$advert = $advertexists->id;
			DB::table('advertisement')->where('id',$advert)->update(['name'=> $name, 'url'=> $url, 'price'=>$price * 100, 'updated_at'=> date('Y-m-d H:i:s')]);
		}
		$folder = base_path() . '/public/images/advertisement/' . $advert;
        File::makeDirectory($folder, 0777, true, true);
        $destination = $folder . '/';
        //chmod($folder,0775);
        $image = $request->file('slider_image');
		
        if (isset($image)) {		
            $image_name = $image->getClientOriginalName();
            if ($image->move($destination, $image_name)) {
				$adimageexists = DB::table('adimage')->where('advertisement_id',$advert)->first();
				
				if(is_null($adimageexists)){
					DB::table('adimage')->insert(['path'=>$image_name,'adcontrol_id'=>0,'advertisement_id'=>$advert, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')]);
				} else {
					DB::table('adimage')->where('advertisement_id',$advert)->update(['path'=>$image_name, 'updated_at'=> date('Y-m-d H:i:s')]);
				}
            }
        }
		$a1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','A1')
										->orderBy('advertisement.updated_at','DESC')->first();
		$d1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D1')->first();
		//dd($d1);
		$d2 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D2')->first();
		$d3 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D3')->first();
		$d4 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D4')->first();
		return view('advertisement.global.landing_edit')
			->with('a1',$a1)
			->with('d1',$d1)
			->with('d2',$d2)
			->with('d3',$d3)
			->with('d4',$d4);		
	}
	
	public function save_hyper(Request $request)
    {
		$AdTarget=AdTarget::where('route','/')->first();
		$name = $request->get('hyper_name');
		$url = $request->get('hyper_url');
		$price = $request->get('hyper_price');
		$segment = $request->get('hyper_segment');
		$adslot_id = 3;
		$slot = DB::table('adslot')->where('placement','D' . $segment)->first();
		if(!is_null($slot)){
			$adslot_id = $slot->id;
		}
		$advertexists = DB::table('advertisement')->where('adslot_id',$adslot_id)->first();
		if(is_null($advertexists)){
			$advert = DB::table('advertisement')->insertGetId(['name'=> $name, 'url'=> $url, 'price'=>$price * 100, 'adslot_id'=>$adslot_id, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')]);
		} else {
			$advert = $advertexists->id;
			DB::table('advertisement')->where('id',$advert)->update(['name'=> $name, 'url'=> $url, 'price'=>$price * 100, 'updated_at'=> date('Y-m-d H:i:s')]);
		}
		$folder = base_path() . '/public/images/advertisement/' . $advert;
        File::makeDirectory($folder, 0777, true, true);
        $destination = $folder . '/';
        //chmod($folder,0775);
        $image = $request->file('hyper_image');
		
        if (isset($image)) {		
            $image_name = $image->getClientOriginalName();
            if ($image->move($destination, $image_name)) {
				$adimageexists = DB::table('adimage')->where('advertisement_id',$advert)->first();
				
				if(is_null($adimageexists)){
					DB::table('adimage')->insert(['path'=>$image_name,'adcontrol_id'=>0,'advertisement_id'=>$advert, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')]);
				} else {
					DB::table('adimage')->where('advertisement_id',$advert)->update(['path'=>$image_name, 'updated_at'=> date('Y-m-d H:i:s')]);
				}
            }
        }
		$a1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','A1')
										->orderBy('advertisement.updated_at','DESC')->first();
		$d1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D1')->first();
		//dd($d1);
		$d2 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D2')->first();
		$d3 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D3')->first();
		$d4 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D4')->first();
		return view('advertisement.global.landing_edit')
			->with('a1',$a1)
			->with('d1',$d1)
			->with('d2',$d2)
			->with('d3',$d3)
			->with('d4',$d4);
	}

	public function landing_preview() {
		$AdTarget=AdTarget::where('route','/')->first();
		$a1slides = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','A1')->get();
		$a1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','A1')
										->first();
		$d1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D1')->first();
		//dd($d1);
		$d2 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D2')->first();
		$d3 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D3')->first();
		$d4 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D4')->first();
		return view('advertisement.global.landing_preview')
			->with('images',$a1slides)
			->with('a1',$a1)
			->with('d1',$d1)
			->with('d2',$d2)
			->with('d3',$d3)
			->with('d4',$d4);	
	}
	
	public function global_landing() {
		$AdTarget=AdTarget::where('route','/')->first();
		$a1slides = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','A1')->get();
		$a1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','A1')
										->orderBy('advertisement.updated_at','DESC')->first();
		$d1 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D1')->first();
		//dd($d1);
		$d2 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D2')->first();
		$d3 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D3')->first();
		$d4 = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
										->join('adimage','adimage.advertisement_id','=','advertisement.id')
										->join('adtarget','adslot.adtarget_id','=','adtarget.id')
										->select('advertisement.*','adimage.path')
										->where('adtarget.id','=',$AdTarget->id)
										->where('adslot.placement','=','D4')->first();
		return view('advertisement.global.landing')
			->with('a1slides',$a1slides)
			->with('a1',$a1)
			->with('d1',$d1)
			->with('d2',$d2)
			->with('d3',$d3)
			->with('d4',$d4);
	}
	public function global_index() {
		
		return view('advertisement.global');
	}
	
	public function master() {
		$advertisements = DB::table('advertisement')->join('adslot','adslot.id','=','adslot_id')
							->join('adimage','adimage.advertisement_id','=','advertisement.id')
							->join('adtarget','adslot.adtarget_id','=','adtarget.id')
							->select('advertisement.*','adimage.path','adslot.placement','adtarget.description as target')
							->get();
		return view('advertisement.master')->with('advertisements',$advertisements);
	}
	
	public function index() {
		return view('advertise');
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
     * Get all advertisements
     *
     * @method Ajax Get
     */
    public function getAdvertisement() {
        $advertisement = advertisement::all('id', 'name', 'phone','email')->toArray();
        $advertisements = array();
        foreach ($advertisement as $key => $value) {
            if (empty($advertisements[$key])) {
                $advertisements[$key] = array();
            }
            $advertisements[$key]['text'] = $value['name'];
            $advertisements[$key]['name'] = $value['name'];
            $advertisements[$key]['phone'] = $value['phone'];
            $advertisements[$key]['email'] = $value['email'];
            $advertisements[$key]['data-advertisement-id'] = $value['id'];
        }
        return response()->json($advertisements);
    }

    public function getAdvertisementTable() {
        return view('admin/advertisementTree');
    }

    /**
     * Add new advertisement or subadvertisement
     *
     * @method Ajax POST
     */
    public function postNewadvertisement(Request $request) {

        $formData = Input::all();

        $now = \Carbon\Carbon::now()->toDateTimeString();
        if (!empty($formData)) {

            $advertisementData = array(
                'name' => $formData['name'],
                'phone' => $formData['phone'],
                'email' => $formData['email'],
                'created_at' => $now,
                'updated_at' => $now,
            );

            advertisement::insert($advertisementData);
        }

        echo json_encode(array('success' => true));
    }

    /**
     * Edit new advertisement or subadvertisement
     *
     * @method Ajax POST
     */
    public function postEditadvertisement(Request $request) {

        $formData = Input::all();

        $advertisementData = array(
            'name' => $formData['name'],
            'phone' => $formData['phone'],
            'email' => $formData['email'],
        );

        advertisement::where('id', '=', $formData['data-advertisement-id'])->update($advertisementData);

        echo json_encode(array('success' => true));
    }

    /**
     * Delete new advertisement or subadvertisement
     *
     * @method Ajax POST
     */
    public function removeAdvertisement() {
        $formData = Input::all();
        advertisement::where('id', '=', $formData['data-advertisement-id'])->delete();

        echo "success";
        exit();
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(AdvertisementRequest $request) {
		$input = $request->except("_token");
		$validator = Validator::make($input, [
			'name' => 'required|min:3',
			'phone' => 'required|min:0',
			'email' => 'required|email',
		]);

		if ($validator->fails()) {
			return redirect('advertise')
				->withErrors($validator)
				->withInput();
		}
		$status = $this->repo->create($input);
		return redirect('advertise')->with('success', "Thank You We Will Contact You.");
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
	public function update(Request $request, $id) {
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
}
