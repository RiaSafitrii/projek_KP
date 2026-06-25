<?php

namespace App\Http\Controllers;

use App\Models\GroupNavigation;
use App\Models\Navigation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstNavigationController extends Controller
{
    public function index($id)
    {
        $id = decrypt($id);

        $group = GroupNavigation::find($id);

        $navigation = Navigation::where('group_navigation_id', $id)->get();

        return view('navigation.detail', compact('group', 'navigation'));
    }
    public function store(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'value' => 'required|string',
        ], [
            'name.required' => 'Nama Wajib Di isi.',
            'name.string' => 'Nama Wajib String.',
            'name.max' => 'Batas Karakter Nama 150.',
            'value.required' => 'Value Wajib Di isi.',
            'name.string' => 'Value Wajib String.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();

        try{

            Navigation::create([
                'group_navigation_id' => $id,
                'name' => $request->name,
                'value' => $request->value,
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Membuat Navigasi Baru']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Membuat  Navigasi Baru!']);
        }

    }
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'value' => 'required|string',
        ], [
            'name.required' => 'Nama Wajib Di isi.',
            'name.string' => 'Nama Wajib String.',
            'name.max' => 'Batas Karakter Nama 150.',
            'value.required' => 'Value Wajib Di isi.',
            'name.string' => 'Value Wajib String.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $before = Navigation::where('id', $id)->first();
        $before->name = $request->name;
        $before->value = $request->value;

        if($before->isDirty()){
            DB::beginTransaction();
            try{
                // Simpan perubahan ke database
                $before->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Sukses Update Navigasi']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Navigasi!']);
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

            $name = Navigation::where('id', $id)->first();

            $name->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menghapus Navigasi ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Navigasi ' . $name->name .'!']);
        }
    }
}
