<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $arsip = Arsip::where('jenis_arsip', 'keluar')
            ->with('user', 'kategori', 'validator')
            ->orderByRaw("CASE WHEN status_keluar = 'diproses' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->get();

        $validators = User::whereHas('role', function ($query) {
            $query->where('nama_role', 'dekan')
                ->orWhere('nama_role', 'kaprodi');
        })->get();

        $users = User::whereIn('role_id', [1, 3])->get();
        $kategori = Kategori::all();

        return view('admin.suratKeluar.index', compact('arsip', 'users', 'kategori', 'validators'));
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'no_surat' => 'required|unique:arsip,no_surat',
            'tgl_surat' => 'required|date',
            'tujuan_keluar' => 'required',
            'isi' => 'required',
            'no_arsip' => 'required',
            'nama_file' => 'required',
            'user_id' => 'required',
            'kategori_id' => 'required',
            'file' => 'nullable|file',
            'validator' => 'nullable',
            'keterangan' => 'nullable',
        ], [
            'no_surat.required' => 'Nomor surat harus diisi',
            'no_surat.unique' => 'Nomor surat sudah digunakan',
            'tgl_surat.required' => 'Tanggal surat harus diisi',
            'tujuan_keluar.required' => 'Tujuan harus diisi',
            'isi.required' => 'Isi surat harus diisi',
            'no_arsip.required' => 'Nomor arsip harus diisi',
            'nama_file.required' => 'Nama file harus diisi',
            'user_id.required' => 'Penanggung jawab harus diisi',
            'kategori_id.required' => 'Kategori harus diisi',
        ]);

        $existingArsip = Arsip::where('no_surat', $validatedData['no_surat'])->first();
        if ($existingArsip) {
            return redirect()->back()->with('error', 'Nomor surat sudah digunakan. Silakan gunakan nomor surat yang berbeda.');
        }

        $arsip = new Arsip();
        $arsip->no_surat = $validatedData['no_surat'];
        $arsip->tgl_surat = $validatedData['tgl_surat'];
        $arsip->tujuan_keluar = $validatedData['tujuan_keluar'];
        $arsip->isi = $validatedData['isi'];
        $arsip->no_arsip = $validatedData['no_arsip'];
        $arsip->nama_file = $validatedData['nama_file'];
        $arsip->jenis_arsip = 'keluar';
        $arsip->user_id = $validatedData['user_id'];
        $arsip->kategori_id = $validatedData['kategori_id'];
        $arsip->validator = $validatedData['validator'];
        $arsip->keterangan = $validatedData['keterangan'];
        $arsip->status_keluar = 'diproses';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/arsip/suratkeluar', $filename);
            $arsip->file = $filename;
        }

        $arsip->save();

        return redirect()->route(Auth::user()->role->nama_role . '.surat-keluar.index')
            ->with('success', 'Surat keluar berhasil ditambahkan.');
    }
    public function show($id)
    {
        $arsip = Arsip::where('jenis_arsip', 'keluar')
            ->with('user', 'kategori')
            ->findOrFail($id);

        return response()->json(['arsip' => $arsip]);
    }

    public function edit($id)
    {
        $arsip = Arsip::with('user', 'kategori')->findOrFail($id);
        return response()->json(['arsip' => $arsip]);
    }

    public function update(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        $validatedData = $request->validate([
            'no_surat' => 'required',
            'tgl_surat' => 'required|date',
            'tujuan_keluar' => 'required',
            'isi' => 'required',

            'no_arsip' => 'required',
            'nama_file' => 'required',

            'user_id' => 'required',
            'kategori_id' => 'required',
            'file' => 'nullable|file',
            'validator' => 'nullable',
            'keterangan' => 'nullable',
        ], [
            'no_surat.required' => 'Nomor surat harus diisi',
            'tgl_surat.required' => 'Tanggal surat harus diisi',
            'tujuan_keluar.required' => 'Tujuan harus diisi',
            'isi.required' => 'Isi surat harus diisi',

            'no_arsip.required' => 'Nomor arsip harus diisi',
            'nama_file.required' => 'Nama file harus diisi',

            'user_id.required' => 'Penanggung jawab harus diisi',
            'kategori_id.required' => 'Kategori harus diisi',
        ]);

        try {
            $arsip->update([
                'no_surat' => $validatedData['no_surat'],
                'tgl_surat' => $validatedData['tgl_surat'],
                'tujuan_keluar' => $validatedData['tujuan_keluar'],
                'isi' => $validatedData['isi'],

                'no_arsip' => $validatedData['no_arsip'],
                'nama_file' => $validatedData['nama_file'],

                'user_id' => $validatedData['user_id'],
                'kategori_id' => $validatedData['kategori_id'],
                'validator' => $validatedData['validator'],
                'keterangan' => $validatedData['keterangan'],
            ]);
        } catch (\Exception $e) {
            \Log::error("Update arsip failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui arsip.']);
        }

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($arsip->file && Storage::disk('public')->exists('arsip/suratkeluar/' . $arsip->file)) {
                Storage::disk('public')->delete('arsip/suratkeluar/' . $arsip->file);
            }

            $file = $request->file('file');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/arsip/suratkeluar', $filename);
            $arsip->file = $filename;
            $arsip->save();
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        // Deleting the main document file
        $documentPath = 'arsip/suratkeluar/' . $arsip->file;
        if (Storage::disk('public')->exists($documentPath)) {
            Storage::disk('public')->delete($documentPath);
        } else {
            \Log::error("File not found: " . $documentPath);
        }

        // Deleting the signature file
        if ($arsip->ttd && Storage::disk('public')->exists($arsip->ttd)) {
            Storage::disk('public')->delete($arsip->ttd);
        } else if ($arsip->ttd) {
            \Log::error("Signature file not found: " . $arsip->ttd);
        }

        // Delete the archive record
        $arsip->delete();

        return response()->json(['success' => true]);
    }


    public function lihatIsiSurat($id)
    {
        $surat = Arsip::with('validator')->findOrFail($id);
        $validator = null;
        $no_identitas = null;

        if ($surat->validator) {
            $validatorUser = User::find($surat->validator);
            $validator = $validatorUser->name;
            $no_identitas = $validatorUser->no_identitas;
        }

        return view('admin.suratKeluar.isi-surat-keluar', [
            'validator' => $validator,
            'no_identitas' => $no_identitas,
            'no_surat' => $surat->no_surat,
            'nama_file' => $surat->nama_file,
            'isi' => $surat->isi,
            'tgl_surat' => $surat->tgl_surat,
            'ttd' => $surat->ttd
        ]);
    }

    // sign
    public function tandaTangan(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        // Check if an existing signature file needs to be deleted
        if ($arsip->ttd && Storage::disk('public')->exists($arsip->ttd)) {
            Storage::disk('public')->delete($arsip->ttd);
        }

        $data = $request->input('signature');
        $image_path = 'signatures/' . uniqid() . '.png';
        $image_data = explode(',', $data)[1];
        $image_data = base64_decode($image_data);

        Storage::disk('public')->put($image_path, $image_data);

        $arsip->ttd = $image_path;
        // Set status_keluar to 'disetujui' when a signature is added
        $arsip->status_keluar = 'disetujui';
        $arsip->save();

        return response()->json(['success' => true]);
    }

    public function tolakSurat(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        // Hanya menghapus file tanda tangan jika bukan file default
        $defaultSignaturePath = 'signatures/default.png';  // Path tanda tangan default

        if ($arsip->ttd && $arsip->ttd !== $defaultSignaturePath && Storage::disk('public')->exists($arsip->ttd)) {
            Storage::disk('public')->delete($arsip->ttd);
        }

        // Mengatur ulang path ttd ke default setelah penolakan
        $arsip->ttd = $defaultSignaturePath;
        $arsip->status_keluar = 'ditolak';
        $arsip->save();

        return response()->json(['message' => 'Surat berhasil ditolak'], 200);
    }
}
