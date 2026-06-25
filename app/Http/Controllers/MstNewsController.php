<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Comments;
use App\Models\Hashtags;
use App\Models\News;
use App\Models\Author;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MstNewsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getData();
            return $data;
        }
        return view('news.index');
    }
    public function getData()
    {
        $query = News::select(
            'news.id',
            'news.title',
            'news.publish_date',
            'news.operator_id',   // harus diambil supaya cek akses bisa
            'news.operator',
            'news.status',
            'categories.name as name_category',
            'author.name as penulis'
        )
        ->withCount('comments')
        ->orderBy('news.id', 'desc')
        ->leftJoin('categories', 'categories.id', 'news.category_id')
        ->leftJoin('author', 'author.id', 'news.author_id');

        return DataTables::of($query)
            ->addColumn('formatted_publish_date', function($data) {
                return \Carbon\Carbon::parse($data->publish_date)->translatedFormat('d F Y H:i');
            })
            ->addColumn('status', function($data) {
                return $data->status == 'actived'
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Tidak Aktif</span>';
            })
            ->addColumn('action', function($data){
                if(auth()->user()->id === $data->operator_id || auth()->user()->role === 'admin'){
                    $dropdown = '<div class="btn-group" role="group">
                        <button id="btnGroupDrop'.$data->id.'" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop'.$data->id.'">
                            <li><a class="dropdown-item drpdwn-wrg" href="'.route('mstnews.detail', encrypt($data->id)).'"><span class="mdi mdi-newspaper"></span> | Detail</a></li>
                            <li><a href="#"
                                    class="dropdown-item drpdwn-wrg btn-edit"
                                    data-id="'.$data->id.'"
                                    data-title="'.htmlspecialchars($data->title, ENT_QUOTES).'"
                                    data-route="'.route('mstnews.edit', encrypt($data->id)).'"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit"
                                ><span class="mdi mdi-file-edit"></span> | Edit</a></li>';

                    if($data->status == 'actived'){
                        $dropdown .= '<li><a href="#"
                            class="dropdown-item drpdwn-dgr btn-deactivate"
                            data-id="'.$data->id.'"
                            data-title="'.htmlspecialchars($data->title, ENT_QUOTES).'"
                            data-route="'.route('mstnews.deactivate', encrypt($data->id)).'"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-deactivate"
                        ><span class="mdi mdi-close-circle"></span> | Nonaktifkan</a></li>';
                    } else {
                        $dropdown .= '<li><a href="#"
                                class="dropdown-item drpdwn-scs btn-activate"
                                data-id="'.$data->id.'"
                                data-title="'.htmlspecialchars($data->title, ENT_QUOTES).'"
                                data-route="'.route('mstnews.activate', encrypt($data->id)).'"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-activate"
                            ><span class="mdi mdi-check-circle"></span> | Aktifkan</a></li>';

                    }

                    $dropdown .= '<li><a href="#"
                            class="dropdown-item drpdwn-dgr btn-delete"
                            data-id="'.$data->id.'"
                            data-title="'.htmlspecialchars($data->title, ENT_QUOTES).'"
                            data-route="'.route('mstnews.delete', encrypt($data->id)).'"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-delete"
                            ><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>

                            </ul>
                        </div>';

                    return $dropdown;
                } else {
                    return '<span class="badge bg-info">Ini bukan konten Anda</span>';
                }
            })
            ->rawColumns(['status','action'])

            ->make(true);

    }
    public function detail($id)
    {
        $idnews = decrypt($id);

        $news = News::select('news.*', 'categories.name as category_name', 'author.name as penulis')
        ->leftJoin('categories', 'categories.id', 'news.category_id')
        ->leftJoin('author', 'author.id', 'news.author_id')
        ->find($idnews);
        $news->publish_date = Carbon::parse($news->publish_date)
        // ->setTimezone('Asia/Jakarta') // Pastikan timezone sesuai jika diperlukan
        ->translatedFormat('d F Y, H:i') . ' WIB';

        $comment = Comments::where('news_id', $news->id)
        ->get();

        // Ambil daftar hashtag yang terkait dengan artikel ini
        return view('news.detail', compact('news', 'comment'));
    }
    public function add()
    {
        $categories = Categories::select('id', 'name')->get();
        $penulis = Author::get();
        $case = 'add';
        return view('news.form', compact('categories', 'case', 'penulis'));
    }
    public function store(Request $request)
    {

        // Ambil kategori dulu
        $category = Categories::find($request->id_category);

        // Atur aturan validasi dasar
        $rules = [
            'id_category' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:author,id',
            'title' => 'required|string|max:255',
            'publish_date' => 'required|date|date_format:Y-m-d\TH:i',
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'content' => 'required|string',
            'hashtag' => 'nullable|string',
        ];

        // Tambah aturan untuk finaldate_of_announcement jika kategori pengumuman
        if ($category && strtolower($category->name) === 'pengumuman') {
            $rules['finaldate_of_announcement'] = 'required|date|date_format:Y-m-d\TH:i';
        }

        // Custom pesan error untuk semua rules
        $messages = [
            'id_category.exists' => 'Kategori tidak valid.',
            'author_id.exists' => 'Author tidak valid.',
            'title.required' => 'Judul wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'publish_date.required' => 'Tanggal publish wajib diisi.',
            'publish_date.date' => 'Tanggal publish harus berupa tanggal yang valid.',
            'publish_date.date_format' => 'Format tanggal publish harus : Y-m-d\TH:i.',
            'thumbnail.required' => 'Thumbnail wajib diupload.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berekstensi jpeg, jpg, png, atau webp.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2MB.',
            'content.required' => 'Isi konten wajib diisi.',
            'content.string' => 'Isi konten harus berupa teks.',
            'hashtag.string' => 'Hashtag harus berupa teks.',
            'finaldate_of_announcement.required' => 'Tanggal akhir pengumuman wajib diisi.',
            'finaldate_of_announcement.date' => 'Tanggal akhir pengumuman harus berupa tanggal yang valid.',
            'finaldate_of_announcement.date_format' => 'Format tanggal akhir pengumuman harus : Y-m-d\TH:i.',
        ];

        // Jalankan validasi
        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }



        DB::beginTransaction();
        try {
            // Proses penyimpanan file tumbnail
            if($request->hasFile('thumbnail')){
                $path_loc_thumb = $request->file('thumbnail');
                $name = $path_loc_thumb ->hashName();
                $path_loc_thumb->move(public_path('uploads/news/thumbnail'), $name);
                $url_thumb = 'uploads/news/thumbnail/'.$name;
            }else{
                $url_thumb = null;
                return redirect()->back()->with(['fail' => 'Gagal Menyimpan File Thumbnail Berita!']);
            }


            // Simpan data news ke database
            $newsData = [
                'operator_id' => auth()->user()->id,
                'author_id' => $request->author_id,
                'category_id' => $request->id_category,
                'title' => $request->title,
                'operator' => auth()->user()->name,
                'publish_date' => $request->publish_date,
                'content' => $request->content,
                'thumbnail' => $url_thumb,
                'status' => 'actived',
            ];
            if ($category && strtolower($category->name) === 'pengumuman') {
                $newsData['finaldate_of_announcement'] = $request->finaldate_of_announcement;
            }
            // Simpan data ke dalam database
            $news = News::create($newsData);

            // Ekstrak hashtag dari konten artikel
            preg_match_all('/#(\w+)/', $request->hashtag, $matches);

            // Ambil atau buat hashtag
            foreach ($matches[1] as $hashtagText) {
                $hashtag = Hashtags::firstOrCreate(
                    ['hashtag' => $hashtagText],
                    ['operator_id' => auth()->user()->id]
                );
                $news->hashtags()->attach($hashtag->id);// ini untuk otomatis masuk ke tabel newsn_hashtag
            }
            DB::commit();
            return redirect()->to(route('mstnews.index'))->with(['success' => 'Sukses Buat Berita Baru']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Membuat Berita Baru!'. $e->getMessage()]);
        }

    }
    public function edit($id)
    {
        $idnews = decrypt($id);

        $categories = Categories::select('id', 'name')->get();
        $penulis = Author::get();
        $news = News::find($idnews);
        $news->publish_date = \Carbon\Carbon::parse($news->publish_date)->format('Y-m-d\TH:i');
        // Ambil daftar hashtag yang terkait dengan artikel ini
        $hashtags = $news->hashtags->pluck('hashtag')->map(function ($hashtag) {
            return "#" . $hashtag; // Menambahkan tanda # di setiap hashtag
        })->implode(' ');

        $case = 'edit';
        return view('news.form', compact('categories', 'penulis', 'case', 'news', 'hashtags'));
    }
    public function update(Request $request, $id)
    {
        $idnews = decrypt($id);

        $newsbefore = News::find($idnews);
        if(!$newsbefore){
            return redirect()->back()->withInput()->with(['fail' => 'Gagal, Anda Tidak Perlu Memperbarui Berita']);
        }

        $category = Categories::find($request->id_category);

        // Atur aturan validasi dasar
        $rules = [
            'id_category' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:author,id',
            'title' => 'required|string|max:255',
            'publish_date' => 'required|date|date_format:Y-m-d\TH:i',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'content' => 'required|string',
            'hashtag' => 'nullable|string',
        ];

        // Jika kategori pengumuman
        if ($category && strtolower($category->name) === 'pengumuman') {
            $rules['finaldate_of_announcement'] = 'required|date|date_format:Y-m-d\TH:i';
        }

        // Pesan error kustom
        $messages = [
            'id_category.exists' => 'Kategori yang dipilih tidak valid.',
            'author_id.exists' => 'Penulis tidak ditemukan.',
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'publish_date.required' => 'Tanggal publish wajib diisi.',
            'publish_date.date' => 'Format tanggal publish tidak valid.',
            'publish_date.date_format' => 'Format tanggal publish harus seperti : Y-m-dTH:i.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat jpeg, jpg, png, atau webp.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2 MB.',
            'content.required' => 'Isi berita wajib diisi.',
            'finaldate_of_announcement.required' => 'Tanggal akhir pengumuman wajib diisi.',
            'finaldate_of_announcement.date' => 'Format tanggal akhir pengumuman tidak valid.',
            'finaldate_of_announcement.date_format' => 'Format tanggal akhir pengumuman harus seperti : Y-m-dTH:i.',
        ];

        // Jalankan validasi dengan pesan error kustom
        $validate = Validator::make($request->all(), $rules, $messages);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }


        // Assign data dasar
        $newsbefore->operator_id = auth()->user()->id;
        $newsbefore->category_id = $request->id_category;
        $newsbefore->author_id = $request->author_id;
        $newsbefore->title = $request->title;
        $newsbefore->operator = auth()->user()->name;
        $newsbefore->publish_date = Carbon::parse($request->publish_date)->format('Y-m-d H:i:s');
        $newsbefore->content = $request->content;

        // Update field finaldate_of_announcement jika kategori pengumuman
        if ($category && strtolower($category->name) === 'pengumuman') {
            $newsbefore->finaldate_of_announcement = Carbon::parse($request->finaldate_of_announcement)->format('Y-m-d H:i:s');
        } else {
            // Kalau bukan pengumuman, bersihkan nilai finaldate_of_announcement (optional)
            $newsbefore->finaldate_of_announcement = null;
        }

        // Update thumbnail jika ada file baru
        if ($request->hasFile('thumbnail')) {
            // Hapus file thumbnail lama jika ada
            if ($newsbefore->thumbnail) {
                $old_thumb = public_path($newsbefore->thumbnail);
                if (file_exists($old_thumb)) {
                    unlink($old_thumb);
                }
            }

            // Simpan file thumbnail yang baru
            $path_loc_thumbnail = $request->file('thumbnail');
            $namethumbnail = $path_loc_thumbnail->hashName();
            $path_loc_thumbnail->move(public_path('uploads/news/thumbnail'), $namethumbnail);
            $newsbefore->thumbnail = 'uploads/news/thumbnail/' . $namethumbnail;
        }

        // Update tags (hashtag)
        preg_match_all('/#(\w+)/', $request->hashtag, $matches);
        $newHashtags = [];
        foreach ($matches[1] as $hashtagText) {
            $hashtag = Hashtags::firstOrCreate(
                ['hashtag' => $hashtagText],
                ['operator_id' => auth()->user()->id]
            );
            $newHashtags[] = $hashtag->id;
        }
        $newsbefore->hashtags()->whereNotIn('hashtag_id', $newHashtags)->detach();
        $newsbefore->hashtags()->sync($newHashtags);

        // Cek jika ada perubahan pada model
        if ($newsbefore->getDirty()) {

            DB::beginTransaction();
            try {
                $newsbefore->save();
                DB::commit();
                return redirect()->route('mstnews.index')->with(['success' => 'Berita Update Sukses: '.$request->title]);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Berita! '.$e->getMessage()]);
            }

        } else {
            return redirect()->route('mstnews.index')->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }
    }


    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            News::where('id', $id)->update([
                'status' => 'actived'
            ]);

            $name = News::where('id', $id)->first();


            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menayangkan Berita ' . $name->title]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menayangkan Berita ' . $name->title .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            News::where('id', $id)->update([
                'status' => 'deactived'
            ]);

            $name = News::where('id', $id)->first();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menonaktifkan Tayangan Berita ' . $name->title]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menonaktifkan Tayangan Berita ' . $name->title .'!']);
        }
    }

    public function delete($id)
    {
        $idnews = decrypt($id);

        // Cek produk berdasarkan ID
        $news = News::select('id', 'title', 'thumbnail')->find($idnews);
        if (!$news) {
            // Jika produk tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Berita!']);
        }

        DB::beginTransaction();
        try {
            // Hapus file thumbnail jika ada
            if ($news->thumbnail) {
                $old_thum = public_path($news->thumbnail);
                if (file_exists($old_thum)) {
                    unlink($old_thum); // Menghapus file thumbnail
                }
            }

            // Hapus produk
            $news->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Hapus Berita: ' . $news->title]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Berita! Kesalahan: ' . $e->getMessage()]);
        }
    }


    // Komentar
    public function comment()
    {
        $comment = Comments::select('comments.*', 'news.title')
        ->join('news', 'news.id', 'comments.news_id')
        ->orderBy('comments.id', 'desc')
        ->get();


       return view('news.comment', compact('comment'));
    }
    public function store_comment(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'comment' => 'required|string',
        ], [
            'comment.required' => 'Pesan Wajib Di isi.',
            'comment.string' => 'Harus Berupa Text Valid.',
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();
        try {

            // Simpan data ke database
            $inputData = [
                'news_id' => $id,
                'name' => auth()->user()->name,
                'comment' => $request->comment,
            ];

            // Simpan data ke dalam database
            Comments::create($inputData);

            DB::commit();
            return redirect()->back()->with(['success' => 'Sukses Kirim Komentar']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Mengirim Komentar!'. $e->getMessage()]);
        }
    }

    public function status_comment($id)
    {
        $id = decrypt($id);

        $commenbefore = Comments::find($id);
        if (!$commenbefore) {
            return redirect()->back()->with(['fail' => 'Komentar tidak ditemukan!']);
        }


        $commenbefore->is_approved = $commenbefore->is_approved == 1 ? 0 : 1;


        if ($commenbefore->getDirty()) {

            DB::beginTransaction();
            try {

                $commenbefore->save();


                DB::commit();
                return redirect()->back()->with(['success' => 'Komentar Status Pembaruan Sukses: '.$commenbefore->name]);

            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Komentar Status!'.$e]);
            }



        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }


    }


    public function delete_comment($id)
    {
        $id = decrypt($id);

        $comment = Comments::find($id);
        if (!$comment) {
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Komentar!']);
        }

        DB::beginTransaction();
        try {

            // Hapus
            $comment->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Hapus Komentar: ' . $comment->name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Komentar! Kesalahan: ' . $e->getMessage()]);
        }
    }
}
