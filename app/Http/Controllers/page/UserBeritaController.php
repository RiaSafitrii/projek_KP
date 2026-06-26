<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Comments;
use App\Models\Hashtags;
use App\Models\News;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserBeritaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getBerita($request); // Kirimkan request ke method getBerita
            return $data;
        }

        return view('page.news.index');
    }

    public function getBerita(Request $request)
    {
        $limit = 6;
        $offset = $request->offset ?? 0;

        $berita = News::orderBy('publish_date', 'desc')
            ->where('status', 'actived')
            ->where('publish_date', '<=', \Carbon\Carbon::now())
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($news) {
                $news->publish_date = \Carbon\Carbon::parse($news->publish_date)->translatedFormat('d F Y');
                $cleanContent = strip_tags(str_replace('&nbsp;', ' ', $news->content));
                $words = str_word_count($cleanContent, 1);
                $news->content = implode(' ', array_slice($words, 0, 30)) . '...';
                return $news;
            });

        return response()->json($berita);
    }

    public function show($slug)
    {
        $news_detail = News::select('news.*', 'categories.name as category', 'author.name as author')->where('slug', $slug)
        ->join('categories', 'categories.id', 'news.category_id')
        ->leftJoin('author', 'author.id', 'news.author_id')
        ->first();

        // Cek jika berita tidak ditemukan
        if (!$news_detail) {
            return redirect()->back()->with('fail', 'News not found!');
        }

        //ini untuk hapus session
        //session()->forget('viewed_news');


        $news_detail->publish_date = Carbon::parse($news_detail->publish_date)
        // ->setTimezone('Asia/Jakarta') // Pastikan timezone sesuai jika diperlukan
        ->translatedFormat('d F Y H:i') . ' WIB';

        // Cek di sesi apakah berita ini sudah dilihat
        $viewedNews = session()->get('viewed_news', []); // Ambil array berita yang sudah dilihat dari sesi

        if (!in_array($news_detail->id, $viewedNews)) {
            // Jika belum dilihat, tambahkan ke sesi dan tingkatkan views
            $news_detail->increment('views');
            session()->push('viewed_news', $news_detail->id); // Simpan ID berita ke dalam sesi
        }

        $databerita = News::select('news.id', 'news.slug', 'news.operator', 'news.title', 'news.thumbnail', 'news.publish_date', 'categories.name')
        ->leftJoin('categories', 'categories.id', 'news.category_id')
        ->where('categories.name', $news_detail->category)
        ->where('news.id', '!=', $news_detail->id)
        ->inRandomOrder()
        ->take(3)->get()
        ->map(function ($news) {
            // Format tanggal
            $news->publish_date = \Carbon\Carbon::parse($news->publish_date)
                ->translatedFormat('d F Y');

            // Batasi konten menjadi 150 kata
            $content = $news->content; // Misalnya kolom berita bernama 'content'

            // Menghilangkan tag HTML dari konten
            $cleanContent = strip_tags($content);

            // Mengganti semua &nbsp; dengan spasi biasa
            $cleanContent = str_replace('&nbsp;', ' ', $cleanContent);

            // Memecah konten yang sudah bersih menjadi array kata
            $words = str_word_count($cleanContent, 1);

            // Mengambil 150 kata pertama
            $shortenedContent = implode(' ', array_slice($words, 0, 150));

            // Menambahkan '...' di akhir jika diperlukan
            $news->content = $shortenedContent . '...';

            return $news;
        });

        $comment = Comments::where('news_id', $news_detail->id)
        ->where('is_approved', true)
        ->latest()
        ->get();

        // Meta
        $metaTitle = $news_detail->title;
        $metaDescription = Str::limit(strip_tags($news_detail->content), 150);// Ambil potongan dari konten untuk deskripsi
        $metaImage = $news_detail->thumbnail;


        return view('page.news.detail', compact(
            'metaTitle', 'metaDescription', 'metaImage',
            'news_detail', 'databerita', 'comment'
        ));
    }
    public function categories($categories)
    {
        $categories = str_replace('-', ' ', $categories);
        $databerita = News::select('news.*')
        ->join('categories', 'categories.id', 'news.category_id')
        ->whereRaw('LOWER(categories.name) = LOWER(?)', [$categories])
        ->get()->map(function ($news) {
            // Format tanggal
            $news->publish_date = \Carbon\Carbon::parse($news->publish_date)
                ->translatedFormat('d F Y');

            // Batasi konten menjadi 150 kata
            $content = $news->content; // Misalnya kolom berita bernama 'content'

            // Menghilangkan tag HTML dari konten
            $cleanContent = strip_tags($content);

            // Mengganti semua &nbsp; dengan spasi biasa
            $cleanContent = str_replace('&nbsp;', ' ', $cleanContent);

            // Memecah konten yang sudah bersih menjadi array kata
            $words = str_word_count($cleanContent, 1);

            // Mengambil 150 kata pertama
            $shortenedContent = implode(' ', array_slice($words, 0, 150));

            // Menambahkan '...' di akhir jika diperlukan
            $news->content = $shortenedContent . '...';

            return $news;
        });


        return view('page.berita.categori', compact('databerita', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $results = News::where('title', 'LIKE', '%' . $query . '%')
            ->orWhere('content', 'LIKE', '%' . $query . '%')->get();
        } else {
            $results = collect();
        }

        return view('page.berita.search', compact('results', 'query'));
    }


    public function store_comment(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'nama' => 'required|string|max:70',
            'komentar' => [
                'required',
                'string',
                'not_regex:/https?:\/\/|www\./i', // Tidak boleh mengandung pola URL
            ],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks valid.',
            'nama.max' => 'Nama tidak boleh lebih dari 70 karakter.',
            'komentar.required' => 'Pesan wajib diisi.',
            'komentar.string' => 'Harus berupa teks valid.',
            'komentar.not_regex' => 'Komentar tidak boleh mengandung link.',
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Periksa Inputan Anda']);
        }

        DB::beginTransaction();
        try {

            // Simpan data ke database
            $inputData = [
                'news_id' => $id,
                'name' => $request->nama,
                'comment' => $request->komentar,
            ];

            // Simpan data ke dalam database
            Comments::create($inputData);

            DB::commit();
            return redirect()->back()->with(['success' => 'Memasukan Komentar, Menunggu Persetujuan Admin']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Memasukan Komentar!'. $e->getMessage()]);
        }
    }
}
