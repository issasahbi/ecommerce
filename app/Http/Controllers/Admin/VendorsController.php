<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Support\Facades\Notification;
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
           $vendor= Vendor::create([
               'name'=>$request->name,
               'mobile'=>$request->mobile,
               'email'=>$request->email,
               'active'=>$request->active,
               'logo'=>$filePath,
               'password'=>$request->password,
               'category_id'=>$request->category_id,
               'address'=>$request->address,

            ]);
            Notification::send($vendor, new VendorCreated($vendor));
            return redirect()->route('admin.vendors')->with(['success'=>'تم الحفظ بنجاح']);
        }catch(\Exception $e){
           // return($e);
            return redirect()->route('admin.vendors')->with(['error'=>'حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    public function edit($id){
        try {
            $categories=MainCategory::where('translation_lang',get_default_lang())->active()->get();
            $vendor=Vendor::selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error'=>'حدث خطأ ما الرجاء المحاولة لاحقا']);
            //return $vendor;
            return view('admin.vendors.edit',compact('vendor','categories'));
        }catch(\exception $e){
            return $e;
            return redirect()->route('admin.vendors')->with(['error'=>'حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    public function update ($id, VendorRequest $request){
        try {
            $vendor=Vendor::selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error'=>'حدث خطأ ما الرجاء المحاولة لاحقا']);
            $data=$request->except('_token','id','logo','password');
            // --------- add logo -------------
            if($request->has('logo')){
                $filepath=uploadeImage('vendors',$request->logo);
                $data['logo']=$filepath;
            }
            // --------- add password -------------
            if($request->has('password')) {
                $data['password']=$request->password;
            }
            Vendor::where('id',$id)->update($data);
            return redirect()->route('admin.vendors')->with(['success'=>'تم التعديل بنجاح']);
        }catch(\exception $e){
            return $e;
            return redirect()->route('admin.vendors')->with(['error'=>'حدث خطأ ما الرجاء المحاولة لاحقا']);
        }

    }
}
