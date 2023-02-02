<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\mainCategoryRequest;
use App\Models\mainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class MainCategoriesController extends Controller
{
    public function index()
    {
        $default_lang = get_default_lang();
        $categories = mainCategory::where('translation_lang', $default_lang)->selection()->get();
        return view('admin.maincategories.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.maincategories.create');
    }


    public function store(mainCategoryRequest $request)
    {
        try {
            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {

                return $value['abbr'] == get_default_lang();
            });

            $default_category = array_values($filter->all())[0];

            $filePath = '';

            if ($request->has('photo')) {
                $filePath = uploadImage('maincategorise', $request->photo);
            }

            DB::beginTransaction();

            $default_category_id = mainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);


            $categories = $main_categories->filter(function ($value, $key) {

                return $value['abbr'] != get_default_lang();
            });


            if (isset($categories) && $categories->count() > 0)
                $categories_array = [];
            foreach ($categories as $category) {


                $categories_array[] = [
                    'translation_lang' => $category['abbr'],
                    'translation_of' => $default_category_id,
                    'name' => $category['name'],
                    'slug' => $category['name'],
                    'photo' => $filePath
                ];
            }
            mainCategory::insert($categories_array);

            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الاضافة بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما']);
        }
    }


    public function edit($id)
    {
        try {
            $mainCategory = mainCategory::with('categories')->selection()->find($id);
            //valid category id 
            if (!$mainCategory) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);
            }
            return view('admin.maincategories.edit', compact('mainCategory'));
        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما']);
        }
    }


    public function update(mainCategoryRequest $request, $id)
    {
        try {
            //check
            $main_category = mainCategory::find($id);
            if (!$main_category) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);
            }
            //update
            $category = array_values($request->category)[0];

            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            mainCategory::where('id', $id)->update([
                'name' => $category['name'],
                'active' => $request->active,
            ]);

            //update photo
            if ($request->has('photo')) {
                $filePath = uploadImage('maincategorise', $request->photo);
                mainCategory::where('id', $id)->update([
                    'photo' => $filePath,
                ]);
            }
            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما']);
        }
    }


    public function destroy($id)
    {
        try {
            $category = mainCategory::find($id);
            if (!$category)
                return redirect()->route('admin.maincategories', $id)->with(['error' => 'هذه القسم غير موجوده']);


            $vendors = $category->vendors;
            if (isset($vendors) && $vendors->count() > 0)
                return redirect()->route('admin.maincategories', $id)->with(['error' => 'هذا القسم به متاجر قيد العمل ']);

            $image = Str::after($category->photo, 'assets/');
            $image = base_path('assets/' . $image);
            unlink($image);

            $category->categories()->delete();
            $category->delete();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم حذف القسم بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة اخري']);
        }
    }


    public function changeStatus($id)
    {
        try {
            $category = mainCategory::find($id);
            if (!$category)
                return redirect()->route('admin.maincategories', $id)->with(['error' => 'هذه القسم غير موجوده']);

            $status = $category->active ==  0 ? 1 : 0;

            $category->update(['active' => $status]);

            return redirect()->route('admin.maincategories')->with(['success' => 'تم تغير حالة القسم بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة اخري']);
        }
    }
}
