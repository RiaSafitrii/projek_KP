<?php

namespace App\Http\Controllers;

use App\Models\JabatanPegawai;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstJabatanPegawaiController extends Controller
{
    public function index()
    {
        $jabatan = JabatanPegawai::get();

        return view('datapegawai.jabatan', compact('jabatan'));
    }
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'jabatan' => 'required|string|max:255|unique:jabatan_pegawai,name',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                             ->withErrors($validate)
                             ->withInput()
                             ->with(['fail' => 'Failed, Check Your Input']);
        }
        DB::beginTransaction();
        try {
            // Simpan data simulasi ke database
            JabatanPegawai::create([
                'name' => $request->jabatan,
            ]);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create!']);
        }

    }
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validate = Validator::make($request->all(), [
            'jabatan' => 'required|string|max:255|unique:jabatan_pegawai,name,' . $id,
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                             ->withErrors($validate)
                             ->withInput()
                             ->with(['fail' => 'Failed, Check Your Input']);
        }
        $before = JabatanPegawai::find($id);
        $before->name = $request->jabatan;

        if ($before->isDirty()) {
            DB::beginTransaction();
            try {

                $before->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        $categories = JabatanPegawai::where('id', $id)->first();
        try{

            $categories->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete ' . $categories->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete ' . $categories->name .'!']);
        }
    }
}
