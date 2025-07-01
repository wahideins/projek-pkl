<?php

// Namespace diperbarui agar sesuai dengan lokasi file di app/Http/Controllers/Auth/
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Metode ini mengembalikan view yang berisi form login Anda.
        // Pastikan Anda memiliki file 'login.blade.php' di 'resources/views/auth/'.
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // 1. Validasi data request yang masuk.
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Coba untuk mengotentikasi pengguna.
        // Metode 'attempt' akan secara otomatis mengenkripsi password untuk perbandingan.
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            // 3. Jika otentikasi berhasil, buat ulang sesi.
            // Ini adalah langkah keamanan untuk mencegah serangan session fixation.
            $request->session()->regenerate();

            // 4. Arahkan pengguna ke tujuan yang dimaksud atau ke dashboard.
            return redirect()->intended('/dashboard');
        }

        // 5. Jika otentikasi gagal, lemparkan validation exception.
        // Ini akan secara otomatis mengarahkan pengguna kembali ke form login
        // dan menampilkan pesan error.
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Logout pengguna dari guard 'web'.
        Auth::guard('web')->logout();

        // Batalkan sesi pengguna.
        $request->session()->invalidate();

        // Buat ulang token CSRF untuk keamanan.
        $request->session()->regenerateToken();

        // Arahkan pengguna ke halaman utama.
        return redirect('/');
    }
}
