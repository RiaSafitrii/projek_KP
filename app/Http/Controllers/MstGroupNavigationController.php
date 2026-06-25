<?php

namespace App\Http\Controllers;

use App\Models\GroupNavigation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstGroupNavigationController extends Controller
{
    public function index()
    {
        $navigation = GroupNavigation::get();

        return view('navigation.group', compact('navigation'));
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
        ], [
            'name.required' => 'Nama Wajib Di isi.',
            'name.string' => 'Nama Wajib String.',
            'name.max' => 'Batas Karakter Nama 150.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();

        try{

            GroupNavigation::create([
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Ciptakan Group Navigasi Baru']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Membuat Group Navigasi Baru!']);
        }
    }
    public function update(Request $request, $id)
    {

        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name may not be greater than 150 characters.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $before = GroupNavigation::where('id', $id)->first();
        $before->name = $request->name;

        if($before->isDirty()){
            DB::beginTransaction();
            try{
                // Simpan perubahan ke database
                $before->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Sukses Update Group Navigasi']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Group Navigasi!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{

            $name = GroupNavigation::where('id', $id)->first();

            GroupNavigation::where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menghapus Group Navigasi ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Group Navigasi ' . $name->name .'!']);
        }
    }
}
