<!-- [Page Specific JS] start -->
<script src="{{ url('') }}/assets/js/plugins/apexcharts.min.js"></script>
<script src="{{ url('') }}/assets/js/pages/dashboard-default.js"></script>
<!-- [Page Specific JS] end -->
<!-- Required Js -->
<script src="{{ url('') }}/assets/js/plugins/popper.min.js"></script>
<script src="{{ url('') }}/assets/js/plugins/simplebar.min.js"></script>
<script src="{{ url('') }}/assets/js/plugins/bootstrap.min.js"></script>
<script src="{{ url('') }}/assets/js/fonts/custom-font.js"></script>
<script src="{{ url('') }}/assets/js/pcoded.js"></script>
<script src="{{ url('') }}/assets/js/plugins/feather.min.js"></script>
<script>
    layout_change('light');
</script>
<script>
    change_box_container('false');
</script>
<script>
    layout_rtl_change('false');
</script>
<script>
    preset_change("preset-1");
</script>
<script>
    font_change("Public-Sans");
</script>

{{-- SweetAlert Notifikasi --}}
@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        })
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ $errors->first() }}'
        })
    </script>
@endif
