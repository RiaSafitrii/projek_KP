<?php

namespace App\Http\Controllers;

use App\Models\BidangPegawai;
use App\Models\DataPegawai;
use App\Models\JabatanPegawai;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstDataPegawaiController extends Controller
{
    public function index()
    {
        $datapegawai = DataPegawai::select('data_pegawai.*', 'jabatan_pegawai.name as jabatan', 'bidang_pegawai.name as bidang')
        ->leftJoin('jabatan_pegawai', 'jabatan_pegawai.id', '=', 'data_pegawai.jabatan_id')
        ->leftJoin('bidang_pegawai', 'bidang_pegawai.id', '=', 'data_pegawai.bidang_id')
        ->get();

        $jabatan = JabatanPegawai::get();
        $bidang = BidangPegawai::get();

        return view('datapegawai.index', compact('datapegawai', 'jabatan', 'bidang'));
    }
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'jabatan_id' => 'required|exists:jabatan_pegawai,id',
            'bidang_id' => 'nullable|exists:bidang_pegawai,id',
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            // Pesan untuk jabatan_id
            'jabatan_id.required' => 'Jabatan wajib dipilih.',
            'jabatan_id.exists' => 'Jabatan yang dipilih tidak valid.',

            // Pesan untuk bidang_id
            'bidang_id.exists' => 'Bidang yang dipilih tidak valid.',

            // Pesan untuk name
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            // Pesan untuk foto
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);



        if ($validate->fails()) {
            return redirect()->back()
                             ->withErrors($validate)
                             ->withInput()
                             ->with(['fail' => 'Failed, Check Your Input']);
        }


        DB::beginTransaction();
        try {

            if($request->hasFile('foto')){
                $path_loc_thumb = $request->file('foto');
                $name = $path_loc_thumb ->hashName();
                $path_loc_thumb->move(public_path('uploads/profile'), $name);
                $url_thumb = 'uploads/profile/'.$name;
            }else{
                $url_thumb = null;
            }
            // Simpan data simulasi ke database
            DataPegawai::create([
                'jabatan_id' => $request->jabatan_id,
                'bidang_id' => $request->bidang_id,
                'name' => $request->name,
                'foto' => $url_thumb,
            ]);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create Data Pegawai: '.$request->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create Data Pegawai!']);
        }

    }
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'jabatan_id' => 'required|exists:jabatan_pegawai,id',
            'bidang_id' => 'nullable|exists:bidang_pegawai,id',
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            // Pesan untuk jabatan_id
            'jabatan_id.required' => 'Jabatan wajib dipilih.',
            'jabatan_id.exists' => 'Jabatan yang dipilih tidak valid.',

            // Pesan untuk bidang_id
            'bidang_id.exists' => 'Bidang yang dipilih tidak valid.',

            // Pesan untuk name
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            // Pesan untuk foto
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Failed, Check Your Input']);
        }

        $before = DataPegawai::find($id);
        $before->name = $request->name;
        $before->jabatan_id = $request->jabatan_id;
        $before->bidang_id = $request->bidang_id;

        if ($request->hasFile('foto')) {
            // Hapus file foto lama jika ada
            if ($before->foto) {
                $old_thumb = public_path($before->foto);
                if (file_exists($old_thumb)) {
                    unlink($old_thumb);
                }
            }

            // Simpan file foto yang baru
            $path_loc_thumbnail = $request->file('foto');
            $namethumbnail = $path_loc_thumbnail->hashName();
            $path_loc_thumbnail->move(public_path('uploads/profile'), $namethumbnail);
            $before->foto = 'uploads/profile/' . $namethumbnail;
        }

        if ($before->getDirty()) {

            DB::beginTransaction();
            try {

                $before->save();


                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Data Pegawai: '.$request->name]);

            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Data Pegawai!'.$e]);
            }



        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
    public function delete($id)
    {
        $id = decrypt($id);

        // Cek produk berdasarkan ID
        $before = DataPegawai::find($id);
        if (!$before) {
            // Jika data tidak ditemukan
            return redirect()->back()->with(['fail' => 'Failed to Delete Pegawai!']);
        }

        DB::beginTransaction();
        try {
            // Hapus file foto jika ada
            if ($before->foto) {
                $old_thum = public_path($before->foto);
                if (file_exists($old_thum)) {
                    unlink($old_thum); // Menghapus file foto
                }
            }

            // Hapus data
            $before->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data Pegawai: ' . $before->name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Failed to Delete Data Pegawai! Error: ' . $e->getMessage()]);
        }
    }

}
