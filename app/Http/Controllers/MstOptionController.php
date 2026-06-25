<?php

namespace App\Http\Controllers;

use App\Models\WebOption;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstOptionController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $weboption = WebOption::select('id', 'name', 'value', 'path_file')
                        ->get();
        }else{
            $weboption = WebOption::select('id', 'name', 'value', 'path_file')
                        ->whereNotIn('name', ['logo', 'logoWhite'])
                        ->get();
        }

        return view('weboption.index', compact('weboption'));
    }

    public function update(Request $request, $id)
    {
        $idopt = decrypt($id);



        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'nullable|string',
            'file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // Maksimal ukuran 2MB
        ]);

        // Jika ada error validasi
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $otpbefore = WebOption::find($idopt);

        $otpbefore->name = $request->name;
        $otpbefore->value = $request->value;

        if ($request->hasFile('file')) {
            // Hapus file thumbnail lama jika ada
            if ($otpbefore->path_file) {
                $old_thumb = public_path($otpbefore->path_file);
                if (file_exists($old_thumb)) {
                    unlink($old_thumb);
                }
            }

            // Simpan file thumbnail yang baru
            $path_loc_thumbnail = $request->file('file');
            $namethumbnail = $path_loc_thumbnail->hashName();
            $path_loc_thumbnail->move(public_path('assets/images'), $namethumbnail);
            $otpbefore->path_file = 'assets/images/' . $namethumbnail;
        }
         // Cek jika ada perubahan pada model
         if ($otpbefore->getDirty()) {

            DB::beginTransaction();
            try {

                $otpbefore->save();


                DB::commit();
                return redirect()->back()->with(['success' => 'Opsi Pembaruan Sukses: '.$request->name]);

            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Opsi!'.$e]);
            }



        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }



    }
}
