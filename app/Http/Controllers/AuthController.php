<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
use App\Models\OPD;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use function Illuminate\Cookie\Middleware\encrypt;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function postlogin(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate)->with(['fail' => 'Email atau Kata Sandi tidak boleh kosong!']);
        }


        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ditemukan atau password tidak cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('fail', 'Email atau Kata Sandi yang anda masukkan tidak valid!'); // Balik ke halaman sebelumnya dengan pesan error
        }

        // Periksa apakah akun user 'actived' atau tidak
        if ($user->status == 'actived') {
            // Login user jika kredensial valid
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Berhasil Masuk Aplikasi');
        } else {
            return redirect()->route('login')->with('fail', 'Akun Anda Tidak Aktif');
        }
    }

    public function register()
    {
        $opd = OPD::get();
        return view('auth.register', compact('opd'));
    }
    public function postregister(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'opd_id'    => 'required|exists:opd,id',
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'nip' => 'required|numeric|digits:18|unique:detail_user,nip',  // Validasi NIP
            'no_wa' => 'required|numeric|unique:detail_user,no_wa',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ], [
            'opd_id.required'  => 'OPD wajib dipilih.',
            'opd_id.exists' => 'OPD tidak valid.',

            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email ini sudah digunakan.',

            'nip.required' => 'NIP wajib diisi.',
            'nip.numeric' => 'NIP hanya boleh angka.',
            'nip.digits' => 'NIP harus terdiri dari 18 digit.',
            'nip.unique' => 'NIP ini sudah digunakan.',

            'no_wa.required' => 'Nomor Wa wajib diisi.',
            'no_wa.numeric' => 'Nomor Wa hanya boleh angka.',
            'no_wa.unique' => 'Nomor Wa ini sudah digunakan.',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung setidaknya satu huruf kecil, satu huruf besar, satu angka, dan satu karakter khusus.',
        ]);

        // Jika validasi gagal
        if ($validate->fails()) {
            return redirect()->route('register')->withInput()->withErrors($validate)->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        DB::beginTransaction();
        try {
            // Membuat User Baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'deactived',
                'role' => 'user'
            ]);

            // Menyimpan data ke tabel detail_user
            $user->detailUser()->create([
                'opd_id' => $request->opd_id,
                'nip' => $request->nip,
                'no_wa' => $request->no_wa,
            ]);

            $emailcrypt = base64_encode(Crypt::encryptString($request->email));

            DB::commit();
            // return redirect()->to('verify/'.$emailcrypt)
            //     ->with(['success' => 'Pendaftaran berhasil, silakan hubungi admin untuk aktivasi!']);


            $user = User::select(
                'users.name as name',
                'email',
                'opd.name as opd_name',
            )
            ->where('email', $request->email)
            ->leftJoin('detail_user', 'detail_user.user_id', '=', 'users.id')
            ->leftJoin('opd', 'opd.id', '=', 'detail_user.opd_id')
            ->first();

            $message = "Halo, Saya *{$user->name}*,\n\n" .
                "Saya Dari OPD *{$user->opd_name}*.\n" .
                "Saya telah melakukan registrasi di SKPP Online Dengan Email *{$user->email}*.\n" .
                "Mohon Agar Data saya dapat di verifikasi oleh admin \n\n" .
                "Terima kasih.";

            $whatsappLink = "https://wa.me/6285269783663?text=" . urlencode($message);


            return view('auth.verify', compact('whatsappLink'));

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Gagal melakukan pendaftaran, silakan hubungi admin!']);
        }
    }

    public function verify($email)
    {
        $decode = base64_decode($email);
        $email = Crypt::decryptString($decode);

        $user = User::select(
            'users.name as name',
            'email',
            'opd.name as opd_name',
        )
        ->where('email', $email)
        ->leftJoin('detail_user', 'detail_user.user_id', '=', 'users.id')
        ->leftJoin('opd', 'opd.id', '=', 'detail_user.opd_id')
        ->first();

        $message = "Halo, Saya *{$user->name}*,\n\n" .
               "Saya Dari OPD *{$user->opd_name}*.\n" .
               "Saya telah melakukan registrasi di SKPP Online Dengan Email *{$user->email}*.\n" .
               "Mohon Agar Data saya dapat di verifikasi oleh admin \n\n" .
               "Terima kasih.";

        $whatsappLink = "https://wa.me/6285269783663?text=" . urlencode($message);


        return view('auth.verify', compact('whatsappLink'));
    }


    public function forgotpassword(Request $request)
    {
        return view('auth.forgotpassword');
    }

    public function postforgotpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('fail', 'Email tidak terdaftar');
        }

        // Buat token reset password
        $token = Password::createToken($user);
        $resetUrl = url("/reset-password/{$token}?email={$user->email}");

        // Simpan token ke database
        $user->remember_token = $token; // Pastikan ada kolom reset_token di tabel users
        $user->save();

        try {
            // Kirim email reset password
            Mail::to($user->email)->send(new ForgotPasswordMail($user, $resetUrl));

            return back()->with('success', 'Tautan reset password telah dikirim ke email Anda');
        } catch (\Exception $e) {
            // Tangani jika terjadi error saat mengirim email
            return back()->with('fail', 'Gagal mengirim email reset password: ' . $e->getMessage());
        }
    }
    public function showResetForm(Request $request, $token)
    {
        // Validasi token
        $user = Password::getUser($request->only('email'), $token);

        // Jika pengguna tidak ditemukan dengan token
        if (!$user) {
            return redirect()->route('login')->with('fail', 'Token atau alamat email tidak valid.');
        }

        return view('auth.newpassword', ['token' => $token, 'email' => $request->query('email')]);
    }
    public function resetpassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed', // menggunakan confirmed untuk memvalidasi password dan konfirmasi password
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with(['fail' => 'Gagal, Periksa Input Anda']);
        }

        // Temukan pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek apakah pengguna ada dan token valid
        if (!$user || $request->token !== $user->remember_token) {
            return back()->with('fail', 'Invalid token or email'); // Token atau email tidak valid
        }

        // Perbarui password pengguna
        $user->password = Hash::make($request->password);
        $user->remember_token = null; // Reset token setelah berhasil diubah
        $user->save();

        return redirect()->route('login')->with('success', 'Kata sandi berhasil disetel ulang. Anda sekarang dapat masuk dengan kata sandi baru Anda.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('login');
    }
}
