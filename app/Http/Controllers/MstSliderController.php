<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MstSliderController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = $this->getData();
            return $data;
        }

        return view('slider.index');
    }
    public function getData()
    {
        $query = Slider::select(
            'slider.id',
            'slider.name',
            'slider.path_file',
            'slider.status',
        )
        ->orderBy('slider.id', 'desc');

        return DataTables::of($query)
            ->editColumn('status', function($data) {
                return $data->status == 'actived'
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Tidak Aktif</span>';
            })
            ->addColumn('gambar', function($data) {
                $url = asset($data->path_file); // atau sesuaikan path-nya
                return '<img src="'.$url.'" alt="'.$data->name.'" style="width: 150px; aspect-ratio: 3 / 1; object-fit: cover; border-radius: 4px;" />';
            })
            ->addColumn('action', function($data){
                $dropdown = '<div class="btn-group" role="group">
                        <button id="btnGroupDrop'.$data->id.'" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop'.$data->id.'">
                            <li><a href="#"
                                    class="dropdown-item drpdwn-wrg btn-edit"
                                    data-id="'.$data->id.'"
                                    data-title="'.htmlspecialchars($data->name, ENT_QUOTES).'"
                                    data-path_file="'.$data->path_file.'"
                                    data-status="'.$data->status.'"
                                    data-route="'.route('mstslider.update', encrypt($data->id)).'"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit"
                                ><span class="mdi mdi-file-edit"></span> | Ubah</a></li>';
                if($data->status == 'actived'){
                    $dropdown .= '<li><a href="#"
                        class="dropdown-item drpdwn-dgr btn-deactivate"
                        data-id="'.$data->id.'"
                        data-title="'.htmlspecialchars($data->name, ENT_QUOTES).'"
                        data-route="'.route('mstslider.deactivate', encrypt($data->id)).'"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-deactivate"
                    ><span class="mdi mdi-close-circle"></span> | Nonaktifkan</a></li>';
                } else {
                    $dropdown .= '<li><a href="#"
                            class="dropdown-item drpdwn-scs btn-activate"
                            data-id="'.$data->id.'"
                            data-title="'.htmlspecialchars($data->name, ENT_QUOTES).'"
                            data-route="'.route('mstslider.activate', encrypt($data->id)).'"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-activate"
                        ><span class="mdi mdi-check-circle"></span> | Aktifkan</a></li>';

                }

                $dropdown .= '<li><a href="#"
                        class="dropdown-item drpdwn-dgr btn-delete"
                        data-id="'.$data->id.'"
                        data-title="'.htmlspecialchars($data->name, ENT_QUOTES).'"
                        data-route="'.route('mstslider.delete', encrypt($data->id)).'"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-delete"
                        ><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>

                        </ul>
                    </div>';

                return $dropdown;
            })
            ->rawColumns(['status', 'action', 'gambar'])
            ->make(true);

    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'thumbnail.image' => 'File foto harus berupa gambar.',
            'thumbnail.mimes' => 'Format foto harus jpeg, jpg, png, atau webp.',
            'thumbnail.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();
        try {

            // Proses penyimpanan file Foto
            if($request->hasFile('thumbnail')){
                $path_loc_thumb = $request->file('thumbnail');
                $name = $path_loc_thumb ->hashName();
                $path_loc_thumb->move(public_path('uploads/slider'), $name);
                $url_thumb = 'uploads/slider/'.$name;
            }else{
                $url_thumb = null;
                return redirect()->back()->with(['fail' => 'Gagal Menyimpan File Slider!']);
            }

            // Simpan data ke database
            $inputData = [
                'name' => $request->name,
                'path_file' => $url_thumb,
                'user_id' => auth()->user()->id,
                'status' => 'actived'
            ];

            // Simpan data ke dalam database
            Slider::create($inputData);

            DB::commit();
            return redirect()->back()->with(['success' => 'Menambahkan Slider '.$request->name]);
        } catch (Exception $e) {
            DB::rollback();
            //kondisi untuk menghapus file yang kesimpan
            if (isset($url_thumb) && $url_thumb) {
                $filePath = public_path($url_thumb);
                if (file_exists($filePath)) {
                    unlink($filePath);  // Menghapus file
                }
            }
            return redirect()->back()->with(['fail' => 'Gagal Membuat Slider Baru!'. $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'thumbnail.image' => 'File foto harus berupa gambar.',
            'thumbnail.mimes' => 'Format foto harus jpeg, jpg, png, atau webp.',
            'thumbnail.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }


        $databefore = Slider::find($id);
        $databefore->name = $request->name;
        if ($request->hasFile('thumbnail')) {
            // Hapus file foto lama jika ada
            if ($databefore->foto) {
                $old_thumb = public_path($databefore->foto);
                if (file_exists($old_thumb)) {
                    unlink($old_thumb);
                }
            }

            // Simpan file foto yang baru
            $path_loc_thumbnail = $request->file('thumbnail');
            $namethumbnail = $path_loc_thumbnail->hashName();
            $path_loc_thumbnail->move(public_path('uploads/slider'), $namethumbnail);
            $databefore->path_file = 'uploads/slider/' . $namethumbnail;
        }

        if ($databefore->getDirty()) {
            DB::beginTransaction();
            try {

                $databefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Memperbarui Slider: '.$request->name]);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Slider!'. $e->getMessage()]);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }

    }
    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            Slider::where('id', $id)->update([
                'status' => 'actived'
            ]);

            $name = Slider::where('id', $id)->first();


            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menayangkan Slider ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menayangkan Slider ' . $name->name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            Slider::where('id', $id)->update([
                'status' => 'deactived'
            ]);

            $name = Slider::where('id', $id)->first();

            DB::commit();
            return redirect()->back()->with(['success' => 'Menonaktifkan Tayangan Slider ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Menonaktifkan Tayangan Slider ' . $name->name .'!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);

        // Cek produk berdasarkan ID
        $data = Slider::find($id);
        if (!$data) {
            // Jika produk tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Slider!']);
        }

        DB::beginTransaction();
        try {
            // Hapus file jika ada
            if ($data->path_file) {
                $old_thum = public_path($data->path_file);
                if (file_exists($old_thum)) {
                    unlink($old_thum); // Menghapus file
                }
            }

            // Hapus produk
            $data->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Menghapus Slider: ' . $data->name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Menghapus Slider! Kesalahan: ' . $e->getMessage()]);
        }
    }
}
