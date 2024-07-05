<script src="{{asset('Sources/JS/csrf.js')}}"></script>

@if (Session::get('modal_redirect') == 'modal-prt-create')
<script>
    $(function() {
        $('#modal-prt-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-prt-update')
<script>
    $(function() {
        $('#modal-prt-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-off-create')
<script>
    $(function() {
        $('#modal-off-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-off-update')
<script>
    $(function() {
        $('#modal-off-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-dep-view')
<script>
    $(function() {
        $('#modal-dep-view').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-dep-create')
<script>
    $(function() {
        $('#modal-dep-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-dep-update')
<script>
    $(function() {
        $('#modal-dep-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-usr-create')
<script>
    $(function() {
        $('#modal-usr-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-usr-update')
<script>
    $(function() {
        $('#modal-usr-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-crt-create')
<script>
    $(function() {
        $('#modal-crt-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-crt-update')
<script>
    $(function() {
        $('#modal-crt-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-sub-create')
<script>
    $(function() {
        $('#modal-sub-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-sub-update')
<script>
    $(function() {
        $('#modal-sub-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-vcr-create')
<script>
    $(function() {
        $('#modal-vcr-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-vcr-update')
<script>
    $(function() {
        $('#modal-vcr-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-per-create')
<script>
    $(function() {
        $('#modal-per-create').modal('show');
    });
</script>
@endif
