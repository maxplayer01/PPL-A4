<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\News;
use DB;
use App\Shelter;
use App\Adoption;
use App\User;
use App\Question;
use Validator;
use Session;
use Storage;
use App\Traits\CaptchaTrait;

class AdminController extends Controller
{
    public function __construct(){
		$this->middleware('admin');
	}
	public function index(){
		//return the homepage of admin section
        $shelters = Shelter::all();
        $adoptions = Adoption::all();
        $userss = User::all();
        $users = [];
        $questions = Question::all();
        $allnews = DB::table('news')
                ->get();
        foreach($userss as $singleUser){
        	if($singleUser->admin!='1')
        		array_push($users,$singleUser);
        }
		return view('admin.index',
            ['shelters'=>$shelters,'adoptions'=>$adoptions,'users'=>$users,'questions'=>$questions,'allnews'=>$allnews]);
	}
	
	public function createNews(Request $request){
		//save the new submitted news to database
		$news = new News;
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $count = News::all();
        $count = $count->count();
        if($request->hasFile('newsimage'))
        {
            $file = $request->file('newsimage');
            $validator = Validator::make(array('file'=>$file),[
                'file' => 'image|max:2000',
            ]);
            if($validator->fails())
                return redirect('/admin')->withErrors($validator);
            $destinationPath = 'engine/images/news';
			$extension = $file->getClientOriginalExtension();
			$fileName = ($count+1).'.'.$extension;
            Storage::put('newsimage/'.$fileName,
                file_get_contents($file->getRealPath()));
            //$file->move($destinationPath,$fileName);
            $news->photo = $fileName;
        }
        $news->save();
        return redirect()->back();
	}

    public function deleteNews($id){
        //save the new submitted news to database
        DB::table('news')->where('id','=', $id)->delete();
        return $this->index();
    }

    public function viewUpdateNews($id){
        $news = News::find($id);
        return view('admin.updateNews',
            ['news'=>$news]);
    }

	public function updateNews(Request $request){
		//save the new submitted news to database
		$news = new News;
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $count = News::all();
        $count = $count->count();
        if($request->hasFile('newsimage'))
        {
            $file = $request->file('newsimage');
            $validator = Validator::make(array('file'=>$file),[
                'file' => 'image|max:2000',
            ]);
            if($validator->fails())
                return redirect('/admin')->withErrors($validator);
            $destinationPath = 'engine/images/news';
            $extension = $file->getClientOriginalExtension();
            $fileName = ($count+1).'.'.$extension;
            $file->move($destinationPath,$fileName);
            $news->photo = $fileName;
        }
        $news->save();
	}
}
