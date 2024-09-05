<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function index()
    {
        $peserta = Peserta::all();
        return view('admin.peserta.index', compact('peserta'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_peserta' => 'required|string|max:255',
        ]);

        Peserta::create($validatedData);

        return redirect()->route(Auth::user()->role->nama_role . '.peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $peserta = Peserta::findOrFail($id);
        return response()->json(['peserta' => $peserta]);
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $validatedData = $request->validate([
            'nama_peserta' => 'required',
        ]);

        $peserta->update($validatedData);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->delete();

        return response()->json(['success' => true]);
    }
}
