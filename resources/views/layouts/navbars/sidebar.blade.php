<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <div class="sidenav-header align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="{{ asset('assets/img/brand/siar.png') }}" class="custom-navbar-brand-img" alt="...">
            </a>
        </div>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <img src="{{ asset('argon') }}/img/brand/siar.png">
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
                <form class="mt-4 mb-3 d-md-none">
                    <div class="input-group input-group-rounded input-group-merge">
                        <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search" id="mobile-search-input">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fa fa-search"></span>
                            </div>
                        </div>
                    </div>
                    <div id="mobile-search-results" class="mt-2"></div>
                </form>

            </div>



            <!-- **************************************
                ░█████╗░██████╗░███╗░░░███╗██╗███╗░░██╗
                ██╔══██╗██╔══██╗████╗░████║██║████╗░██║
                ███████║██║░░██║██╔████╔██║██║██╔██╗██║
                ██╔══██║██║░░██║██║╚██╔╝██║██║██║╚████║
                ██║░░██║██████╔╝██║░╚═╝░██║██║██║░╚███║
                ╚═╝░░╚═╝╚═════╝░╚═╝░░░░░╚═╝╚═╝╚═╝░░╚══╝
                *************************************** -->

            @if (Auth::user()->hasRole('admin'))
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>

            </ul>
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Master Data</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="ni ni-single-02 text-primary"></i> {{ __('Kelola Users') }}
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                        <i class="fas fa-folder-open text-warning"></i> {{ __('Kelola Kategori') }}
                    </a>
                </li>
            </ul>


            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Administrasi</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.surat-masuk*') ? 'active' : '' }}" href="{{ route('admin.surat-masuk.index') }}">
                        <i class="ni ni-archive-2 text-success"></i> {{ __('Arsip Masuk') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.surat-keluar*') ? 'active' : '' }}" href="{{ route('admin.surat-keluar.index') }}">
                        <i class="ni ni-archive-2 text-danger"></i> {{ __('Arsip Keluar') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.disposisi*') ? 'active' : '' }}" href="{{ route('admin.disposisi.index') }}">
                        <i class="ni ni-paper-diploma text-warning"></i> {{ __('Disposisi') }}
                    </a>
                </li>
            </ul>


            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Rapat</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.peserta.*') ? 'active' : '' }}" href="{{ route('admin.peserta.index') }}">
                        <i class="ni ni-single-02 text-pink"></i> {{ __('Kelola Peserta') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.agenda.*') ? 'active' : '' }}" href="{{ route('admin.agenda.index') }}">
                        <i class="ni ni-calendar-grid-58 text-success"></i> {{ __('Kelola Agenda') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.rapat.*') ? 'active' : '' }}" href="{{ route('admin.rapat.index') }}">
                        <i class="ni ni-bullet-list-67 text-info"></i> {{ __('Hasil Rapat') }}
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Laporan</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                        <i class="ni ni-chart-bar-32 text-blue"></i> {{ __('Laporan') }}
                    </a>
                </li>
            </ul>


            <!-- ******************************************************
            ██████╗░███████╗░██████╗░░█████╗░░██╗░░░░░░░██╗░█████╗░██╗
            ██╔══██╗██╔════╝██╔════╝░██╔══██╗░██║░░██╗░░██║██╔══██╗██║
            ██████╔╝█████╗░░██║░░██╗░███████║░╚██╗████╗██╔╝███████║██║
            ██╔═══╝░██╔══╝░░██║░░╚██╗██╔══██║░░████╔═████║░██╔══██║██║
            ██║░░░░░███████╗╚██████╔╝██║░░██║░░╚██╔╝░╚██╔╝░██║░░██║██║
            ╚═╝░░░░░╚══════╝░╚═════╝░╚═╝░░╚═╝░░░╚═╝░░░╚═╝░░╚═╝░░╚═╝╚═╝
            *********************************************************** -->

            @elseif (Auth::user()->hasRole('pegawai'))
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai*') ? 'active' : '' }}" href="{{ route('pegawai.dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Master Data</h6>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.kategori*') ? 'active' : '' }}" href="{{ route('pegawai.kategori.index') }}">
                        <i class="fas fa-folder-open text-warning"></i> {{ __('Kelola Kategori') }}
                    </a>
                </li>
            </ul>


            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Administrasi</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.surat-masuk*') ? 'active' : '' }}" href="{{ route('pegawai.surat-masuk.index') }}">
                        <i class="ni ni-archive-2 text-success"></i> {{ __('Arsip Masuk') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.surat-keluar*') ? 'active' : '' }}" href="{{ route('pegawai.surat-keluar.index') }}">
                        <i class="ni ni-archive-2 text-danger"></i> {{ __('Arsip Keluar') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.disposisi*') ? 'active' : '' }}" href="{{ route('pegawai.disposisi.index') }}">
                        <i class="ni ni-paper-diploma text-warning"></i> {{ __('Disposisi') }}
                    </a>
                </li>
            </ul>


            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Rapat</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.peserta.*') ? 'active' : '' }}" href="{{ route('pegawai.peserta.index') }}">
                        <i class="ni ni-single-02 text-pink"></i> {{ __('Kelola Peserta') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.agenda.*') ? 'active' : '' }}" href="{{ route('pegawai.agenda.index') }}">
                        <i class="ni ni-calendar-grid-58 text-success"></i> {{ __('Kelola Agenda') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.rapat.*') ? 'active' : '' }}" href="{{ route('pegawai.rapat.index') }}">
                        <i class="ni ni-bullet-list-67 text-info"></i> {{ __('Hasil Rapat') }}
                    </a>
                </li>


            </ul>

            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Laporan</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('pegawai.laporan.*') ? 'active' : '' }}" href="{{ route('pegawai.laporan.index') }}">
                        <i class="ni ni-chart-bar-32 text-blue"></i> {{ __('Laporan') }}
                    </a>
                </li>
            </ul>

            <!-- ****************************************
                ██████╗░███████╗██╗░░██╗░█████╗░███╗░░██╗
                ██╔══██╗██╔════╝██║░██╔╝██╔══██╗████╗░██║
                ██║░░██║█████╗░░█████═╝░███████║██╔██╗██║
                ██║░░██║██╔══╝░░██╔═██╗░██╔══██║██║╚████║
                ██████╔╝███████╗██║░╚██╗██║░░██║██║░╚███║
                ╚═════╝░╚══════╝╚═╝░░╚═╝╚═╝░░╚═╝╚═╝░░╚══╝
               ****************************************** -->

            @elseif (Auth::user()->hasRole('dekan'))
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dekan.dekan*') ? 'active' : '' }}" href="{{ route('dekan.dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Administrasi</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dekan.surat-masuk*') ? 'active' : '' }}" href="{{ route('dekan.surat-masuk.index') }}">
                        <i class="ni ni-archive-2 text-success"></i> {{ __('Arsip Masuk') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dekan.surat-keluar*') ? 'active' : '' }}" href="{{ route('dekan.surat-keluar.index') }}">
                        <i class="ni ni-archive-2 text-danger"></i> {{ __('Arsip Keluar') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dekan.disposisi*') ? 'active' : '' }}" href="{{ route('dekan.disposisi.index') }}">
                        <i class="ni ni-paper-diploma text-warning"></i> {{ __('Disposisi') }}
                    </a>
                </li>
            </ul>


            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Rapat</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dekan.peserta.*') ? 'active' : '' }}" href="{{ route('dekan.peserta.index') }}">
                        <i class="ni ni-single-02 text-pink"></i> {{ __('Kelola Peserta') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dekan.rapat.*') ? 'active' : '' }}" href="{{ route('dekan.rapat.index') }}">
                        <i class="ni ni-calendar-grid-58 text-info"></i> {{ __('Hasil Rapat') }}
                    </a>
                </li>


            </ul>


            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Laporan</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dekan.laporan.*') ? 'active' : '' }}" href="{{ route('dekan.laporan.index') }}">
                        <i class="ni ni-chart-bar-32 text-blue"></i> {{ __('Laporan') }}
                    </a>
                </li>
            </ul>

            <!-- **********************************************
            ██╗░░██╗░█████╗░██████╗░██████╗░░█████╗░██████╗░██╗
            ██║░██╔╝██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔══██╗██║
            █████═╝░███████║██████╔╝██████╔╝██║░░██║██║░░██║██║
            ██╔═██╗░██╔══██║██╔═══╝░██╔══██╗██║░░██║██║░░██║██║
            ██║░╚██╗██║░░██║██║░░░░░██║░░██║╚█████╔╝██████╔╝██║
            ╚═╝░░╚═╝╚═╝░░╚═╝╚═╝░░░░░╚═╝░░╚═╝░╚════╝░╚═════╝░╚═╝
            *************************************************** -->

            @elseif (Auth::user()->hasRole('kaprodi'))
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.dashboard*') ? 'active' : '' }}" href="{{ route('kaprodi.dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Administrasi</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.surat-masuk*') ? 'active' : '' }}" href="{{ route('kaprodi.surat-masuk.index') }}">
                        <i class="ni ni-archive-2 text-success"></i> {{ __('Arsip Masuk') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.surat-keluar*') ? 'active' : '' }}" href="{{ route('kaprodi.surat-keluar.index') }}">
                        <i class="ni ni-archive-2 text-danger"></i> {{ __('Arsip Keluar') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.disposisi*') ? 'active' : '' }}" href="{{ route('kaprodi.disposisi.index') }}">
                        <i class="ni ni-paper-diploma text-warning"></i> {{ __('Disposisi') }}
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Rapat</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.peserta*') ? 'active' : '' }}" href="{{ route('kaprodi.peserta.index') }}">
                        <i class="ni ni-single-02 text-pink"></i> {{ __('Kelola Peserta') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.rapat*') ? 'active' : '' }}" href="{{ route('kaprodi.rapat.index') }}">
                        <i class="ni ni-calendar-grid-58 text-info"></i> {{ __('Hasil Rapat') }}
                    </a>
                </li>
            </ul>


            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Laporan</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.laporan*') ? 'active' : '' }}" href="{{ route('kaprodi.laporan.index') }}">
                        <i class="ni ni-chart-bar-32 text-blue"></i> {{ __('Laporan') }}
                    </a>
                </li>
            </ul>

            <!-- ****************************************
                ██╗░░██╗███████╗████████╗██╗░░░██╗░█████╗░
                ██║░██╔╝██╔════╝╚══██╔══╝██║░░░██║██╔══██╗
                █████═╝░█████╗░░░░░██║░░░██║░░░██║███████║
                ██╔═██╗░██╔══╝░░░░░██║░░░██║░░░██║██╔══██║
                ██║░╚██╗███████╗░░░██║░░░╚██████╔╝██║░░██║
                ╚═╝░░╚═╝╚══════╝░░░╚═╝░░░░╚═════╝░╚═╝░░╚═╝
                ****************************************** -->

            @elseif (Auth::user()->hasRole('ketua'))
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('ketua.dashboard*') ? 'active' : '' }}" href="{{ route('ketua.dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Rapat</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('ketua.peserta*') ? 'active' : '' }}" href="{{ route('ketua.peserta.index') }}">
                        <i class="ni ni-single-02 text-pink"></i> {{ __('Kelola Peserta') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('ketua.rapat*') ? 'active' : '' }}" href="{{ route('ketua.rapat.index') }}">
                        <i class="ni ni-calendar-grid-58 text-info"></i> {{ __('Hasil Rapat') }}
                    </a>
                </li>

            </ul>
            <hr class="my-2">
            <h6 class="navbar-heading text-muted">Laporan</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('ketua.laporan*') ? 'active' : '' }}" href="{{ route('ketua.laporan.index') }}">
                        <i class="ni ni-chart-bar-32 text-blue"></i> {{ __('Laporan') }}
                    </a>
                </li>
            </ul>

            @elseif (Auth::user()->hasRole('dosen'))
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dosen.dashboard*') ? 'active' : '' }}" href="{{ route('dosen.dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-2">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Rapat</h6>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dosen.peserta*') ? 'active' : '' }}" href="{{ route('dosen.peserta.index') }}">
                        <i class="ni ni-single-02 text-pink"></i> {{ __('Kelola Peserta') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dosen.agenda.*') ? 'active' : '' }}" href="{{ route('dosen.agenda.index') }}">
                        <i class="ni ni-calendar-grid-58 text-success"></i> {{ __('Agenda Rapat') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dosen.rapat*') ? 'active' : '' }}" href="{{ route('dosen.rapat.index') }}">
                        <i class="ni ni-calendar-grid-58 text-info"></i> {{ __('Hasil Rapat') }}
                    </a>
                </li>

            </ul>
       

            @endif

            </ul>
            <hr class="my-2 d-md-none">
            <ul class="navbar-nav d-md-none">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run text-red"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('js')
<script>
    $(document).ready(function() {
        var menus = @json($searchMenus);

        $('#mobile-search-input').on('keyup', function() {
            var query = $(this).val().toLowerCase();
            var searchResults = '';

            if (query.length >= 1) {
                var filteredMenus = Object.keys(menus).filter(function(menu) {
                    return menu.toLowerCase().indexOf(query) !== -1;
                });

                if (filteredMenus.length > 0) {
                    searchResults += '<ul class="list-group">';
                    filteredMenus.forEach(function(menu) {
                        var url = menus[menu];
                        searchResults += '<li class="list-group-item"><a href="' + url + '">' + menu + '</a></li>';
                    });
                    searchResults += '</ul>';
                } else {
                    searchResults += '<ul class="list-group">';
                    searchResults += '<li class="list-group-item no-result">Tidak ada hasil yang cocok.</li>';
                    searchResults += '</ul>';
                }
            }

            $('#mobile-search-results').html(searchResults);
        });
    });
</script>

<style>
    @media (min-width: 768px) {

        .my-2 .d-md-none,
        .navbar-nav.d-md-none {
            display: none !important;
        }
</style>

@endpush
