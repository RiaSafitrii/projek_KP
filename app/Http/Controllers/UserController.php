<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('role', 'operator')->orderBy('id', 'desc')
        ->get();

        return view('users.index', compact('user'));
    }

    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email ini sudah digunakan.',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung setidaknya satu huruf kecil, satu huruf besar, satu angka, dan satu karakter khusus.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }


        $count= User::where('email',$request->email)->count();

        if($count > 0){
            return redirect()->back()->with('warning','Email Sudah Terdaftar Sebagai Pengguna');
        } else {
            DB::beginTransaction();

            try{

                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'status' => 'actived',
                    'role' => 'operator'
                ]);

                DB::commit();
                return redirect()->back()->with(['success' => 'Pengguna Baru Berhasil ditambahkan']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Membuat Pengguna Baru!']);
            }
        }
    }

    public function newpassword(Request $request)
    {
        // Validasi input
        $validate = Validator::make($request->all(), [
            'old_password' => [
                'required',  // Password lama harus diisi
                'string',
                'min:8', // Sesuaikan panjang minimal password lama jika perlu
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', // Kombinasi huruf besar, huruf kecil, angka, dan simbol
            ],
        ], [
            'old_password.required' => 'Password lama wajib diisi.',
            'old_password.min' => 'Password lama minimal 8 karakter.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password lama minimal 8 karakter.',
            'password.regex' => 'Password harus terdiri dari huruf besar, huruf kecil, angka, dan simbol.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate);
        }

        $iduser = auth()->user()->id;
        $userbefore = User::where('id', $iduser)->first();

        // Cek apakah password lama yang dimasukkan cocok dengan password yang ada di database
        if (!Hash::check($request->old_password, $userbefore->password)) {
            // Jika password lama tidak cocok
            return redirect()->back()->with(['fail' => 'Password lama tidak cocok.']);
        }

        // Jika password baru diisi, update juga password di model
        if ($request->filled('password')) {
            // Update password baru jika password lama valid
            $userbefore->password = Hash::make($request->password);
        }

        // Simpan perubahan jika ada perubahan
        if ($userbefore->isDirty()) {
            DB::beginTransaction();
            try {
                // Simpan perubahan ke database
                $userbefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Sukses Perbarui Kata Sandi']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Kata Sandi!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }
    }


    public function update(Request $request, $id)
    {

        $iduser = decrypt($id);

        $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:255', // Wajib, harus berupa string, dan maksimal 255 karakter
            'email' => 'required|email|max:255|unique:users,email,' . $iduser, // Wajib, harus format email, maksimal 255 karakter, dan unik pada tabel `users`
            'password' => [
                'nullable',
                'string',
                'min:8', // Minimal panjang 8 karakter
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', // Kombinasi huruf besar, huruf kecil, angka, dan simbol
            ],
        ],[
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email ini sudah digunakan.',

            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung setidaknya satu huruf kecil, satu huruf besar, satu angka, dan satu karakter khusus.',
        ]);

        //jika validasi ada yang tidak sesuai
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        $userbefore = User::where('id', $iduser)->first();
        $userbefore->name = $request->name;
        $userbefore->email = $request->email;

        // Jika password diisi, update juga password di model
        if ($request->filled('password')) { // Mengecek apakah password baru diisi
            // Cek apakah password lama yang dimasukkan sesuai dengan password yang sudah terenkripsi
            if (!Hash::check($request->password, $userbefore->password)) {
                //jika password beda
                $userbefore->password = Hash::make($request->password);
            }
        }

        if($userbefore->isDirty()){
            DB::beginTransaction();
            try{
                // Simpan perubahan ke database
                $userbefore->save();

                DB::commit();
                return redirect()->back()->with(['success' => 'Pengguna Berhasil Update']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Gagal Memperbarui Pengguna!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Tidak Ada Perubahan, Data yang dimasukkan sama dengan yang sebelumnya!']);
        }
    }

    public function delete($id)
    {
        $iduser = decrypt($id);

        DB::beginTransaction();
        try{

            $name = User::where('id', $iduser)->first();

            User::where('id', $iduser)->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Hapus Pengguna ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menghapus Pengguna ' . $name->email .'!']);
        }
    }


    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = User::where('id', $id)->update([
                'status' => 'actived'
            ]);

            $name = User::where('id', $id)->first();


            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Aktifkan Pengguna ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Mengaktifkan Pengguna ' . $name->email .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = User::where('id', $id)->update([
                'status' => 'deactived'
            ]);

            $name = User::where('id', $id)->first();

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Nonaktifkan Pengguna ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal Menonaktifkan Pengguna ' . $name->email .'!']);
        }
    }

}
