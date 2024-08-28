<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Kriteria | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.criteria')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.Modal.criteria')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Admin.Includes.Components.Offcanvas.criteria')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script>
    $(document).ready(function () {
        $('#v-pills-tab button[data-bs-target="#{{ old('tab_redirect') }}"]').trigger("click")
    });
</script>
<!--
<script>
    document.getElementById("close-crt-create").addEventListener("click", function()
    {
        document.getElementById("form-crt-create").reset();
    });
</script>
-->
@foreach ($criterias as $criteria)
<script>
    var $valuetype{{ str_replace('-', '', $criteria->id_criteria) }} = $('#value_type_{{ $criteria->id_criteria }}'), $valueto{{ str_replace('-', '', $criteria->id_criteria) }} = $('#value_to_{{ $criteria->id_criteria }}');
    $valuetype{{ str_replace('-', '', $criteria->id_criteria) }}.change(function() {
        if($valuetype{{ str_replace('-', '', $criteria->id_criteria) }}.val() == 'Between'){
            $valueto{{ str_replace('-', '', $criteria->id_criteria) }}.removeAttr('disabled');
        }else{
            $valueto{{ str_replace('-', '', $criteria->id_criteria) }}.attr('disabled', 'disabled').val('');
        }
    }).trigger('change'); // added trigger to calculate initial state
</script>
@endforeach
@foreach ($crips as $crip)
<script>
    var $valuetype{{ str_replace('-', '', $crip->id_crips) }} = $('#value_type_{{ $crip->id_crips }}'), $valueto{{ str_replace('-', '', $crip->id_crips) }} = $('#value_to_{{ $crip->id_crips }}');
    $valuetype{{ str_replace('-', '', $crip->id_crips) }}.change(function() {
        if($valuetype{{ str_replace('-', '', $crip->id_crips) }}.val() == 'Between'){
            $valueto{{ str_replace('-', '', $crip->id_crips) }}.removeAttr('disabled');
        }else{
            $valueto{{ str_replace('-', '', $crip->id_crips) }}.attr('disabled', 'disabled').val('');
        }
    }).trigger('change'); // added trigger to calculate initial state
</script>
@endforeach
@endpush
