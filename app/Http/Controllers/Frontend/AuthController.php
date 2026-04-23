<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect('/admin')
                : redirect()->route('home');
        }

        return view('frontend.auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = trim($request->email);
        $password = $request->password;
        $remember = $request->boolean('remember');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan terdaftar dalam sistem.'
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password yang Anda masukkan salah. Silakan coba lagi.'
            ], 401);
        }

        // Cek status persetujuan jika bukan admin
        if ($user->role !== 'admin') {
            $isApprovedRaw = $user->getRawOriginal('is_approved');
            
            if (is_null($isApprovedRaw)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda sedang menunggu persetujuan admin. Kami akan mengirimkan email jika sudah disetujui.',
                ], 403);
            }

            if ($isApprovedRaw == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, pendaftaran Anda ditolak. Silakan hubungi admin gereja untuk informasi lebih lanjut.',
                ], 403);
            }
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return response()->json([
            'success'  => true,
            'message'  => 'Login berhasil!',
            'redirect' => $user->role === 'admin' ? '/admin' : route('home')
        ]);
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect('/admin')
                : redirect()->route('home');
        }

        return view('frontend.auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'sektor'        => 'required|string|max:100',
            'unit'          => 'required|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
        ], [
            'name.required'          => 'Nama lengkap wajib diisi',
            'email.required'         => 'Email wajib diisi',
            'email.email'            => 'Format email tidak valid',
            'email.unique'           => 'Email sudah terdaftar',
            'password.required'      => 'Password wajib diisi',
            'password.min'           => 'Password minimal 8 karakter',
            'password.confirmed'     => 'Konfirmasi password tidak cocok',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in'       => 'Pilih jenis kelamin yang valid',
            'sektor.required'        => 'Sektor wajib diisi',
            'unit.required'          => 'Unit wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'role'          => 'jemaat',
                'no_hp'         => $request->no_hp,
                'alamat'        => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'sektor'        => $request->sektor,
                'unit'          => $request->unit,
                'is_approved'   => null, // null = menunggu persetujuan admin
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan admin.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi'
            ], 500);
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success'  => true,
            'message'  => 'Logout berhasil',
            'redirect' => route('login')
        ]);
    }
}