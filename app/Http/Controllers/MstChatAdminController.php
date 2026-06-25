<?php

namespace App\Http\Controllers;

use App\Models\PublicInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstChatAdminController extends Controller
{
    public function index()
    {
        $chatadmin = PublicInfo::where('code_sama', 'admin_wa')->get();

        return view('chat_admin.index', compact('chatadmin'));
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'info_name' => 'required|string',  // untuk nama buttonnya
            'info_value' => 'required|string', // ini untuk nomor wa
            'info_name_url' => 'required|string', // ini untuk pesannya
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $info_url = 'https://api.whatsapp.com/send?phone='.$request->info_value.'&text='.$request->info_name_url;

        DB::beginTransaction();
        try {
            // Simpan data ke database
            $inputData = [
                'code_sama' => 'admin_wa',
                'info_name' => $request->info_name,
                'info_value' => $request->info_value,
                'info_name_url' => $request->info_name_url,
                'info_url' => $info_url,
            ];

            // Simpan data ke dalam database
            PublicInfo::create($inputData);

            DB::commit();
            return redirect()->to(route('mstchatadmin.index'))->with(['success' => 'Sukses Buat Admin Baru '.$request->info_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Membuat Admin Baru!'. $e->getMessage()]);
        }


    }
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $validate = Validator::make($request->all(), [
            'info_name' => 'required|string',  // untuk nama buttonnya
            'info_value' => 'required|string', // ini untuk nomor wa
            'info_name_url' => 'required|string', // ini untuk pesannya
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $publicbefore = PublicInfo::find($id);
        $publicbefore->info_name = $request->info_name;
        $publicbefore->info_value = $request->info_value;
        $publicbefore->info_name_url = $request->info_name_url;
        $publicbefore->info_url = 'https://api.whatsapp.com/send?phone='.$request->info_value.'&text='.$request->info_name_url;


        // Cek jika ada perubahan pada model
        if ($publicbefore->getDirty()) {

            DB::beginTransaction();
            try {

                $publicbefore->save();


                DB::commit();
                return redirect()->to(route('mstchatadmin.index'))->with(['success' => 'Pembaruan Sukses: '.$request->info_name]);

            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui!'.$e]);
            }


        } else {
            return redirect()->to(route('mstchatadmin.index'))->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);

        // Cek produk berdasarkan ID
        $data = PublicInfo::find($id);
        if (!$data) {
            // Jika produk tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus!']);
        }

        DB::beginTransaction();
        try {
            // Hapus produk
            $data->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Hapus: ' . $data->info_name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Gagal Menghapus! Kesalahan: ' . $e->getMessage()]);
        }
    }


}
