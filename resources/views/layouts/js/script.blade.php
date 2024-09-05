    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('argon/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Argon JS -->
    <script src="{{ asset('argon/js/argon.js?v=1.0.0') }}"></script>

    @if(session('success'))
    <script>
        swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            button: "OK",
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        swal.fire({
            title: "Error!",
            text: "{{ $errors->first() }}",
            icon: "error",
            button: "OK",
        });
    </script>
    @endif
