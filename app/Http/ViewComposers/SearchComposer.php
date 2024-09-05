<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SearchComposer
{
    public function compose(View $view)
    {
        $searchMenus = $this->getMenus();
        $view->with('searchMenus', $searchMenus);
    }

    private function getMenus()
    {
        $menus = [];

        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                $menus = [
                    'Dashboard' => route('admin.dashboard'),
                    'Kelola Users' => route('users.index'),
                    'Kelola Kategori' => route('kategori.index'),
                    'Surat Masuk' => route('admin.surat-masuk.index'),
                    'Arsip Masuk' => route('admin.surat-masuk.index'),
                    'Surat Keluar' => route('admin.surat-keluar.index'),
                    'Arsip Keluar' => route('admin.surat-keluar.index'),
                    'Disposisi' => route('admin.disposisi.index'),
                    'Kelola Peserta' => route('admin.peserta.index'),
                    'Kelola Agenda' => route('admin.agenda.index'),
                    'Rapat Peserta' => route('admin.rapat.index'),
                    'Laporan' => route('laporan.index'),
                ];
            } elseif (Auth::user()->hasRole('pegawai')) {
                $menus = [
                    'Dashboard' => route('pegawai.dashboard'),
                    'Kelola Kategori' => route('pegawai.kategori.index'),
                    'Arsip Masuk' => route('pegawai.surat-masuk.index'),
                    'Arsip Keluar' => route('pegawai.surat-keluar.index'),
                    'Disposisi' => route('pegawai.disposisi.index'),
                    'Kelola Peserta' => route('pegawai.peserta.index'),
                    'Kelola Agenda' => route('pegawai.agenda.index'),
                    'Rapat Peserta' => route('pegawai.rapat.index'),
                    'Laporan' => route('pegawai.laporan.index'),
                ];
            } elseif (Auth::user()->hasRole('dekan')) {
                $menus = [
                    'Dashboard' => route('dekan.dashboard'),
                    'Arsip Masuk' => route('dekan.surat-masuk.index'),
                    'Arsip Keluar' => route('dekan.surat-keluar.index'),
                    'Disposisi' => route('dekan.disposisi.index'),
                    'Kelola Peserta' => route('dekan.peserta.index'),
                    'Agenda Rapat' => route('dekan.rapat.index'),
                    'Laporan' => route('dekan.laporan.index'),
                ];
            } elseif (Auth::user()->hasRole('kaprodi')) {
                $menus = [
                    'Dashboard' => route('kaprodi.dashboard'),
                    'Arsip Masuk' => route('kaprodi.surat-masuk.index'),
                    'Arsip Keluar' => route('kaprodi.surat-keluar.index'),
                    'Disposisi' => route('kaprodi.disposisi.index'),
                    'Kelola Peserta' => route('kaprodi.peserta.index'),
                    'Agenda Rapat' => route('kaprodi.rapat.index'),
                    'Laporan' => route('kaprodi.laporan.index'),
                ];
            } elseif (Auth::user()->hasRole('ketua')) {
                $menus = [
                    'Dashboard' => route('ketua.dashboard'),
                    'Kelola Peserta' => route('ketua.peserta.index'),
                    'Agenda Rapat' => route('ketua.rapat.index'),
                    'Laporan' => route('ketua.laporan.index'),
                ];
            }
             elseif (Auth::user()->hasRole('dosen')) {
                $menus = [
                    'Dashboard' => route('dosen.dashboard'),
                    'Kelola Peserta' => route('dosen.peserta.index'),
                    'Hasil Rapat' => route('dosen.rapat.index'),
                    'Agenda Rapat' => route('dosen.agenda.index'),
                ];
            }
        }

        return $menus;
    }
}
