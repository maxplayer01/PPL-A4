<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Session;
use App\Adoption;
use App\Shelter;
use App\News;
use App\User;
use App\Traits\CaptchaTrait;

class HomeController extends Controller
{


use CaptchaTrait;
    //fungsi ini untuk menampilkan view index web root
    public function index(){
        if(Auth::check()){
            $user = Auth::user();
            Session::put('user','1');
            Session::put('name',$user->name);
        }
        $adoptions = Adoption::where('done','0')
            ->orderBy('created_at','desc')
            ->take(3)
            ->get();
        $news = DB::table('news')->take(3)->get();
        $markers = DB::table('maps')->get();
    	return view('home.index',['adoptions'=>$adoptions,'news'=>$news,'markers'=>$markers]);
    }
    //menampilkan adoption form
    public function adoption(){
		$adoptions = Adoption::where('done','0');
		$adoptions = $adoptions->paginate(6);
    	return view('home.adoption',['adoptions'=>$adoptions]);
    }
    //menampilkan shelter form
     public function shelter(){
		$shelter = Shelter::paginate(6);
    	return view('home.shelter',['shelters'=>$shelter]);
    }
    //menampilkan contact form
    public function contact(){
    	return view('home.contact');
    }
    //menampilkan about us
    public function about(){
    	return view('home.about');
    }
    //menampilkan faq
    public function faq(){
    	return view('home.faq');
    }
    //displaying news
    public function news(){
    	$allnews = DB::table('news')
                ->orderBy('created_at', 'desc')
                ->get();
    	return view('home.news',['allnews'=>$allnews]);
    }
	public function singleNews($id){
		$news = News::find($id);
        return view('home.singleNews',['news'=>$news]);
	}

    public function adoptionInfo($id){
		$adoption = Adoption::find($id);
        $user = Auth::user();
        $requests = DB::table('requests')
            ->where('idAdopsi',$adoption->id);
        $count = $requests->count();
        if($user)
            $request = $requests->where('idUser',$user->id)->get();
        else
            $request = "";
        $adoptionOwner = User::where('id',$adoption->user_id)->first();
        $id_dom = $adoption->domicile;
        $domicile = DB::table('domicile')->where('id',$id_dom)->first();
        $requests = DB::table('requests')
            ->join('users','requests.idUser','=','users.id')
            ->where('idAdopsi',$adoption->id)
            ->select('users.name','users.id');
        $requests = $requests->get();
        if($adoption->sex=='2'){
            $adoption->sex = 'female';
        }
        else{
            $adoption->sex = 'male';
        }
        if($adoption->age=='2')
            $adoption->age = '0-6 months';
        if($adoption->age=='3')
            $adoption->age = '6-12 months';
        if($adoption->age=='4')
            $adoption->age = '12-18 months';
        if($adoption->age=='5')
            $adoption->age = 'More than 2 years';
        if($adoption->age=='6')
            $adoption->age = 'More than 3 years';  
        return view('home.adoptionInfo',['adoption'=>$adoption,'user'=>$user,
            'request'=>$request,'requests'=>$requests,'adoptionOwner'=>$adoptionOwner,'count'=>$count,
            'domicile'=>$domicile]);
		
	}
    public function contactPost(Request $request){
        $name = $request->name;
        $email = $request->email;
        $title = $request->title;
        $content = $request->content;
        //validasi the input first
        $validator = Validator::make($request->all(),[
            'name' => 'min:3|max:20',
            'email' => 'email',
            'title' => 'required',
            'content' => 'required',
        ],[
            'email'=>'Email address is not in valid format',
            'name'=>'Name must be more than 2 characters',
            'title'=>'title must be filled',
            'content' => 'content must be filled'
        ]);
	
	    if($this->captchaCheck() == false)
        {
            return redirect()->back()
                ->withErrors(['Wrong Captcha'])
                ->withInput();
        }
        if($validator->fails()){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        else{
            //insert the information to the database
            DB::table('questions')->insert([
                'name'=>$name,
                'email'=>$email,
                'title'=>$title,
                'content'=>$content,
            ]);
            return redirect('/contact')->with('success','Your question is sent!');
        }  
    }
	public function searchAdoption(Request $request){
		$domicile = $request->input('domicile');
		//input can be 1,which is any type
		$type = $request->input('type');
		$breed = $request->input('breed');
		$age = $request->input('age');
		$sex = $request->input('sex');
        if($domicile!='0')
            $results = Adoption::where('domicile',$domicile);
        else
            $results = Adoption::where('domicile','>',$domicile);
        if($breed)
            $results = $results->where('breed','like','%'.$breed.'%');
		if($type!='1'){
			if($type=='2'){
				//isCat
				$type = '0';
				//0 for cat
			}
			else{
				$type = '1';
				//1 for dog
			}
			$results = $results
				->where('type',$type);
		}
		if($age!='1'){
			$results = $results->where('age',$age);
		}
		if($sex!='1'){
			$results = $results->where('sex',$sex);
		}
		$adoptions = $results->where('done','0')->paginate(6);
		return view('home.adoption',['adoptions'=>$adoptions]);
	}
	public function viewProfile($id){
        $user = User::find($id);
        $idDom = $user->domicile;
        $name = $user->name;
        $phone = $user->phone;
        $address = $user->address;
        $desc = $user->description;
        $domicile = DB::table('domicile')->select('location')->where('id',$idDom)->first();
        if( $idDom == 0 ){
            //belum set domisili
            $domicile = "None";
        }
        else
            $domicile = $domicile->location;
        $adoptions = Adoption::where('user_id','=',$id)->get();
        $shelters = Shelter::where('user_id',$id)->get();
        $check = 0;
        if(Auth::check())
        {
            $usr = Auth::user();
            $usrId = $usr->id;
            if($usrId == $id)
                $check = 1;
        }
        return view('home.viewProfile',['user'=>$user,'domicile'=>$domicile,'address'=>$address
            ,'description'=>$desc,'phone'=>$phone,'adoptions'=>$adoptions,'check'=>$check,
            'shelters'=>$shelters]);
    }
	public function searchShelter(Request $request){
		$domicile = $request->input('domicile');
		//input can be 1,which is any type
		$name = $request->input('name');
		$address = $request->input('address');
        if($domicile!='0')
		  $results = Shelter::where('domicile',$domicile);
        else
            $results = Shelter::where('domicile','>',$domicile);
        if($name)
            $results = $results->where('shelterName','like','%'.$name.'%');
		if($address)
		  $results = $results->where('address','like','%'.$address.'%');
		$shelter = $results->paginate(6);
		return view('home.shelter',['shelters'=>$shelter]);	
	}
    public function shelterInfo($id){
        $shelter = Shelter::find($id);
        $id_dom = $shelter->domicile;
        $domicile = DB::table('domicile')->where('id',$id_dom)->first();
        $shelterOwner = User::where('id',$shelter->user_id)->first();
        return view('home.shelterInfo',['shelter'=>$shelter,'shelterOwner'=>$shelterOwner,'domicile'=>$domicile]);
    }
}
