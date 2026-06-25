<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstStorageController extends Controller
{
    public function index()
    {
        $filestorage = Storage::select('storage.*', 'users.name as writer')
        ->join('users', 'users.id', 'storage.created_by')
        ->orderBy('storage.id', 'desc')
        ->get();
        return view('storage.index', compact('filestorage'));
    }
    public function folder()
    {
        return view('storage.folder');

    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'data_file_input' => 'nullable|array', // Menangani multiple file uploads
            'data_file_input.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx,ppt,pptx,xls,xlsx|max:2048', // Validasi untuk gambar dan dokumen
        ]);

        // Jika ada error validasi
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }
        DB::beginTransaction();
        try {
            if ($request->hasFile('data_file_input')) {
                $files = $request->file('data_file_input'); // Mendapatkan array file

                foreach ($files as $file) {
                    $namaAsli = $file->getClientOriginalName(); // Mendapatkan nama asli file
                    $name = $file->hashName(); // Menggunakan hashName untuk nama file unik
                    $file->move(public_path('uploads/storage'), $name); // Menyimpan file ke folder tujuan
                    $url_path = 'uploads/storage/' . $name; // Path file yang disimpan

                    // Menyimpan data file ke dalam database satu per satu
                    $inputData = [
                        'name' => $namaAsli,
                        'jenis' => 'file',
                        'created_by' => auth()->id(),
                        'path_file' => $url_path, // Path file yang sudah disimpan
                    ];

                    // Simpan data ke dalam database untuk setiap file
                    Storage::create($inputData);
                }

                DB::commit();
                return redirect()->back()->with(['success' => 'Berhasil memasukkan File Baru']);
            } else {
                return redirect()->back()->with(['fail' => 'Tidak ada file yang diunggah']);
            }
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal memasukkan File Baru! ' . $e->getMessage()]);
        }

    }

    public function delete($id)
    {
        $id = decrypt($id);

        // Cek produk berdasarkan ID
        $storage = Storage::find($id);
        if (!$storage) {
            // Jika produk tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus File!']);
        }

        DB::beginTransaction();
        try {
            // Hapus file jika ada
            if ($storage->path_file) {
                $old_file = public_path($storage->path_file);
                if (file_exists($old_file)) {
                    unlink($old_file); // Menghapus file
                }
            }

            // Hapus file
            $storage->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menghapus File: ' . $storage->name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Gagal Menghapus file! Kesalahan: ' . $e->getMessage()]);
        }
    }
}
