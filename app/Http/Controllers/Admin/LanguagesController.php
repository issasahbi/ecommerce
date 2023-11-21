<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LangaugeRequest;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', compact('languages'));
    }




    public function create(){
        return view('admin.languages.create');
    }



    public function store(LangaugeRequest $request){
        try {
            if(!$request->has('active'))
                $request->request->add(['active'=> 0]);
            Language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success'=>'languages added successfuly']);
        }catch(\Exception $ex){
            return redirect()->route('admin.languages')->with(['error'=>'some thing went rongs']);
        }

    }

    public function edit($id){
        $language=Language::selection()->find($id);
        if(!$language){
           return redirect() ->route('admin.languages')->with(['error'=>'this language not esist']);
        }
        return view('admin.languages.edit',compact('language'));
    }



    public function update($id , LangaugeRequest $request){
        try {
            $language=Language::find($id);
            if(!$language){
                return redirect() ->route('admin.languages.edit',$id)->with(['error'=>'this language not esist']);
            }
            if(!$request->has('active'))
                $request->request->add(['active'=> 0]);
            $language->update($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success'=>'languages updated successfuly']);
        }catch(\Exception $ex){
            return redirect()->route('admin.languages')->with(['error'=>'some thing went rongs']);
        }

    }



    public function destroy($id){
        try {
            $language=Language::find($id);
            if(!$language){
                return redirect() ->route('admin.languages.edit',$id)->with(['error'=>'this language not esist']);
            }
            $language->delete();
            return redirect()->route('admin.languages')->with(['success'=>'languages deleted successfuly']);
        }catch(\Exception $ex){
            return redirect()->route('admin.languages')->with(['error'=>'some thing went rongs']);
        }

    }
}
