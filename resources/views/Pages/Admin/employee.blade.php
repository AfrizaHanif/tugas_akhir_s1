<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Karyawan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.employee')
@endsection

<!--MODALS-->
@section('modals')
@include('Templates.Includes.Components.Modal.employee')
@include('Pages.Includes.Components.Modal.employee')
@endsection

<!--TOASTS-->
@section('toasts')
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="exportToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Export File</strong>
            <small>Sekarang</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Export sedang diproses dan akan muncul di Download pada browser anda.
        </div>
    </div>
</div>
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Includes.Components.Offcanvas.employees')
@endsection

<!--SCRIPTS-->
@push('scripts')
<!--AUTO SWITCH TAB (PART)-->
<script>
    $(document).ready(function () {
        $('#parts-tab button[data-bs-target="#{{ old('tab_redirect') }}"]').trigger("click")
    });
</script>
<!--AUTO SWITCH TAB (TEAMS)-->
<script>
    $(document).ready(function () {
        $('#teams-tab button[data-bs-target="#{{ old('sub_tab_redirect') }}"]').trigger("click")
    });
</script>
<!--AUTO SWITCH TAB (VIEW TEAMS AND SUB TEAMS)-->
<script>
    $(document).ready(function () {
        $('#teams-modal-tab button[data-bs-target="#{{ old('modal_tab_redirect') }}"]').trigger("click")
    });
</script>
<script>
    const toastTrigger = document.getElementById('exportToastBtn')
    const toastLiveExample = document.getElementById('exportToast')

    if (toastTrigger) {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
        toastTrigger.addEventListener('click', () => {
            toastBootstrap.show()
        })
    }
</script>
@foreach ($employees as $employee)
<script>
    var $idposition{{ str_replace('-', '', $employee->id_employee) }} = $('#id_position_{{ $employee->id_employee }}');
    var $idsubteam1{{ str_replace('-', '', $employee->id_employee) }} = $('#id_sub_team_1_{{ $employee->id_employee }}');
    var $idsubteam2{{ str_replace('-', '', $employee->id_employee) }} = $('#id_sub_team_2_{{ $employee->id_employee }}');
    $idposition{{ str_replace('-', '', $employee->id_employee) }}.change(function() {
        if($idposition{{ str_replace('-', '', $employee->id_employee) }}.val() == 'POS-001' || $idposition{{ str_replace('-', '', $employee->id_employee) }}.val() == 'POS-002'){
            if($idposition{{ str_replace('-', '', $employee->id_employee) }}.val() == 'POS-001'){
                $idsubteam1{{ str_replace('-', '', $employee->id_employee) }}.attr('disabled', 'disabled').val('');
                $idsubteam2{{ str_replace('-', '', $employee->id_employee) }}.attr('disabled', 'disabled').val('');
            }else if($idposition{{ str_replace('-', '', $employee->id_employee) }}.val() == 'POS-002'){
                $idsubteam1{{ str_replace('-', '', $employee->id_employee) }}.attr('disabled', 'disabled').val('STM-002');
                $idsubteam2{{ str_replace('-', '', $employee->id_employee) }}.removeAttr('disabled');
            }
        }else{
            $idsubteam1{{ str_replace('-', '', $employee->id_employee) }}.removeAttr('disabled');
            $idsubteam2{{ str_replace('-', '', $employee->id_employee) }}.removeAttr('disabled');
        }
    }).trigger('change'); // added trigger to calculate initial state
</script>
@endforeach
<!--
<script>
    $("input[id='import_method_update']").change(function() {
        $("input[id='import_reset']").prop('disabled', true);
        $("input[id='import_reset']").prop('checked', false);
    });
    $("input[id='import_method_create']").change(function() {
        $("input[id='import_reset']").prop('disabled', false);
    });
    $("input[id='import_method_updcre']").change(function() {
        $("input[id='import_reset']").prop('disabled', true);
        $("input[id='import_reset']").prop('checked', false);
    });
</script>
-->
<!--
<script>
    document.getElementById("close-prt-create").addEventListener("click", function()
    {
        document.getElementById("form-prt-create").reset();
    });
</script>
<script>
    document.getElementById("close-dep-create").addEventListener("click", function()
    {
        document.getElementById("form-dep-create").reset();
    });
</script>
-->
@endpush
