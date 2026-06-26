<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\DataPegawai;
use App\Models\Galery;
use App\Models\News;
use App\Models\Puskesmas;
use App\Models\Slider;
use App\Models\WebOption;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserHomeController extends Controller
{
    public function index()
    {

        $sliderutama = Slider::where('name', 'Slider Utama')->first();
        $slider = Slider::orderBy('id', 'desc')
            ->where('status', 'actived')
            ->where('name', '!=', 'Slider Utama')
            ->take(3)
            ->get();

        $pengumuman = News::select('news.id', 'news.slug', 'news.operator', 'news.title', 'news.thumbnail', 'news.publish_date')
            ->join('categories', 'categories.id', '=', 'news.category_id')
            ->where('categories.name', '=', 'Pengumuman')
            ->where('news.status', '=', 'actived')
            ->where('news.publish_date', '<=', Carbon::now()) // Sudah boleh tayang
            ->where('news.finaldate_of_announcement', '>=', Carbon::today()) // Belum berakhir
            ->orderBy('news.publish_date', 'desc')
            ->take(3)
            ->get();

        $berita = News::select('news.id', 'news.slug', 'news.operator', 'news.title', 'news.thumbnail', 'news.publish_date')
            ->join('categories', 'categories.id', '=', 'news.category_id')
            ->where('categories.name', '!=', 'Pengumuman')
            ->where('news.status', '=', 'actived')
            ->where('news.publish_date', '<=', Carbon::now())
            ->orderBy('news.publish_date', 'desc')
            ->first();

        if ($berita) {
            $berita->publish_date = Carbon::parse($berita->publish_date)
                ->translatedFormat('d F Y');
        }
        $databerita = News::join('categories', 'categories.id', '=', 'news.category_id')
            ->where('categories.name', '!=', 'Pengumuman')
            ->where('news.publish_date', '<=', Carbon::now()) // hanya yang sudah dipublikasikan
            ->where('news.status', '=', 'actived')
            ->orderBy('news.publish_date', 'desc')
            ->skip(1) // Lewati yang paling baru
            ->take(4)
            ->get()
            ->map(function ($news) {
                // Format tanggal
                $news->publish_date = Carbon::parse($news->publish_date)
                    ->translatedFormat('d F Y');

                // Bersihkan dan batasi konten jadi 150 kata
                $cleanContent = strip_tags($news->content);
                $cleanContent = str_replace('&nbsp;', ' ', $cleanContent);
                $words = str_word_count($cleanContent, 1);
                $shortenedContent = implode(' ', array_slice($words, 0, 150));
                $news->content = $shortenedContent . '...';

                return $news;
            });

        $datapegawai = DataPegawai::select(
                'data_pegawai.*',
                'jabatan_pegawai.name as jabatan',
                'bidang_pegawai.name as bidang'
            )
            ->leftJoin('jabatan_pegawai', 'jabatan_pegawai.id', '=', 'data_pegawai.jabatan_id')
            ->leftJoin('bidang_pegawai', 'bidang_pegawai.id', '=', 'data_pegawai.bidang_id')
            ->orderBy('data_pegawai.id', 'asc')
            ->limit(10)
            ->get();


        // Ambil 3 foto terbaru
        $fotosgalery = Galery::where('jenis', 'foto')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        // Ambil 2 video terbaru
        $videosgalery = Galery::where('jenis', 'video')
            ->orderBy('id', 'desc')
            ->take(2)
            ->get();


        // Meta
        $webName = WebOption::select('value')->where('name', 'webName')->first();
        $metaTitle = $webName ? $webName->value : config('app.name');
        $deskripsi = WebOption::select('value')->where('name', 'deskripsi')->first();
        $metaDescription = $deskripsi ? $deskripsi->value : 'Deskripsi singkat website Anda';
        $image = WebOption::select('path_file')->where('name', 'favicon')->first();
        $metaImage = asset($image->path_file);


        return view('page.beranda', compact(
            'metaTitle', 'metaDescription', 'metaImage',
            'sliderutama',
            'slider',
            'datapegawai',
            'pengumuman',
            'berita',
            'databerita',
            'fotosgalery',
            'videosgalery',
        ));
    }


    public function contact()
    {
        return view('page.contact');
    }
    public function store_contactus(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:35',
            'last_name' => 'required|string|max:35',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a valid string.',
            'first_name.max' => 'First name cannot be more than 35 characters.',

            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a valid string.',
            'last_name.max' => 'Last name cannot be more than 35 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot be more than 150 characters.',

            'subject.required' => 'Subject is required.',
            'subject.string' => 'Subject must be a valid string.',
            'subject.max' => 'Subject cannot be more than 150 characters.',

            'message.required' => 'Message is required.',
            'message.string' => 'Message must be a valid string.',
            'message.max' => 'Message cannot be more than 1000 characters.',
        ]);

        // Jika ada error validasi
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }


        DB::beginTransaction();
        try {
            // Simpan data simulasi ke database
            ContactUs::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Submit Your Message']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Submit Your Message!']);
        }




        dd($request->all());
    }

    public function pegawai()
    {
        $datapegawai = DataPegawai::select(
                'data_pegawai.*',
                'jabatan_pegawai.name as jabatan',
                'bidang_pegawai.name as bidang'
            )
            ->leftJoin('jabatan_pegawai', 'jabatan_pegawai.id', '=', 'data_pegawai.jabatan_id')
            ->leftJoin('bidang_pegawai', 'bidang_pegawai.id', '=', 'data_pegawai.bidang_id')
            ->orderBy('data_pegawai.id', 'asc')
            ->limit(10)
            ->get();

        // Meta
        $webName = WebOption::select('value')->where('name', 'webName')->first();
        $metaTitle = $webName ? $webName->value : config('app.name');
        $deskripsi = WebOption::select('value')->where('name', 'deskripsi')->first();
        $metaDescription = $deskripsi ? $deskripsi->value : 'Dinas Kesehatan Kota Bandar Lampung';
        $image = WebOption::select('path_file')->where('name', 'favicon')->first();
        $metaImage = asset($image->path_file);


        return view('page.profile.pegawai', compact(
            'metaTitle', 'metaDescription', 'metaImage',
            'datapegawai',
        ));
    }

    public function datapuskesmas()
    {
        $puskes = Puskesmas::orderBy('id', 'asc')
        ->get();

        // Meta
        $metaTitle = 'Puskesmas Kota Bandar Lampung';
        $metaDescription = 'Data Puskesmas Kota Bandar Lampung';
        $image = WebOption::select('path_file')->where('name', 'favicon')->first();
        $metaImage = asset($image->path_file);


        return view('page.puskesmas', compact(
            'metaTitle', 'metaDescription', 'metaImage',
            'puskes',
        ));
    }

    public function redirect()
    {
        // Tujuan manual
        $url = 'https://drive.google.com/drive/folders/1qiqQB6Jcyuyh5DfX_zIgO_8MY5bM3qpd?usp=sharing';

        // Redirect ke URL tujuan
        return redirect()->away($url);
    }
}
