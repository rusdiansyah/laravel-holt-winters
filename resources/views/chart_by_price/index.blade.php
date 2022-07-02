@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Chart By Price') }}</div>

                <div class="card-body">
                    <div id="linechart" style="width: 100%; height: 500px"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    var visitor = @php echo $data;
    @endphp;
    console.log(visitor);
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(visitor);
        var options = {
            title: 'Price Forecast Line Chart'
            , curveType: 'function'
            , legend: {
                position: 'bottom'
            }
        };
        var chart = new google.visualization.LineChart(document.getElementById('linechart'));
        chart.draw(data, options);
    }

</script>
@endpush
