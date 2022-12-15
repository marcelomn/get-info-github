@extends('base.base')

@include('base._inc.navbar')

<main class="container mt-3">
    <h2 class="mb-2">Dados do repositório {{ $repository }}</h2>

    @if(isset($dataChart))
        <div class="row">
            <div class="col-sm-12">
                <p>Clique na data do commit no gráfico para obter mais detalhes sobre cada commit.</p>
            </div>
            <div class="col-sm-6">
                <div style="width: 100%; height: 400px;">
                    <canvas id="myChart" width="500" height="400"></canvas>
                </div>
            </div>
            <div class="col-sm-6" id="js-data-commit"></div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12">
                <p>Ops, algo deu errado, acho que esse repositório está vazio.</p>
                <a class="btn btn-info btn-sm" href="{{ route('github.index') }}">Ir para a lista de respositórios</a>
            </div>
        </div>
    @endif

</main>

@section('js')
    @if(isset($dataChart))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        let chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Quantidade de commits',
                    data: {!! $dataChart !!}
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Commits nos útltimos 90 dias'
                }
            }
        });

        function getDataCommit(req){
            document.querySelector('#js-data-commit').innerHTML = req.responseText;
        }

        function sendRequest(url,callback,postData=null) {
            var req = createXMLHTTPObject();
            if (!req) return;
            var method = (postData) ? "POST" : "GET";
            req.open(method,url,true);
            // req.setRequestHeader('User-Agent','XMLHTTP/1.0');
            if (postData) {
                req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                req.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
            req.onreadystatechange = function () {
                if (req.readyState != 4) return;
                if (req.status != 200 && req.status != 304) {
                    alert('HTTP error ' + req.status);
                    return;
                }
                callback(req);
            }
            if (req.readyState == 4) return;
            req.send(postData);
        }

        let XMLHttpFactories = [
            function () {return new XMLHttpRequest()},
            function () {return new ActiveXObject("Msxml2.XMLHTTP")},
            function () {return new ActiveXObject("Msxml3.XMLHTTP")},
            function () {return new ActiveXObject("Microsoft.XMLHTTP")}
        ];

        function createXMLHTTPObject() {
            var xmlhttp = false;
            for (var i=0;i<XMLHttpFactories.length;i++) {
                try {
                    xmlhttp = XMLHttpFactories[i]();
                }
                catch (e) {
                    continue;
                }
                break;
            }
            return xmlhttp;
        }

        function clickHandler(click){
            const points = chart.getElementsAtEventForMode(click, 'nearest', {
               intersect:true
            }, true);

            if(points[0]){
                document.querySelector('#js-data-commit').innerHTML = 'Obtendo dados, aguarde...';
                const dataset = points[0].datasetIndex;
                const index = points[0].index;
                const label = chart.data.labels[index];
                const value = chart.data.datasets[dataset].data[index].y;
                const repository = chart.data.datasets[dataset].data[index].repo;

                let date = label.replaceAll('/', '-');
                let url = '{{ route('github.data-commit', ['repository'=>':REPOSITORY', 'date'=>':DATE']) }}'
                    .replace(':REPOSITORY', repository).replace(':DATE', date);
                sendRequest(url, getDataCommit);
            }
        }

        chart.canvas.onclick = clickHandler;
    </script>
    @endif
@endsection
