<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\mainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('category')->selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }


    public function create()
    {
        $categories = mainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }


    public function store(VendorRequest $request)
    {
        try {

            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


            $filePath = '';
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }


            Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'password' => $request->password,
                'category_id' => $request->category_id,
                'address' => $request->address,
                'logo' => $filePath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            // Notification::send($vendor, new VendorCreated($vendor)); // to send a notify email for vendor after sign in

            return redirect()->route('admin.vendors')->with(['success' => 'تم الاضافة بنجاح']);


            // redirect message
        } catch (\Exception $ex) {
            return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما']);
        }
    }


    public function edit($id)
    {
        try {
            $vendor = Vendor::selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود']);
            $categories = mainCategory::where('translation_of', 0)->active()->get();
            return view('admin.vendors.edit', compact('vendor', 'categories'));
        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما']);
        }
    }


    public function update(VendorRequest $request, $id)
    {
        try {
            //check
            $vendor = vendor::find($id);
            if (!$vendor) {
                return redirect()->route('admin.Vendor')->with(['error' => 'هذا المتجر غير موجود']);
            }
            //update
            DB::beginTransaction();

            //update active status mode
            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            mainCategory::where('id', $id)->update([
                'active' => $request->active,
            ]);
            //update photo
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
                Vendor::where('id', $id)->update([
                    'photo' => $filePath,
                ]);
            }

            $data = $request->except('_token', 'id', 'logo', 'password');

            if ($request->has('password')) {
                $data['password'] = $request->password;
            }

            Vendor::where('id', $id)->update($data);


            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            return $ex;
            DB::rollBack();
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما']);
        }
    }


    public function destroy($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors', $id)->with(['error' => 'هذه المتجر غير موجوده']);

            $image = Str::after($vendor->logo, 'assets/');
            $image = base_path('assets/' . $image);
            unlink($image);

            $vendor->delete();

            return redirect()->route('admin.vendors')->with(['success' => 'تم حذف المتجر بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما']);
        }
    }


    public function changeStatus($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor)
                return redirect()->route('admin.Vendors', $id)->with(['error' => 'هذه المتجر غير موجوده']);

            $status = $vendor->active ==  0 ? 1 : 0;

            $vendor->update(['active' => $status]);

            return redirect()->route('admin.Vendors')->with(['success' => 'تم تغير حالة المتجر بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما']);
        }
    }
}
