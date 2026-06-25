<?php

namespace App\Http\Controllers;

use App\Models\Puskesmas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MstDataPuskesmasController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = $this->getData();
            return $data;
        }


        return view('puskesmas.index');
    }
    public function getData()
    {
        $query = Puskesmas::select(
            'puskesmas.id',
            'puskesmas.name',
            'puskesmas.alamat',
            'puskesmas.domain',
        )
        ->orderBy('puskesmas.id', 'desc');

        return DataTables::of($query)
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
                                    data-alamat="'.$data->alamat.'"
                                    data-domain="'.$data->domain.'"
                                    data-route="'.route('mstdatapuskesmas.update', encrypt($data->id)).'"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit"
                                ><span class="mdi mdi-file-edit"></span> | Ubah</a></li>';

                $dropdown .= '<li><a href="#"
                        class="dropdown-item drpdwn-dgr btn-delete"
                        data-id="'.$data->id.'"
                        data-title="'.htmlspecialchars($data->name, ENT_QUOTES).'"
                        data-route="'.route('mstdatapuskesmas.delete', encrypt($data->id)).'"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-delete"
                        ><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>

                        </ul>
                    </div>';

                return $dropdown;
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'alamat' => 'required|string',
            'domain' => 'required|string',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',

            'domain.required' => 'Domain wajib diisi.',
            'domain.string' => 'Domain harus berupa teks.',
        ]);


        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();
        try {

            // Simpan data ke database
            $inputData = [
                'name' => $request->name,
                'alamat' => $request->alamat,
                'domain' => $request->domain,
            ];

            // Simpan data ke dalam database
            Puskesmas::create($inputData);

            DB::commit();
            return redirect()->back()->with(['success' => 'Menambahkan Puskesmas '.$request->name]);
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['fail' => 'Gagal Menambahkan Puskesmas Baru!'. $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'alamat' => 'required|string',
            'domain' => 'required|string',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',

            'domain.required' => 'Domain wajib diisi.',
            'domain.string' => 'Domain harus berupa teks.',
        ]);



        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }


        $databefore = Puskesmas::find($id);
        $databefore->name = $request->name;
        $databefore->alamat = $request->alamat;
        $databefore->domain = $request->domain;


        if ($databefore->getDirty()) {
            DB::beginTransaction();
            try {

                $databefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Memperbarui Puskesmas: '.$request->name]);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Puskesmas!'. $e->getMessage()]);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }

    }

    public function delete($id)
    {
        $id = decrypt($id);

        // Cek produk berdasarkan ID
        $data = Puskesmas::find($id);
        if (!$data) {
            // Jika produk tidak ditemukan
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Puskesmas!']);
        }

        DB::beginTransaction();
        try {

            // Hapus produk
            $data->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Menghapus Puskesmas: ' . $data->name]);
        } catch (Exception $e) {
            DB::rollback();
            // Menampilkan error yang lebih rinci
            return redirect()->back()->with(['fail' => 'Menghapus Puskesmas! Kesalahan: ' . $e->getMessage()]);
        }
    }
}
