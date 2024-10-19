<!--TEMPLATE-->
@extends('Templates.Officer.template')

<!--TITLE-->
@section('title')
<title>Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.dashboard')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Officer.Includes.Components.Modal.dashboard')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script>
    $("#year").on('change',function(){
        if($(this).find('option:selected').text()=="Pilih Tahun Periode Nilai Akhir")
            $("#editsaveBtn").attr('disabled',true)
        else
            $("#editsaveBtn").attr('disabled',false)
    }).trigger("change");
</script>
<script>
    var labels =  {{ Js::from($c_labels) }};
    var datas =  {{ Js::from($c_datas) }};
    const data = {
        labels: labels,
        datasets: [{
            label: 'Nilai Akhir',
            backgroundColor: 'rgb(0, 147, 221)',
            borderColor: 'rgb(0, 147, 221)',
            data: datas,
        }]
    };
    const config = {
        type: 'line',
        data: data,
        options: {
            plugins: {
                legend: {
                    display: false
                },
            }
        }
    };
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>
@endpush
