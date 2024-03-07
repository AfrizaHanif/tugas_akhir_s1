<!--TEMPLATE-->
@extends('Templates.Home.template')

<!--TITLE-->
@section('title')
<title>Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Home.Includes.Contents.officer')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Home.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script type="text/javascript">
    var path = "{{ route('json.autocomplete') }}";
    $('input.typeahead').typeahead({
        source: function (query, process) {
        return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        }
    });
</script>
@include('Pages.Home.Includes.Scripts.js')
@endpush
