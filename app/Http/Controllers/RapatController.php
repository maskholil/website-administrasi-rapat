<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\Agenda;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RapatController extends Controller
{
    public function index()
    {
        $rapat = Rapat::with('agenda', 'peserta')->get();
        $allAgendas = Agenda::all(); // Fetch all agendas for the edit modal
        $unusedAgendaIds = Rapat::pluck('agenda_id'); // This remains useful for filtering in creation scenarios
        $availableAgendas = Agenda::whereNotIn('id', $unusedAgendaIds)->get(); // For creating new rapats

        $absen = Peserta::all();
        return view('admin.rapat.index', compact('rapat', 'availableAgendas', 'allAgendas', 'absen'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'agenda_id' => 'required',
            'keputusan' => 'required',
            'file.*' => 'nullable|file|max:2048', // Mengizinkan semua jenis file dengan ukuran maksimal 2MB
        ], [
            'agenda_id.required' => 'Agenda rapat wajib diisi',
            'keputusan.required' => 'Keputusan rapat wajib diisi',
            'file.*.max' => 'Ukuran file maksimal 2MB'
        ]);

        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $filePaths = [];

            foreach ($files as $file) {
                $filePaths[] = $file->store('rapat', 'public');
            }

            $validatedData['file'] = implode(',', $filePaths);
        }

        $rapat = Rapat::create($validatedData);

        // Prepare attendees status
        $attendees = $request->input('peserta', []);
        $attendanceData = [];

        foreach ($attendees as $pesertaId => $status) {
            if ($status === 'hadir') {
                $attendanceData[$pesertaId] = ['status_kehadiran' => 'hadir']; // You can add more fields to the pivot table as needed
            }
        }

        $rapat->peserta()->sync($attendanceData); // Syncing only those marked as 'hadir'

        return redirect()->route(Auth::user()->role->nama_role . '.rapat.index')->with('success', 'Agenda Rapat berhasil ditambahkan.');
    }


    public function show($id)
    {
        $rapat = Rapat::with('agenda', 'peserta')->findOrFail($id);
        $peserta = $rapat->peserta->map(function ($p) {
            return $p->nama_peserta;
        })->implode(', ');

        if ($rapat->agenda) {
            $rapat->agenda->formatted_tanggal = \Carbon\Carbon::parse($rapat->agenda->tanggal)->isoFormat('dddd, D MMMM YYYY');
            $rapat->agenda->formatted_lokasi = ucfirst($rapat->agenda->lokasi);
        }

        return response()->json(['rapat' => $rapat, 'peserta' => $peserta]);
    }

    public function edit($id)
    {
        $rapat = Rapat::with('agenda', 'peserta')->findOrFail($id);
        $absen = Peserta::all();

        if ($rapat->agenda) {
            $rapat->agenda->formatted_tanggal = \Carbon\Carbon::parse($rapat->agenda->tanggal)->isoFormat('dddd, D MMMM YYYY');
            $rapat->agenda->formatted_lokasi = ucfirst($rapat->agenda->lokasi);
        }

        return response()->json(['rapat' => $rapat, 'absen' => $absen]);
    }

    public function update(Request $request, $id)
    {
        $rapat = Rapat::findOrFail($id);
        $validatedData = $request->validate([
            'agenda_id' => 'nullable',
            'keputusan' => 'required',
            'file.*' => 'nullable|file|max:2048',
        ], [

            'keputusan.required' => 'Keputusan rapat wajib diisi',
            'file.*.max' => 'Ukuran file maksimal 2MB'
        ]);

        // Mengambil file lama
        $oldFiles = $rapat->file ? explode(',', $rapat->file) : [];

        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $filePaths = [];

            foreach ($files as $file) {
                $filePaths[] = $file->store('rapat', 'public');
            }

            // Menggabungkan file lama dan file baru
            $validatedData['file'] = implode(',', array_merge($oldFiles, $filePaths));
        } else {
            $validatedData['file'] = implode(',', $oldFiles);
        }

        $rapat->update($validatedData);

        // Prepare attendees status
        $attendees = $request->input('peserta', []);
        $attendanceData = [];
        foreach ($attendees as $pesertaId => $status) {
            if ($status === 'hadir') {
                $attendanceData[$pesertaId] = ['status_kehadiran' => 'hadir'];
            }
        }
        $rapat->peserta()->sync($attendanceData); // Syncing only those marked as 'hadir'

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $rapat = Rapat::findOrFail($id);

        if ($rapat->file) {
            $files = explode(',', $rapat->file);
            foreach ($files as $file) {
                Storage::delete($file);
            }
        }

        $rapat->delete();

        return response()->json(['success' => true]);
    }



    public function lihatBeritaAcara($id)
    {
        $rapat = Rapat::with(['agenda', 'peserta' => function ($query) {
            $query->withPivot('status_kehadiran');
        }])->findOrFail($id);

        // You might want to pass more data to the view, as per your tables.
        return view('admin.rapat.isi-berita-acara', [
            'rapat' => $rapat,
            'hari' => $rapat->agenda->hari,
            'tanggal' => $rapat->agenda->tanggal,
            'bulan' => $rapat->agenda->bulan,
            'tahun' => $rapat->agenda->tahun,
            'dimulai' => $rapat->agenda->dimulai,
            'ditutup' => $rapat->agenda->ditutup,
            'dipimpin' => $rapat->agenda->pimpinan->name,
            'sekertaris' => $rapat->agenda->sekertaris,
            'peserta' => $rapat->peserta,
            'lokasi' => $rapat->agenda->lokasi,
            'no_agenda' => $rapat->agenda->no_agenda,
            'tentang' => $rapat->agenda->tentang,
            'tujuan' => $rapat->agenda->tujuan,
            'keputusan' => $rapat->keputusan
        ]);
    }

    public function tandaTangan(Request $request, $id)
    {
        $rapat = Rapat::findOrFail($id);
        $agenda = $rapat->agenda;

        // Check if the user signing is the secretary or the person leading the meeting
        $currentUser = auth()->user();
        $isSekretaris = $currentUser->hasRole('pegawai');
        $isDipimpin = $currentUser->id == $agenda->dipimpin;

        if (!$isSekretaris && !$isDipimpin) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak menandatangani rapat ini.'], 403);
        }

        // Save the signature to storage
        $folderPath = 'ttd/'; // Specify the folder path for signature storage
        $image_parts = explode(";base64,", $request->signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.' . $image_type;
        $file = $folderPath . $fileName;

        // Check and delete old signature file if exists before saving the new one
        if ($isSekretaris && $rapat->ttd_sekretaris) {
            Storage::disk('public')->delete($rapat->ttd_sekretaris);
            $rapat->ttd_sekretaris = null;
        } elseif ($isDipimpin && $rapat->ttd_dipimpin) {
            Storage::disk('public')->delete($rapat->ttd_dipimpin);
            $rapat->ttd_dipimpin = null;
        }

        // Update the meeting record with the path to the new signature based on the user's role
        Storage::disk('public')->put($file, $image_base64);
        if ($isSekretaris) {
            $rapat->ttd_sekretaris = $fileName;
        } elseif ($isDipimpin) {
            $rapat->ttd_dipimpin = $fileName;
        }

        // Update meeting status if both signatures (secretary and lead) are provided
        if ($rapat->ttd_sekretaris && $rapat->ttd_dipimpin) {
            $rapat->status_rapat = 'selesai';
        } elseif ($rapat->ttd_dipimpin) {
            $rapat->status_rapat = 'acc';
        }

        $rapat->save();

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil diperbarui.']);
    }

    public function revisi(Request $request, $id)
    {
        $rapat = Rapat::findOrFail($id);

        // Check permission...

        // Process the revision
        $catatanRevisi = $request->input('catatan');
        if ($catatanRevisi) {
            $rapat->catatan = $catatanRevisi;
            $rapat->status_rapat = 'revisi';
            $rapat->save();

            return response()->json(['success' => true, 'message' => 'Rapat berhasil direvisi.']);
        }

        return response()->json(['success' => false, 'message' => 'Catatan revisi tidak boleh kosong.'], 400);
    }

    // public function getPivotDetails($pivotId)
    // {
    //     $pivot = DB::table('arsip_user')->find($pivotId);
    //     if (!$pivot) {
    //         return response()->json(['error' => 'Pivot not found'], 404);
    //     }
    //     // Make sure to return the keterangan field in your response
    //     // dd($pivot);
    //     return response()->json([
    //         'id' => $pivot->id,
    //         'keterangan' => $pivot->keterangan
    //     ]);
    // }

    public function detailRevisi($id)
    {
        $rapat = Rapat::findOrFail($id);

        // Ensure that the current user is authorized to view the details.
        // You can use policies or a manual check.

        return response()->json(['catatan' => $rapat->catatan]);
    }
}
