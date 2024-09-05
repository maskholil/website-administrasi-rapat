<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Rapat;
use App\Models\Agenda;
use App\Models\Peserta;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Mendapatkan total users
        $totalUsers = User::count();

        // Mendapatkan total peserta
        $totalPeserta = Peserta::count();

        // Mendapatkan total surat masuk
        $totalSuratMasuk = Arsip::where('jenis_arsip', 'masuk')->count();

        // Mendapatkan total surat keluar
        $totalSuratKeluar = Arsip::where('jenis_arsip', 'keluar')->count();

        // Mendapatkan jumlah surat masuk bulan ini
        $suratMasukBulanIni = Arsip::where('jenis_arsip', 'masuk')->whereMonth('created_at', date('m'))->count();

        // Mendapatkan jumlah surat masuk bulan lalu
        $suratMasukBulanLalu = Arsip::where('jenis_arsip', 'masuk')->whereMonth('created_at', date('m', strtotime('-1 month')))->count();

        // Menghitung persentase peningkatan surat masuk
        $suratMasukPercentage = $this->calculatePercentage($suratMasukBulanIni, $suratMasukBulanLalu);

        // Mendapatkan jumlah surat keluar bulan ini
        $suratKeluarBulanIni = Arsip::where('jenis_arsip', 'keluar')->whereMonth('created_at', date('m'))->count();

        // Mendapatkan jumlah surat keluar bulan lalu
        $suratKeluarBulanLalu = Arsip::where('jenis_arsip', 'keluar')->whereMonth('created_at', date('m', strtotime('-1 month')))->count();

        // Menghitung persentase peningkatan surat keluar
        $suratKeluarPercentage = $this->calculatePercentage($suratKeluarBulanIni, $suratKeluarBulanLalu);

        // Mendapatkan data users terbaru
        $users = User::orderBy('created_at', 'desc')->take(5)->get();

        // Mendapatkan data arsip terbaru (gabungan surat masuk dan keluar)
        $arsip = Arsip::with('kategori')->orderBy('created_at', 'desc')->take(5)->get();

        // Mendapatkan data agenda terbaru
        $agenda = Agenda::orderBy('tanggal', 'desc')->take(5)->get();

        $totalAgenda = Agenda::count();

        // Mendapatkan jumlah agenda bulan ini
        $agendaBulanIni = Agenda::whereMonth('tanggal', date('m'))->count();

        // Mendapatkan jumlah agenda bulan lalu
        $agendaBulanLalu = Agenda::whereMonth('tanggal', date('m', strtotime('-1 month')))->count();

        // Menghitung persentase peningkatan agenda
        $agendaPercentage = $this->calculatePercentage($agendaBulanIni, $agendaBulanLalu);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSuratMasuk',
            'totalSuratKeluar',
            'suratMasukPercentage',
            'suratKeluarPercentage',
            'totalAgenda',
            'agendaPercentage',
            'users',
            'arsip',
            'totalPeserta',
            'agenda'
        ));
    }

    private function calculatePercentage($currentMonth, $previousMonth)
    {
        if ($previousMonth == 0) {
            return 0;
        }
        return round(($currentMonth - $previousMonth) / $previousMonth * 100, 2);
    }

    public function pegawaiDashboard()
    {
        $pegawaiId = auth()->user()->id;
        $disposisi = Disposisi::whereHas('tujuanUsers', function ($query) use ($pegawaiId) {
            $query->where('user_id', $pegawaiId);
        })
            ->orderBy('created_at', 'desc')
            ->with('arsip')
            ->take(5)
            ->get();

        // Mendapatkan total users
        $totalUsers = User::count();

        // Mendapatkan total peserta
        $totalPeserta = Peserta::count();

        // Mendapatkan total surat masuk
        $totalSuratMasuk = Arsip::where('jenis_arsip', 'masuk')->count();

        // Mendapatkan total surat keluar
        $totalSuratKeluar = Arsip::where('jenis_arsip', 'keluar')->count();

        // Mendapatkan jumlah surat masuk bulan ini
        $suratMasukBulanIni = Arsip::where('jenis_arsip', 'masuk')->whereMonth('created_at', date('m'))->count();

        // Mendapatkan jumlah surat masuk bulan lalu
        $suratMasukBulanLalu = Arsip::where('jenis_arsip', 'masuk')->whereMonth('created_at', date('m', strtotime('-1 month')))->count();

        // Menghitung persentase peningkatan surat masuk
        $suratMasukPercentage = $this->calculatePercentage($suratMasukBulanIni, $suratMasukBulanLalu);

        // Mendapatkan jumlah surat keluar bulan ini
        $suratKeluarBulanIni = Arsip::where('jenis_arsip', 'keluar')->whereMonth('created_at', date('m'))->count();

        // Mendapatkan jumlah surat keluar bulan lalu
        $suratKeluarBulanLalu = Arsip::where('jenis_arsip', 'keluar')->whereMonth('created_at', date('m', strtotime('-1 month')))->count();

        // Menghitung persentase peningkatan surat keluar
        $suratKeluarPercentage = $this->calculatePercentage($suratKeluarBulanIni, $suratKeluarBulanLalu);

        // Mendapatkan data users terbaru
        $users = User::orderBy('created_at', 'desc')->take(5)->get();

        // Mendapatkan data arsip terbaru (gabungan surat masuk dan keluar)
        $arsip = Arsip::with('kategori')->orderBy('created_at', 'desc')->take(5)->get();

        // Mendapatkan data agenda terbaru
        $agenda = Agenda::orderBy('tanggal', 'desc')->take(5)->get();

        $totalAgenda = Agenda::count();

        // Mendapatkan jumlah agenda bulan ini
        $agendaBulanIni = Agenda::whereMonth('tanggal', date('m'))->count();

        // Mendapatkan jumlah agenda bulan lalu
        $agendaBulanLalu = Agenda::whereMonth('tanggal', date('m', strtotime('-1 month')))->count();

        // Menghitung persentase peningkatan agenda
        $agendaPercentage = $this->calculatePercentage($agendaBulanIni, $agendaBulanLalu);

        return view('pegawai.dashboard', compact(
            'disposisi',
            'totalUsers',
            'totalSuratMasuk',
            'totalSuratKeluar',
            'suratMasukPercentage',
            'suratKeluarPercentage',
            'totalAgenda',
            'agendaPercentage',
            'users',
            'arsip',
            'totalPeserta',
            'agenda'
        ));
    }

    public function dekanDashboard()
    {
        // Mendapatkan ID dekan yang sedang login
        $dekanId = auth()->user()->id;

        // Mendapatkan total surat masuk yang tertuju pada dekan
        $totalSuratMasuk = Arsip::where('jenis_arsip', 'masuk')
            ->whereHas('tujuanUsers', function ($query) use ($dekanId) {
                $query->where('user_id', $dekanId);
            })->count();

        // Mendapatkan total disposisi yang tertuju pada dekan
        $totalDisposisi = Disposisi::whereHas('tujuanUsers', function ($query) use ($dekanId) {
            $query->where('user_id', $dekanId);
        })->count();

        // Mendapatkan total agenda
        $totalAgenda = Agenda::count();

        // Mendapatkan data surat masuk terbaru yang tertuju pada dekan
        $suratMasuk = Arsip::where('jenis_arsip', 'masuk')
            ->whereHas('tujuanUsers', function ($query) use ($dekanId) {
                $query->where('user_id', $dekanId);
            })
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Mendapatkan data agenda terbaru
        $agenda = Agenda::orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        $totalBelumDiproses = Arsip::where('jenis_arsip', 'masuk')
            ->whereHas('tujuanUsers', function ($query) use ($dekanId) {
                $query->where('user_id', $dekanId)
                    ->where('status_masuk', 'diproses')
                    ->whereNull('keterangan');
            })->count();

        $disposisi = Disposisi::whereHas('tujuanUsers', function ($query) use ($dekanId) {
            $query->where('user_id', $dekanId);
        })
            ->orderBy('created_at', 'desc')
            ->with('arsip')
            ->take(5)
            ->get();

        return view('dekan.dashboard', compact(
            'totalSuratMasuk',
            'totalDisposisi',
            'totalAgenda',
            'suratMasuk',
            'agenda',
            'totalBelumDiproses',
            'disposisi'
        ));
    }
    public function kaprodiDashboard()
    {
        // Mendapatkan ID kaprodi yang sedang login
        $kaprodiId = auth()->user()->id;

        // Mendapatkan total surat masuk yang tertuju pada kaprodi
        $totalSuratMasuk = Arsip::where('jenis_arsip', 'masuk')
            ->whereHas('tujuanUsers', function ($query) use ($kaprodiId) {
                $query->where('user_id', $kaprodiId);
            })->count();

        // Mendapatkan total disposisi yang tertuju pada kaprodi
        $totalDisposisi = Disposisi::whereHas('tujuanUsers', function ($query) use ($kaprodiId) {
            $query->where('user_id', $kaprodiId);
        })->count();

        // Mendapatkan total agenda
        $totalAgenda = Agenda::count();

        // Mendapatkan data surat masuk terbaru yang tertuju pada kaprodi
        $suratMasuk = Arsip::where('jenis_arsip', 'masuk')
            ->whereHas('tujuanUsers', function ($query) use ($kaprodiId) {
                $query->where('user_id', $kaprodiId);
            })
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Mendapatkan data agenda terbaru
        $agenda = Agenda::orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        $totalBelumDiproses = Arsip::where('jenis_arsip', 'masuk')
            ->whereHas('tujuanUsers', function ($query) use ($kaprodiId) {
                $query->where('user_id', $kaprodiId)
                    ->where('status_masuk', 'diproses')
                    ->whereNull('keterangan');
            })->count();

        $disposisi =  Disposisi::whereHas('tujuanUsers', function ($query) use ($kaprodiId) {
            $query->where('user_id', $kaprodiId);
        })
            ->orderBy('created_at', 'desc')
            ->with('arsip')
            ->take(5)
            ->get();


        return view('kaprodi.dashboard', compact(
            'totalSuratMasuk',
            'totalDisposisi',
            'totalAgenda',
            'suratMasuk',
            'agenda',
            'totalBelumDiproses',
            'disposisi'
        ));
    }

    public function ketuaDashboard()
    {
        // Mendapatkan total agenda
        $totalAgenda = Agenda::count();
        // Mendapatkan total peserta
        $totalPeserta = Peserta::count();
        // Mendapatkan  peserta
        $peserta = Peserta::take(5)->get();

        // Mendapatkan data agenda terbaru
        $agenda = Agenda::orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return view('ketua.dashboard', compact(
            'totalAgenda',
            'agenda',
            'totalPeserta',
            'peserta'
        ));
    }
    public function dosenDashboard()
    {
        // Mendapatkan total agenda
        $totalAgenda = Agenda::count();
        // Mendapatkan total peserta
        $totalPeserta = Peserta::count();
        // Mendapatkan  peserta
        $peserta = Peserta::take(5)->get();

        // Mendapatkan data agenda terbaru
        $agenda = Agenda::orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'totalAgenda',
            'agenda',
            'totalPeserta',
            'peserta'
        ));
    }
}
