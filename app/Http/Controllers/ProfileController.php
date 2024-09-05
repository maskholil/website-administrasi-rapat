<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function edit()
    {
        return view('profile.edit');
    }


    public function update(ProfileRequest $request)
    {
        if (!auth()->user()->is_active) {
            return back()->withErrors(['not_allow_profile' => __('Anda tidak diizinkan untuk mengubah data karena akun Anda tidak aktif.')]);
        }

        auth()->user()->update($request->all());

        return back()->withStatus(__('Profil berhasil diperbarui.'));
    }


    public function password(PasswordRequest $request)
    {
        if (!auth()->user()->is_active) {
            return back()->withErrors(['not_allow_password' => __('Anda tidak bisa mengubah password.')]);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]); // Directly use $request->password

        return back()->withPasswordStatus(__('Password berhasil diubah.'));
    }
}
