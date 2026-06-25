<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index()
    {
        $penulis = Author::get();

        return view('penulis.index', compact('penulis'));
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name may not be greater than 100 characters.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();

        try{

            Author::create([
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Ciptakan Penulis Baru']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Membuat Penulis Baru!']);
        }
    }
    public function update(Request $request, $id)
    {

        $id = decrypt($id);

        $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:100',
        ]);

        //jika validasi ada yang tidak sesuai
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $penulisbefore = Author::where('id', $id)->first();
        $penulisbefore->name = $request->name;

        if($penulisbefore->isDirty()){
            DB::beginTransaction();
            try{
                // Simpan perubahan ke database
                $penulisbefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Sukses Update Penulis']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Penulis!']);
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

            $name = Author::where('id', $id)->first();

            Author::where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menghapus Penulis ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Penulis ' . $name->name .'!']);
        }
    }
}
