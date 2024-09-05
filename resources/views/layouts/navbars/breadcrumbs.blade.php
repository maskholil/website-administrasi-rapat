<!-- breadcrumbs.blade.php -->
@if(View::hasSection('breadcrumb'))
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7" style="margin-top: 75px;">
                    @yield('breadcrumb')
                </div>
            </div>
        </div>
    </div>
</div>
@endif