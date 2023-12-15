<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class VendorsController extends Controller
{
    //
    public function index(){
        $vendors=Vendor::selection()->paginate(5);
        return view('admin.vendors.index',compact('vendors'));
    }

    public function create(){
        $categories=MainCategory::where('translation_lang',get_default_lang())->active()->get();
        return view('admin.vendors.create',compact('categories'));
    }

    public function store(VendorRequest $request){
        try{

            if(!$request->has('active'))
                $request->request->add(['active',0]);
            else
                $request->request->add(['active',1]);
            $filePath="";
            if($request->has('logo')){
                $filePath= uploadeImage('vendors',$request->logo);
            }
            Vendor::create([
               'name'=>$request->name,
               'mobile'=>$request->mobile,
               'email'=>$request->email,
               'active'=>$request->active,
               'logo'=>$filePath,
               'category_id'=>$request->category_id,
               'address'=>$request->address,

            ]);
            return redirect()->route('admin.vendors')->with(['success'=>'تم الحفظ بنجاح']);
        }catch(\Exception $e){
            return($e);
            return redirect()->route('admin.vendors')->with(['error'=>'حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }
}
