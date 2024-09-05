<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $agenda = Agenda::with('pimpinan')->get();
        $pimpinan = User::whereHas('role', function ($query) {
            $query->where('nama_role', 'dekan')
                ->orWhere('nama_role', 'kaprodi')
                ->orWhere('nama_role', 'ketua');
        })->get();
        return view('admin.agenda.index', compact('agenda', 'pimpinan'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_agenda' => 'required|string|max:255',
            'tentang' => 'required|string|max:1000',
            'hari' => 'required|string|max:10',
            'tanggal' => 'required|date',
            'bulan' => 'required|string|max:15',
            'tahun' => 'required|digits:4',
            'lokasi' => 'required|string|max:255',
            'dimulai' => 'required|date_format:H:i',
            'ditutup' => 'required|date_format:H:i',
            'dipimpin' => 'required|integer',
            'sekertaris' => 'required',
            'tujuan' => 'required|string|max:1000',
        ]);

        try {
            \DB::beginTransaction();
            Agenda::create($validatedData);
            \DB::commit();
            return redirect()->route(Auth::user()->role->nama_role .'.agenda.index')->with('success', 'Agenda berhasil ditambahkan.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return response()->json(['agenda' => $agenda]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'no_agenda' => 'required|string|max:255',
            'tentang' => 'required|string|max:1000',
            'hari' => 'required|string|max:10',
            'tanggal' => 'required|date',
            'bulan' => 'required|string|max:15',
            'tahun' => 'required|digits:4',
            'lokasi' => 'required|string|max:255',
            'dimulai' => 'required|date_format:H:i',
            'ditutup' => 'required|date_format:H:i',
            'dipimpin' => 'required|integer',
            'sekertaris' => 'required',
            'tujuan' => 'required|string|max:1000',
        ]);

        try {
            \DB::beginTransaction();
            $agenda = Agenda::findOrFail($id);
            $agenda->update($validatedData);
            \DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();

        return response()->json(['success' => true]);
    }
}
