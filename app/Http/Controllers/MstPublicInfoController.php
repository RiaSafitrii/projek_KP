<?php

namespace App\Http\Controllers;

use App\Models\DataDosen;
use App\Models\PublicInfo;
use App\Models\SosialMedia;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstPublicInfoController extends Controller
{

    // Pemerintahan
    public function strukturorganisasi()
    {
        $data = PublicInfo::where('info_code', 'struktur_organisasi')->first();

        return view('tentang.index', compact('data'));
    }

    public function tugasfungsi()
    {
        $data = PublicInfo::where('info_code', 'tugas_fungsi')->first();

        return view('tentang.index', compact('data'));
    }


    // Update Public
    public function update_info_public(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'info_name' => 'required|string',
            'info_value' => 'required|string',
        ], [
            'info_name.required' => 'Info Name wajib diisi dan harus berupa teks.',
            'info_name.string' => 'Info Name harus berupa teks.',
            'info_value.required' => 'Info Value wajib diisi dan harus berupa teks.',
            'info_value.string' => 'Info Value harus berupa teks.',
        ]);


        $rules = [
            'info_name' => 'required|string',
            'info_value' => 'required|string',
        ];

        if ($request->info_code === 'mars' || $request->info_code === 'hymne') {
            $rules['video_file'] = 'nullable|file|mimes:mp4,webm|max:51200'; // max 50MB
            $rules['audio_file'] = 'nullable|file|mimes:mp3,mpeg,wav|max:20480';
        }


        $validate = Validator::make($request->all(), $rules, [
            'info_name.required' => 'Info Name wajib diisi dan harus berupa teks.',
            'info_name.string' => 'Info Name harus berupa teks.',
            'info_value.required' => 'Info Value wajib diisi dan harus berupa teks.',
            'info_value.string' => 'Info Value harus berupa teks.',
            'video_file.mimes' => 'Format video harus mp4 atau webm.',
            'video_file.max' => 'Ukuran video maksimal 50MB.',
            'audio_file.mimes' => 'Format audio harus mp3, mpeg, atau wav.',
            'audio_file.max' => 'Ukuran audio maksimal 20MB.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                            ->withErrors($validate)
                            ->withInput();
        }



        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $publicbefore = PublicInfo::find($id);
        $publicbefore->info_name = $request->info_name;
        $publicbefore->info_value = $request->info_value;
        if($publicbefore->info_code === 'mars' || $publicbefore->info_code === 'hymne'){


            // Untuk Menyimpan Video
            if ($request->hasFile('video_file')) {
                // Hapus video lama
                if ($publicbefore->video && file_exists(public_path($publicbefore->video))) {
                    unlink(public_path($publicbefore->video));
                }

                $video = $request->file('video_file');
                $videoName = $video->hashName();
                $video->move(public_path('uploads/video'), $videoName);
                $publicbefore->video = 'uploads/video/' . $videoName;
            }

            // Untuk Menyimpan Audio
            if ($request->hasFile('audio_file')) {
                if ($publicbefore->audio && file_exists(public_path($publicbefore->audio))) {
                    unlink(public_path($publicbefore->audio));
                }

                $audio = $request->file('audio_file');
                $audioName = $audio->hashName();
                $audio->move(public_path('uploads/audio'), $audioName);
                $publicbefore->audio = 'uploads/audio/' . $audioName;
            }

        }

         // Cek jika ada perubahan pada model
         if ($publicbefore->getDirty()) {

            DB::beginTransaction();
            try {

                $publicbefore->save();


                DB::commit();
                return redirect()->back()->with(['success' => 'Pembaruan Sukses: '.$request->info_name]);

            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui!'.$e]);
            }


        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }

    }



    // Sosial Media
    public function sosialMediaIndex()
    {
      $sosialmedia = SosialMedia::get();

      return view('weboption.sosialmedia', compact('sosialmedia'));
    }
    public function updatesosialMediaIndex(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'icon_code' => 'required|string',
            'name' => 'required|string',
            'url' => 'required|url',
        ], [
            'icon_code.required' => 'Icon wajib diisi.',
            'icon_code.string' => 'Gunakan Icon Refrensi.',
            'name.required' => 'Nama Informasi wajib diisi.',
            'name.string' => 'Nama Informasi Harus text.',
            'url.required' => 'Deskripsi Wajib Diisi.',
            'url.url' => 'Gunakan URL yang Valid.',
        ]);

         // Jika validasi gagal
         if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $databefore = SosialMedia::find($id);
        $databefore->icon_code = $request->icon_code;
        $databefore->name = $request->name;
        $databefore->url = $request->url;

        if ($databefore->getDirty()) {
            DB::beginTransaction();
            try {

                $databefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Sukses Update Media Sosial: '. $request->name]);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Media Sosial!'. $e->getMessage()]);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }

    }


}
