<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
        ]);

        Kategori::create($validatedData);

        return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return response()->json([
            'kategori' => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validatedData = $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $kategori->id,
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $kategori->update($validatedData);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $kategori = Kategori::findOrFail($id);
    //     $kategori->delete();

    //     return response()->json(['success' => true]);
    // }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Menghapus data terkait pada tabel arsip, disposisi, arsip_user, dan disposisi_user
        foreach ($kategori->arsip as $arsip) {
            // Menghapus data pada tabel pivot arsip_user
            $arsip->tujuanUsers()->detach();

            // Menghapus data pada tabel disposisi dan tabel pivot disposisi_user
            foreach ($arsip->disposisi as $disposisi) {
                $disposisi->tujuanUsers()->detach();
                $disposisi->delete();
            }

            $arsip->delete();
        }

        $kategori->delete();

        return response()->json(['success' => true]);
    }
}
