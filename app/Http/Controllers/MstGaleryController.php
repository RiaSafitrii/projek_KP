<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstGaleryController extends Controller
{
    public function index()
    {
        $galery = Galery::orderBy('id', 'desc')->get();
        return view('galery.index', compact('galery'));
    }
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string',
            'jenis' => 'required|in:foto,video',  // Pastikan 'jenis' dipilih
        ];

        // Validasi berdasarkan jenis
        if ($request->jenis == 'foto') {
            $rules['foto'] = 'required|image|mimes:jpeg,jpg,png,webp|max:2048';  // Validasi foto jika jenisnya foto
        } elseif ($request->jenis == 'video') {
            $rules['video'] = 'required|url';  // Validasi video jika jenisnya video
        }

        $validate = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'jenis.in' => 'Jenis harus berupa foto atau video.',
            'foto.required' => 'Foto harus diunggah jika jenisnya adalah foto.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, jpg, png, atau webp.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            'video.required' => 'URL video harus diisi jika jenisnya adalah video.',
            'video.url' => 'URL video tidak valid.',
        ]);



        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Periksa Inputan!']);
        }

        DB::beginTransaction();
        try {

            if ($request->jenis == 'foto') {
                // Proses penyimpanan file Foto
                if($request->hasFile('foto')){
                    $path_loc_thumb = $request->file('foto');
                    $name = $path_loc_thumb ->hashName();
                    $path_loc_thumb->move(public_path('uploads/galery'), $name);
                    $url = 'uploads/galery/'.$name;
                }else{
                    $url = null;
                    return redirect()->back()->with(['fail' => 'Untuk Menyimpan Galery Foto!']);
                }

            } elseif ($request->jenis == 'video') {

                $url = $request->video;

            }

            // Simpan data ke database
            $inputData = [
                'name' => $request->name,
                'jenis' => $request->jenis,
                'url' => $url,
            ];

            // Simpan data ke dalam database
            Galery::create($inputData);

            DB::commit();
            return redirect()->back()->with(['success' => 'Menambahkan Galeri '.$request->name]);
        } catch (Exception $e) {
            DB::rollback();
            //kondisi untuk menghapus file yang kesimpan
            if (isset($url) && $url) {
                $filePath = public_path($url);
                if (file_exists($filePath)) {
                    unlink($filePath);  // Menghapus file
                }
            }

            return redirect()->back()->with(['fail' => 'Menambahkan Galeri!'. $e->getMessage()]);
        }


    }
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'jenis' => 'required',  // Pastikan 'jenis' dipilih
            'foto' => 'required_if:jenis,foto|image|mimes:jpeg,jpg,png,webp|max:2048',
            'video' => 'required_if:jenis,video|url', // Validasi url untuk video
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'foto.required_if' => 'Foto harus diunggah jika jenisnya adalah foto.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, jpg, png, atau webp.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            'video.required_if' => 'URL video harus diisi jika jenisnya adalah video.',
            'video.url' => 'URL video tidak valid.',
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Periksa Inputan!']);
        }


        $databefore = Galery::find($id);
        $databefore->name = $request->name;
        $databefore->jenis = $request->jenis;
        if ($request->jenis == 'foto') {
            // Proses penyimpanan file Foto
            if ($request->hasFile('foto')) {
                // Hapus file foto lama jika ada
                if ($databefore->foto) {
                    $old_thumb = public_path($databefore->foto);
                    if (file_exists($old_thumb)) {
                        unlink($old_thumb);
                    }
                }

                // Simpan file foto yang baru
                $path_loc_thumbnail = $request->file('foto');
                $namethumbnail = $path_loc_thumbnail->hashName();
                $path_loc_thumbnail->move(public_path('uploads/galery'), $namethumbnail);
                $databefore->url = 'uploads/galery/' . $namethumbnail;
            }

        } elseif ($request->jenis == 'video') {

            $databefore->url = $request->video;

        }


        if ($databefore->getDirty()) {
            DB::beginTransaction();
            try {

                $databefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Memperbarui Galeri: '.$request->name]);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Memperbarui Galeri!'. $e->getMessage()]);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }


    }
    public function delete($id)
    {
        $id = decrypt($id);

        // Cek Data berdasarkan ID
        $Informasi = Galery::find($id);
        if (!$Informasi) {
            // Jika data tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Data Galeri!']);
        }

        DB::beginTransaction();
        try {

            // Hapus Data
            $Informasi->delete();
            // Hapus file foto jika ada
            if ($Informasi->url && $Informasi->jenis == 'foto') {
                $old_thum = public_path($Informasi->url);
                if (file_exists($old_thum)) {
                    unlink($old_thum); // Menghapus file foto
                }
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Menghapus Galeri: ' . $Informasi->name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Menghapus Galeri! Error: ' . $e->getMessage()]);
        }
    }
}
