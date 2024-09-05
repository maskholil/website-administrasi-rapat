<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{


    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('pegawai')) {
            // Jika role admin atau pegawai, tampilkan semua data surat masuk
            $arsip = Arsip::where('jenis_arsip', 'masuk')
                ->with('user', 'kategori', 'tujuanUsers')
                ->get();
        } else {
            // Jika role dekan atau kaprodi, tampilkan data surat masuk sesuai tujuan
            $arsip = Arsip::where('jenis_arsip', 'masuk')
                ->whereHas('tujuanUsers', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with('user', 'kategori', 'tujuanUsers')
                ->get();
        }

        $tujuans = User::whereNotIn('role_id', [1, 5])->get();
        $users = User::whereIn('role_id', [1, 3])->get();
        $kategori = Kategori::all();

        return view('admin.suratMasuk.index', compact('arsip', 'users', 'tujuans', 'kategori'));
    }



    public function store(Request $request)
    {


        $validatedData = $request->validate([
            'no_surat' => 'required|unique:arsip',
            'tgl_surat' => 'required|date',
            'tujuan' => 'required|array',
            'isi' => 'required',
            'no_arsip' => 'required',
            'nama_file' => 'required',
            'user_id' => 'required',
            'kategori_id' => 'required',
            'file' => 'required|file',
            'keterangan' => 'nullable',
        ], [
            'no_surat.required' => 'Nomor surat harus diisi',
            'no_surat.unique' => 'Nomor surat sudah digunakan',
            'tgl_surat.required' => 'Tanggal surat harus diisi',
            'tujuan.required' => 'Tujuan surat harus diisi',
            'isi.required' => 'Isi surat harus diisi',
            'no_arsip.required' => 'Nomor arsip harus diisi',
            'nama_file.required' => 'Nama file harus diisi',
            'user_id.required' => 'Penanggung jawab harus diisi',
            'kategori_id.required' => 'Kategori harus diisi',
            'file.required' => 'File harus diupload',
        ]);

        $arsip = new Arsip();
        $arsip->no_surat = $validatedData['no_surat'];
        $arsip->tgl_surat = $validatedData['tgl_surat'];
        $arsip->isi = $validatedData['isi'];
        $arsip->no_arsip = $validatedData['no_arsip'];
        $arsip->nama_file = $validatedData['nama_file'];
        $arsip->jenis_arsip = 'masuk';
        $arsip->user_id = $validatedData['user_id'];
        $arsip->kategori_id = $validatedData['kategori_id'];
        $arsip->keterangan = $validatedData['keterangan'];

        DB::transaction(function () use ($request, $validatedData, $arsip) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->storeAs('public/arsip/suratmasuk', $filename);
                $arsip->file = $filename;
            }

            $arsip->save();
            $arsip->tujuanUsers()->sync($validatedData['tujuan']);
        
        });

        return redirect()->route(Auth::user()->role->nama_role . '.surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $arsip = Arsip::where('jenis_arsip', 'masuk')
            ->with('user', 'kategori', 'tujuanUsers')
            ->findOrFail($id);

        return response()->json([
            'arsip' => $arsip,
            'tujuanUsers' => $arsip->tujuanUsers
        ]);
    }

    public function edit($id)
    {
        $arsip = Arsip::with('user', 'kategori', 'tujuanUsers')->findOrFail($id);
        $tujuanUserIds = $arsip->tujuanUsers->pluck('id')->toArray();
        return response()->json([
            'arsip' => $arsip,
            'tujuanUserIds' => $tujuanUserIds
        ]);
    }

    public function update(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        $validatedData = $request->validate([
            'no_surat' => 'required',
            'tgl_surat' => 'required|date',
            'tujuan' => 'required|array',
            'isi' => 'required',
            'no_arsip' => 'required',
            'nama_file' => 'required',

            'user_id' => 'required',
            'kategori_id' => 'required',
            'file' => 'nullable|file',
            'keterangan' => 'nullable',
        ], [
            'no_surat.required' => 'Nomor surat harus diisi',
            'tgl_surat.required' => 'Tanggal surat harus diisi',
            'tujuan.required' => 'Tujuan surat harus diisi',
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
                'isi' => $validatedData['isi'],
                'no_arsip' => $validatedData['no_arsip'],
                'nama_file' => $validatedData['nama_file'],
                'user_id' => $validatedData['user_id'],
                'kategori_id' => $validatedData['kategori_id'],
                'keterangan' => $validatedData['keterangan'],
            ]);
        } catch (\Exception $e) {
            \Log::error("Update arsip failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui arsip.']);
        }

        $arsip->tujuanUsers()->sync($validatedData['tujuan']);

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($arsip->file && Storage::disk('public')->exists('arsip/suratmasuk/' . $arsip->file)) {
                Storage::disk('public')->delete('arsip/suratmasuk/' . $arsip->file);
            }

            $file = $request->file('file');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/arsip/suratmasuk', $filename);
            $arsip->file = $filename;
            $arsip->save();
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        // Contoh path jika nama file '1713341572_TEST_UPLOAD.pdf' disimpan di database
        $fullPath = 'arsip/suratmasuk/' . $arsip->file; // Sesuaikan berdasarkan struktur folder Anda

        if (Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->delete($fullPath);
        } else {
            \Log::error("File not found: " . $fullPath);
        }

        // Hapus data pada tabel pivot (arsip_user)
        $arsip->tujuanUsers()->detach();

        // Hapus data pada tabel disposisi yang terkait dengan arsip
        $arsip->disposisi()->delete();

        $arsip->delete();

        return response()->json(['success' => true]);
    }

    public function terima($suratId)
    {
        $user = auth()->user();

        $arsipUser = DB::table('arsip_user')
            ->where('arsip_id', $suratId)
            ->where('user_id', $user->id)
            ->update(['status_masuk' => 'diterima']);

        if ($arsipUser) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function disposisi(Request $request, $suratId)
    {
        $user = auth()->user();
        $keterangan = $request->input('keterangan');

        DB::beginTransaction();

        try {
            // Update the arsip_user table
            $arsipUser = DB::table('arsip_user')
                ->where('arsip_id', $suratId)
                ->where('user_id', $user->id)
                ->update([
                    'status_masuk' => 'disposisi',
                    'keterangan' => $keterangan
                ]);

            if ($arsipUser) {
                // Check if all users associated with this arsip have disposisi status
                $allDisposisi = DB::table('arsip_user')
                    ->where('arsip_id', $suratId)
                    ->where('status_masuk', 'disposisi')
                    ->count();

                $totalUsers = DB::table('arsip_user')
                    ->where('arsip_id', $suratId)
                    ->count();

                if ($allDisposisi === $totalUsers) {
                    // If all users have disposisi status, insert into disposisi table
                    DB::table('disposisi')->insert([
                        'catatan' => 'Disposisi ulang',
                        'arsip_id' => $suratId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // Commit transaction
                DB::commit();
                return response()->json(['status' => 'success']);
            } else {
                \Log::error('gagal untuk update arsip_user', [
                    'suratId' => $suratId,
                    'user' => $user->id,
                ]);
                DB::rollBack();
                return response()->json(['status' => 'error', 'msg' => 'No changes made']);
            }
        } catch (\Exception $e) {
            \Log::error('Exception during disposisi operation: ' . $e->getMessage());
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => 'Gagal menjalankan operasi disposisi']);
        }
    }


    public function getPivotDetails($pivotId)
    {
        $pivot = DB::table('arsip_user')->find($pivotId);
        if (!$pivot) {
            return response()->json(['error' => 'Pivot not found'], 404);
        }
        // Make sure to return the keterangan field in your response
        // dd($pivot);
        return response()->json([
            'id' => $pivot->id,
            'keterangan' => $pivot->keterangan
        ]);
    }
}
