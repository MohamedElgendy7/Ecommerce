<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::selection()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', compact('languages'));
    }


    public function create()
    {
        return view('admin.languages.create');
    }


    public function store(LanguageRequest $request)
    {
        //insert in DB
        try {
            Language::create($request->except(['_token']));

            return redirect()->route('admin.languages')->with(['success' => 'تم اضافة اللغة بنجاح']);
            //

        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة اخري']);
        }
    }


    public function edit($id)
    {
        $language = Language::selection()->find($id);
        if (!$language) {
            return redirect()->route('admin.languages')->with(['error' => 'هذه اللغة غير موجوده']);
        }
        return view('admin.languages.edit', compact('language'));
    }


    public function update($id, LanguageRequest $request)
    {
        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هذه اللغة غير موجوده']);
            }
            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            $language->update($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة اخري']);
        }
    }


    public function destroy($id)
    {
        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages.delete', $id)->with(['error' => 'هذه اللغة غير موجوده']);
            }
            $language->delete();
            return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة اخري']);
        }
    }
}
