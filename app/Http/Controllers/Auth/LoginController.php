<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }


    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($field === 'username') {
            return [
                'username' => $request->get('username'),
                'password' => $request->get('password'),
                'is_active' => 1,
            ];
        } else {
            return [
                'email' => $request->get('username'),
                'password' => $request->get('password'),
                'is_active' => 1,
            ];
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $credentials = $this->credentials($request);
        $field = filter_var($request->get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt($credentials)) {
            $user = User::where($field, $credentials[$field])->first();

            if ($user) {
                if (!$user->is_active) {
                    $message = 'Akun Anda tidak aktif. Silakan hubungi administrator untuk mengaktifkannya.';
                } elseif (!Hash::check($credentials['password'], $user->password)) {
                    $message = 'Password salah.';
                } else {
                    $message = 'Terjadi kesalahan saat login. Silakan coba lagi.';
                }
            } else {
                if ($field === 'email') {
                    $message = 'Email tidak terdaftar.';
                } else {
                    $message = 'Username tidak terdaftar.';
                }
            }
        } else {
            $message = 'Terjadi kesalahan saat login. Silakan coba lagi.';
        }

        throw ValidationException::withMessages([
            'username' => [$message],
        ]);
    }


    protected function getSuccessMessage($role)
    {
        $messages = [
            'admin' => 'Selamat datang di Dashboard Admin!',
            'pegawai' => 'Selamat datang di Dashboard Pegawai!',
            'dekan' => 'Selamat datang di Dashboard Dekan!',
            'kaprodi' => 'Selamat datang di Dashboard Kaprodi!',
            'ketua' => 'Selamat datang di Dashboard Ketua!',
            'dosen' => 'Selamat datang di Dashboard Dosen!',
        ];

        return $messages[$role] ?? 'Login berhasil!';
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->is_active) {
            $role = $user->role->nama_role; // Mengasumsikan relasi dengan tabel roles
            $successMessage = $this->getSuccessMessage($role);

            return redirect()->intended(route($role . '.dashboard'))
                ->with('success', $successMessage);
        } else {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Akun Anda tidak aktif. Silakan hubungi administrator untuk mengaktifkannya.']);
        }
    }
}
