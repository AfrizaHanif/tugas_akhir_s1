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

    const noData = {
        id: 'noData',
        afterDatasetsDraw: ((chart, args, plugins) => {
            const { ctx, data, chartArea: {top, bottom, left, right, width, height}} = chart;
            ctx.save();
            console.log(data.datasets.length)

            if(data.datasets.length === 0){
                ctx.fillStyle = 'rgba(102, 102, 102, 0.5)';
                ctx.fillRect(left, top, width, height);

                ctx.font = 'bold, 20px, sans-serif';
                ctx.fillStyle = 'black';
                ctx.textAlign = 'center';
                ctx.fillText('Tidak Ada Data', left + width / 2, top + height / 2)
            }
        })
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
        },
        plugins: [noData]
    };
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>
@endpush
