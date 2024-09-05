<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->

        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="#">
            <div class="form-group mb-0 position-relative w-100">
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control" placeholder="Search" type="text" id="search-input">
                </div>
                <div id="search-results"></div>
            </div>
        </form>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <style>
                            .avatar .user-avatar {
                                width: 50px;
                                height: 48px;
                                border-radius: 50%;
                                object-fit: cover;
                                border: 2px solid #fff;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
                            }
                        </style>
                        <span class="avatar rounded-circle">
                            @php
                            $user = auth()->user();
                            $userPhoto = $user->foto ? 'storage/users/' . $user->foto : 'argon/img/theme/team-4-800x800.jpg';
                            @endphp
                            <img alt="User Image" src="{{ asset($userPhoto) }}" class="user-avatar">
                        </span>

                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>

                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

@push('js')
<script>
    $(document).ready(function() {
        var menus = @json($searchMenus);

        $('#search-input').on('keyup', function() {
            var query = $(this).val().toLowerCase();
            var searchResults = '';

            if (query.length >= 1) {
                var filteredMenus = Object.keys(menus).filter(function(menu) {
                    return menu.toLowerCase().indexOf(query) !== -1;
                });

                if (filteredMenus.length > 0) {
                    searchResults += '<ul class="dropdown-menu show search-results">';
                    filteredMenus.forEach(function(menu) {
                        var url = menus[menu];
                        searchResults += '<li><a class="dropdown-item" href="' + url + '">' + menu + '</a></li>';
                    });
                    searchResults += '</ul>';
                } else {
                    searchResults += '<ul class="dropdown-menu show search-results">';
                    searchResults += '<li><a class="dropdown-item no-result">Tidak ada hasil yang cocok.</a></li>';
                    searchResults += '</ul>';
                }
            }

            $('#search-results').html(searchResults);
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.navbar-search').length) {
                $('#search-results').html('');
            }
        });
    });
</script>

<style>
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        width: 100%;
        max-width: 300px;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 0.9rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
    }

    .search-results .dropdown-menu {
        display: block;
        width: 100%;
    }

    .search-results .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.5rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: normal;
        background-color: transparent;
        border: 0;
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
        border-bottom: 1px solid #e9ecef;
    }

    .search-results .dropdown-item:last-child {
        border-bottom: none;
    }


    .search-results .dropdown-item:hover,
    .search-results .dropdown-item:focus {
        color: #16181b;
        text-decoration: none;
        background-color: #c0c0c0;
        background-color: #e9ecef;
    }

    .search-results .dropdown-item.no-result {
        color: #6c757d;
        pointer-events: none;
        background-color: transparent;
    }
</style>

@endpush