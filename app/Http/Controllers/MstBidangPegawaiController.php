<?php

namespace App\Http\Controllers;

use App\Models\BidangPegawai;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstBidangPegawaiController extends Controller
{
    public function index()
    {
        $jabatan = BidangPegawai::get();

        return view('datapegawai.bidang', compact('jabatan'));
    }
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:jabatan_pegawai,name',
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
            BidangPegawai::create([
                'name' => $request->name,
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
        $idcategories = decrypt($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:jabatan_pegawai,name,' . $idcategories,
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                             ->withErrors($validate)
                             ->withInput()
                             ->with(['fail' => 'Failed, Check Your Input']);
        }
        $before = BidangPegawai::find($idcategories);
        $before->name = $request->name;

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
        $idcategories = decrypt($id);

        DB::beginTransaction();
        $categories = BidangPegawai::where('id', $idcategories)->first();
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
