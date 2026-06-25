<?php

namespace App\Http\Controllers;

use App\Models\Years;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstTahunController extends Controller
{
    public function index()
    {
        $tahun = Years::orderBy('id', 'desc')->get();
        return view('tahun.index', compact('tahun'));
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|digits:4|integer|min:2012|max:' . date('Y'),
        ], [
            'name.required' => 'Tahun wajib diisi.',
            'name.digits' => 'Tahun harus terdiri dari 4 angka.',
            'name.integer' => 'Tahun harus berupa angka.',
            'name.min' => 'Tahun tidak boleh kurang dari 2012.',
            'name.max' => 'Tahun tidak boleh lebih dari tahun sekarang.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();
        try {

            // Simpan data ke database
            $inputData = [
                'year' => $request->name,
            ];

            Years::create($inputData);

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Buat Tahun Baru '.$request->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Membuat Tahun Baru!'. $e->getMessage()]);
        }


    }
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|digits:4|integer|min:2012|max:' . date('Y'),
        ], [
            'name.required' => 'Tahun wajib diisi.',
            'name.digits' => 'Tahun harus terdiri dari 4 angka.',
            'name.integer' => 'Tahun harus berupa angka.',
            'name.min' => 'Tahun tidak boleh kurang dari 2012.',
            'name.max' => 'Tahun tidak boleh lebih dari tahun sekarang.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }


        $databefore = Years::find($id);
        $databefore->year = $request->name;


        if ($databefore->getDirty()) {
            DB::beginTransaction();
            try {

                $databefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Pembaruan Tahun Sukses']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Tahun!'. $e->getMessage()]);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }


    }
    public function delete($id)
    {
        $id = decrypt($id);

        // Cek Data berdasarkan ID
        $tahun = Years::find($id);
        if (!$tahun) {
            // Jika data tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Tahun!']);
        }

        DB::beginTransaction();
        try {

            // Hapus Data
            $tahun->delete();
            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Hapus Tahun: ' . $tahun->year]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Tahun! Kesalahan: ' . $e->getMessage()]);
        }
    }
}
