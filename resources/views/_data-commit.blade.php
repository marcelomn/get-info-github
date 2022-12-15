<h4>Dados do commit:</h4>

@foreach($commits as $commit)
    <p class="small"><b>Mensagem:</b> {{ $commit['commit']['message'] }}<br>
    <b>Autor:</b> {{ $commit['commit']['author']['name'] }}<br>
{{--    <b>Data e Hora:</b> {{ date('d/m/Y \à\s H \h\o\r\a\s \e i \m\i\n\u\t\o\s \e s \s\e\g\u\n\d\o\s', strtotime($commit['commit']['author']['date'])) }}<br>--}}
    <b>Data e Hora:</b> {{ date('d/m/Y \à\s H:i:s', strtotime($commit['commit']['author']['date'])) }}<br>
    <b>Adições:</b> {{ $commit['data']['stats']['additions'] }}<br>
    <b>Exclusões:</b> {{ $commit['data']['stats']['deletions'] }}<br>
    <b>Total:</b> {{ $commit['data']['stats']['total'] }}</p>
@endforeach
