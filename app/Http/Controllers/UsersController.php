<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }


    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata Sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi Kata Sandi tidak sesuai.',
            'password.min' => 'Kata Sandi minimal harus 8 karakter.',
            'username.required' => 'Username wajib diisi.',
            'role_id.required' => 'Role ID wajib diisi.',
            'is_active.required' => 'Status aktif wajib diisi.',
            'no_hp.required' => 'Nomor telepon wajib diisi.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'no_identitas' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|max:255',
            'password' => 'required|confirmed|min:8',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
            'no_hp' => 'nullable|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ], $messages);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['is_active'] = $validatedData['is_active'] ? 1 : 0;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('users', $fotoName, 'public');
            $validatedData['foto'] = $fotoName;
        }

        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan.');
    }


    public function show($id)
    {
        $user = User::findOrFail($id);
        $roleName = $user->role->nama_role;

        $fotoUrl = '';

        if ($user->foto) {
            $fotoPath = storage_path('app/public/users/' . $user->foto);
            if (file_exists($fotoPath)) {
                $fotoUrl = asset('storage/users/' . $user->foto);
            }
        }

        return response()->json([
            'user' => $user,
            'role_name' => $roleName,
            'foto_url' => $fotoUrl,
            'no_identitas' => $user->no_identitas
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::withCount('users')->get();

        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'no_identitas' => $user->no_identitas
        ]);
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'username.required' => 'Username wajib diisi.',
            'role_id.required' => 'Role ID wajib diisi.',
            'is_active.required' => 'Status aktif wajib diisi.',
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Gambar harus bertipe: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];

        $validatedData = $request->validate([
            'no_identitas' => 'nullable',
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|max:255',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
            'no_hp' => 'nullable|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ], $messages);

        $validatedData['is_active'] = $validatedData['is_active'] ? 1 : 0;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists('users/' . $user->foto)) {
                Storage::disk('public')->delete('users/' . $user->foto);
            }
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('users', $fotoName, 'public');
            $validatedData['foto'] = $fotoName; // Simpan nama file baru
        }


        $user->update($validatedData);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }
}
