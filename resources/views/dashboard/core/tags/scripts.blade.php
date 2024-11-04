<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset("plugins/jquery/jquery.min.js")}}"></script>
<!-- Bootstrap -->
<script src="{{asset("plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- AdminLTE -->
<script src="{{asset("js/adminlte.js")}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="{{asset("plugins/chart.js/Chart.min.js")}}"></script>
<script src="{{asset("js/demo.js")}}"></script>
<script src="{{asset("js/pages/dashboard3.js")}}"></script>
<script type="text/javascript" src="{{asset("js/datatables.js")}}"></script>
<script type="text/javascript" src="{{asset("js/datatables.min.js")}}"></script>

<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
        $('.select2').select2({
            language: {
                searching: function() {}
            },
        });
    });
</script>

@yield('js_addons')
