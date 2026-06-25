<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\PublicInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstHomeSettingController extends Controller
{
    public function update_info_public(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(),[
            'info_name' => 'required',
            'info_value' => 'required',
            'info_name_url' => 'required',
            'info_url' => 'required',
        ]);

        //jika validasi ada yang tidak sesuai
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $publicbefore = PublicInfo::find($id);
        $publicbefore->info_name = $request->info_name;
        $publicbefore->info_value = $request->info_value;
        $publicbefore->info_name_url = $request->info_name_url;
        $publicbefore->info_url = $request->info_url;


        // Cek jika ada perubahan pada model
        if ($publicbefore->getDirty()) {

            DB::beginTransaction();
            try {

                $publicbefore->save();


                DB::commit();
                return redirect()->back()->with(['success' => 'Pembaruan Sukses: '.$request->info_name]);

            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update!'.$e]);
            }


        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }

    }

    public function contactus()
    {
        $contactus = ContactUs::latest()->get();

        return view('homesetting.contactus', compact('contactus'));
    }
    public function contactusdelete($id)
    {
        $id = decrypt($id);

        // Cek data berdasarkan ID
        $contactus = ContactUs::find($id);
        if (!$contactus) {
            // Jika data tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus ContactUs!']);
        }

        DB::beginTransaction();
        try {
            // Hapus data
            $contactus->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Hapus Hubungi Kami: ' . $contactus->name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Gagal Menghapus ContactUs! Kesalahan: ' . $e->getMessage()]);
        }
    }
}
