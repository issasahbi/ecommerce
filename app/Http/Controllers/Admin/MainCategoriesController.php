<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DB;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $default_lang= get_default_lang();
        $maincategories = MainCategory::where('translation_lang',$default_lang)->selection()->paginate(PAGINATION_COUNT);
        return view('admin.maincategories.index', compact('maincategories'));
    }




    public function create(){
        return view('admin.maincategories.create');
    }


###################### store categories ##########################

    public function store(MainCategoryRequest $request)
    {
        try {

            $main_categories = collect($request->category);
            // return $main_categories;
            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });
            $default_category = array_values($filter->all()) [0];
            if ($request->has('photo')) {
                $filepath = uploadeImage('maincategories', $request->photo);
            };
            DB::beginTransaction();
                $default_category_id = MainCategory::insertGetId([
                    'translation_lang' => $default_category['abbr'],
                    'translation_of' => 0,
                    'name' => $default_category['name'],
                    'slug' => $default_category['name'],
                    'photo' => $filepath,
                ]);
                $categories = $main_categories->filter(function ($value, $key) {
                    return $value['abbr'] != get_default_lang();
                });

                if (isset($categories) && $categories->count()) {
                    $categories_arr = [];
                    foreach ($categories as $category) {
                        $categories_arr[] = [
                            'translation_lang' => $category['abbr'],
                            'translation_of' => $default_category_id,
                            'name' => $category['name'],
                            'slug' => $category['name'],
                            'photo' => $filepath,
                        ];
                    }
                    MainCategory::insert($categories_arr);

                }
            DB::commit();
                return redirect()->route('admin.maincategories')->with(['success'=>'category added successfuly']);
        }catch(\Exception $ex){
            DB::rollback();
                return redirect()->route('admin.maincategories')->with(['error'=>'something went rongs']);
        }
    }

    ################### end store categories ###############################




    public function edit($id){
        $mainCategory=MainCategory::with('categories')->selection()->find($id);
        if(!$mainCategory){
            return redirect() ->route('admin.maincategories')->with(['error'=>'this language not esist']);
        }
        return view('admin.maincategories.edit',compact('mainCategory'));
    }


    ###################### store categories ################################

    public function update($id , MainCategoryRequest $request){
        $main_category=MainCategory::find($id);
        if(!$main_category)
            return redirect() ->route('admin.maincategories')->with(['error'=>'this language not esist']);

        $category= array_values($request->category) [0];
        if(!$request->has('category.0.active'))
            $request->request->add(['active'=>0]);
        else
            $request->request->add(['active'=>1]);


        MainCategory::where('id',$id)
            ->update([
               'name'=> $category['name'],
                'active'=>$request->active,

            ]);
        // save photo

        if($request->has('photo')) {
            $filePath = uploadeImage('maincategories', $request->photo);
            MainCategory::where('id',$id)
                ->update([
                    'photo'=>$filePath,
                ]);
        }
        return redirect()->route('admin.maincategories')->with(['success'=>'category updated successfuly']);
    }


    ################### end update categories ###############################




    public function destroy($id)
    {

        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            $vendors = $maincategory->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->route('admin.maincategories')->with(['error' => 'لأ يمكن حذف هذا القسم  ']);
            }

            $image = Str::after($maincategory->photo, 'assets/');
            $image = base_path('assets/' . $image);
            unlink($image); //delete from folder

            $maincategory->delete();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم حذف القسم بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }


}
