<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {

        $categories = Categories::get();

        return view('categories.index', compact('categories'));
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama indikator wajib diisi.',
            'name.string'   => 'Nama indikator harus berupa teks.',
            'name.max'      => 'Nama indikator tidak boleh lebih dari 255 karakter.',
        ]);


        // Jika ada error validasi
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();
        try {
            // Simpan data simulasi ke database
            Categories::create([
                'name' => $request->name,
            ]);
            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Buat Kategori Baru']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Membuat Kategori Baru!']);
        }

    }
    public function update(Request $request, $id)
    {
        $idcategories = decrypt($id);


        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }
        $categoriesbefore = Categories::find($idcategories);
        $categoriesbefore->name = $request->name;

        if ($categoriesbefore->isDirty()) {
            DB::beginTransaction();
            try {

                $updateData = [
                    'name' => $request->name,
                ];

                Categories::where('id', $idcategories)->update($updateData);

                DB::commit();
                return redirect()->back()->with(['success' => 'Kategori Pembaruan Sukses']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Kategori!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function delete($id)
    {
        $idcategories = decrypt($id);

        DB::beginTransaction();
        $categories = Categories::where('id', $idcategories)->first();
        try{

            Categories::where('id', $idcategories)->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Hapus Kategori ' . $categories->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Kategori ' . $categories->name .'!']);
        }
    }
}
