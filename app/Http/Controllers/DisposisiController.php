<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Disposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisposisiController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('pegawai')) {
            // Jika role admin atau pegawai, tampilkan semua data disposisi
            $disposisi = Disposisi::with('arsip', 'tujuanUsers')->get();
        } else {
            // Jika role selain admin, pegawai, dan ketua, tampilkan data disposisi sesuai tujuan
            $disposisi = Disposisi::whereHas('tujuanUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('arsip', 'tujuanUsers')->get();
        }

        $arsip = Arsip::all();

        $disposisiUsers = [];
        foreach ($disposisi as $dis) {
            $arsipId = $dis->arsip_id;
            $existingUserIds = DB::table('arsip_user')
                ->where('arsip_id', $arsipId)
                ->pluck('user_id')
                ->toArray();

            $availableUsers = User::whereNotIn('id', $existingUserIds)
                ->whereNotIn('role_id', [1, 5])
                ->get();

            $disposisiUsers[] = [
                'disposisi_id' => $dis->id,
                'available_users' => $availableUsers,
            ];
        }

        return view('admin.disposisi.index', compact('disposisi', 'arsip', 'disposisiUsers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'catatan' => 'required',
            'tujuan' => 'required|array',
            'arsip_id' => 'required',
        ], [
            'catatan.required' => 'Catatan harus diisi',
            'tujuan.required' => 'Tujuan harus dipilih',
            'tujuan.array' => 'Tujuan harus berupa array',
            'arsip_id.required' => 'Arsip harus dipilih',
        ]);

        $disposisi = Disposisi::create([
            'catatan' => $validatedData['catatan'],
            'arsip_id' => $validatedData['arsip_id'],
        ]);

        $disposisi->tujuanUsers()->sync($validatedData['tujuan']);

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $disposisi = Disposisi::with(['arsip', 'tujuanUsers'])->findOrFail($id);
        return response()->json(['disposisi' => $disposisi]);
    }


    public function edit($id)
    {
        $disposisi = Disposisi::with('suratMasuk')->findOrFail($id);
        return response()->json(['disposisi' => $disposisi]);
    }

    public function update(Request $request, $id)
    {
        $disposisi = Disposisi::findOrFail($id);
        $validatedData = $request->validate([
            'catatan' => 'required',
            'tujuan' => 'required',
            'surat_masuk_id' => 'required',
        ]);
        $disposisi->update($validatedData);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        $disposisi->delete();
        return response()->json(['success' => true]);
    }



    public function disposisiUlang(Request $request, $id)
    {
        $disposisi = Disposisi::findOrFail($id);

        $validatedData = $request->validate([
            'tujuan' => 'required|array',
            'catatan' => 'required|string', // Validate catatan input
        ], [
            'tujuan.required' => 'Tujuan harus dipilih',
            'tujuan.array' => 'Tujuan harus berupa array',
            'catatan.required' => 'Catatan harus diisi', // Error message for catatan
        ]);

        $tujuanUserIds = $validatedData['tujuan'];
        $newCatatan = $validatedData['catatan'];

        // Update catatan in disposisi table
        $disposisi->update([
            'catatan' => $newCatatan,
        ]);

        // Menghapus semua tujuan yang ada sebelumnya
        $disposisi->tujuanUsers()->detach();

        // Menyiapkan data untuk tujuan baru
        $data = [];
        foreach ($tujuanUserIds as $userId) {
            $data[$userId] = ['keterangan' => 'Disposisi berhasil dilakukan']; // Here you might add additional data if needed
        }

        // Menambahkan tujuan baru
        $disposisi->tujuanUsers()->attach($data);

        return response()->json(['success' => true, 'message' => 'Disposisi ulang berhasil dilakukan dengan catatan baru.']);
    }


    public function terima($disposisiId)
    {
        $user = auth()->user();

        // Update the status in disposisi_user
        $updateStatus = DB::table('disposisi_user')
            ->where('disposisi_id', $disposisiId)
            ->where('user_id', $user->id)
            ->update(['status_disposisi' => 'diterima']);

        if ($updateStatus) {
            return response()->json(['status' => 'success', 'message' => 'Disposisi diterima']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gagal menerima disposisi']);
        }
    }

    // public function disposisi(Request $request, $disposisiId)
    // {
    //     $user = auth()->user();
    //     $keterangan = $request->input('keterangan');

    //     DB::beginTransaction();

    //     try {
    //         // Update the status and keterangan in disposisi_user
    //         $updateDisposition = DB::table('disposisi_user')
    //             ->where('disposisi_id', $disposisiId)
    //             ->where('user_id', $user->id)
    //             ->update([
    //                 'status_disposisi' => 'disposisi',
    //                 'keterangan' => $keterangan
    //             ]);

    //         if ($updateDisposition) {
    //             // Commit the transaction if the update is successful
    //             $allDisposisi = DB::table('disposisi_user')
    //                 ->where('disposisi_id', $disposisiId)
    //                 ->where('status_disposisi', 'disposisi')
    //                 ->count();

    //             $totalUsers = DB::table('disposisi_user')
    //                 ->where('disposisi_id', $disposisiId)
    //                 ->count();

    //             if ($allDisposisi === $totalUsers) {
    //                 // If all users have disposisi status, insert into disposisi table
    //                 DB::table('disposisi')->insert([
    //                     'catatan' => 'Disposisi ulang yang ke 2x',
    //                     'arsip_id' => $disposisiId,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
    //             }

    //             // Commit transaction
    //             DB::commit();
    //             return response()->json(['status' => 'success', 'message' => 'Disposisi berhasil di-update']);
    //         } else {
    //             // Roll back if no changes were made
    //             DB::rollBack();
    //             return response()->json(['status' => 'error', 'message' => 'Tidak ada perubahan yang dibuat']);
    //         }
    //     } catch (\Exception $e) {
    //         // Log and handle exceptions
    //         \Log::error('Exception during re-disposition operation: ' . $e->getMessage());
    //         DB::rollBack();
    //         return response()->json(['status' => 'error', 'message' => 'Gagal melakukan operasi disposisi']);
    //     }
    // }


    public function disposisi(Request $request, $disposisiId)
    {
        // debuging

        $user = auth()->user();
        $keterangan = $request->input('keterangan');

        DB::beginTransaction();

        try {
            // Update the status and keterangan in disposisi_user
            $updateDisposition = DB::table('disposisi_user')
                ->where('disposisi_id', $disposisiId)
                ->where('user_id', $user->id)
                ->update([
                    'status_disposisi' => 'disposisi',
                    'keterangan' => $keterangan
                ]);

            if ($updateDisposition) {
                // Check if all users related to this disposisi have been updated to 'disposisi'
                $allDisposisi = DB::table('disposisi_user')
                    ->where('disposisi_id', $disposisiId)
                    ->where('status_disposisi', 'disposisi')
                    ->count();

                $totalUsers = DB::table('disposisi_user')
                    ->where('disposisi_id', $disposisiId)
                    ->count();

                if ($allDisposisi === $totalUsers) {
                    // Fetch the related arsip_id for correct foreign key reference
                    $arsipId = Disposisi::where('id', $disposisiId)->first()->arsip_id;

                    // If all users have disposisi status, insert into disposisi table
                    DB::table('disposisi')->insert([
                        'catatan' => 'Disposisi ulang kembali',
                        'arsip_id' => $arsipId,  // Ensure this is the correct arsip_id
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // Commit transaction
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Disposisi berhasil di-update']);
            } else {
                // Roll back if no changes were made
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Tidak ada perubahan yang dibuat']);
            }
        } catch (\Exception $e) {
            // Log and handle exceptions
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal melakukan operasi disposisi: ' . $e->getMessage()]);
        }
    }
}
