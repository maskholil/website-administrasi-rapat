<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Arsip;
use App\Models\Disposisi;
use App\Models\Rapat;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $arsip = Arsip::with('user', 'kategori')->get();
        return view('admin.laporan.index', compact('arsip'));
    }

    public function cetakArsip(Request $request)
    {
        $startDate = $request->input('startDate', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('endDate', Carbon::now()->toDateString());
        $archiveType = $request->input('archiveType', 'all');

        $query = Arsip::with(['kategori', 'tujuanUsers']) // Include tujuanUsers for eager loading
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($archiveType == 'masuk') {
            $query->where('jenis_arsip', 'masuk');
        } elseif ($archiveType == 'keluar') {
            $query->where('jenis_arsip', 'keluar');
        }

        $arsip = $query->get();
        $totalRows = $arsip->count();

        return view('admin.laporan.laporan-arsip', compact('arsip', 'startDate', 'endDate', 'archiveType', 'totalRows'));
    }

    public function cetakDisposisi(Request $request)
    {
        $startDate = $request->input('startDate', now()->startOfMonth()->toDateString());
        $endDate = $request->input('endDate', now()->toDateString());


        $arsip = Arsip::with(['disposisi.tujuanUsers'])->whereHas('disposisi', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->get();


        $totalRows = $arsip->flatMap(function ($arsip) {
            return $arsip->disposisi;
        })->count();

        return view('admin.laporan.laporan-disposisi', compact('arsip', 'startDate', 'endDate', 'totalRows'));
    }


    public function cetakGabungan(Request $request)
    {
        $startDate = $request->input('startDate', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('endDate', Carbon::now()->toDateString());

        $arsip = Arsip::with('disposisi')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRows = $arsip->count();

        return view('admin.laporan.laporan-gabungan', compact('arsip', 'startDate', 'endDate', 'totalRows'));
    }

    public function cetakRapat(Request $request)
    {
        $startDate = $request->input('startDate', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('endDate', Carbon::now()->toDateString());

        $rapat = Rapat::whereBetween('created_at', [$startDate, $endDate])->with('agenda')->get();

        $totalRows = $rapat->count();

        return view('admin.laporan.laporan-rapat', compact('rapat', 'startDate', 'endDate', 'totalRows'));
    }
}
