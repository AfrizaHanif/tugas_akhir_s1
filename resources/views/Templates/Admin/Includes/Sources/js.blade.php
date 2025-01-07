<script src="{{asset('Sources/JS/csrf.js')}}"></script>

@if (Session::get('modal_redirect') == 'modal-dsh-first')
<script>
    $(function() {
        $('#modal-dsh-first').modal('show');
    });
</script>
@endif

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

@if (Session::get('modal_redirect') == 'modal-emp-import')
<script>
    $(function() {
        $('#modal-emp-import').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-emp-create')
<script>
    $(function() {
        $('#modal-emp-create-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-emp-update')
<script>
    $(function() {
        $('#modal-emp-update-{{ Session::get('id_redirect') }}').modal('show');
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

@if (Session::get('modal_redirect') == 'modal-tim-view')
<script>
    $(function() {
        $('#modal-tim-view-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-tim-create')
<script>
    $(function() {
        $('#modal-tim-create-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-tim-update')
<script>
    $(function() {
        $('#modal-tim-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-stm-create')
<script>
    $(function() {
        $('#modal-stm-create-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-stm-update')
<script>
    $(function() {
        $('#modal-stm-update-{{ Session::get('id_redirect') }}').modal('show');
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

@if (Session::get('modal_redirect') == 'modal-cat-create')
<script>
    $(function() {
        $('#modal-cat-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-cat-update')
<script>
    $(function() {
        $('#modal-cat-update-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-crt-create')
<script>
    $(function() {
        $('#modal-crt-create-{{ Session::get('id_redirect') }}').modal('show');
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

@if (Session::get('modal_redirect') == 'modal-per-create')
<script>
    $(function() {
        $('#modal-per-create').modal('show');
    });
</script>
@endif

@if (Session::get('modal_redirect') == 'modal-crp-view')
<script>
    $(function() {
        $('#modal-crp-view-{{ Session::get('id_redirect') }}').modal('show');
    });
</script>
@endif
