@extends('base.base')

@section('css')
    <style>
        body {
            margin: 0px;
            background: #333;
        }
        .container {
            width: 100vw;
            height: 100vh;
            background: #333;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        .box {
            width: 300px;
            height: 300px;
        }
        .box2{
            width: 80%;
        }

    </style>
@endsection

@section('content')

    @if(isset($error_description))
        <div class="container">
            <div class="box">
                <div class="card text-bg-dark mb-3" style="max-width: 18rem; margin: 0 auto; ">
                    <div class="card-header">Logar com Github</div>
                    <div class="card-body">
                        <h5 class="card-title">Ops...</h5>
                        <p class="card-text">{{ $error_description }}.</p>
                        <a href="{{ route('logout') }}" class="btn btn-info">Tentar de novo</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @if(!is_null($authUrl))
            <div class="container">
                <div class="box">
                    <div class="card text-bg-dark mb-3" style="max-width: 18rem; margin: 0 auto; ">
                        <div class="card-header">Logar com Github</div>
                        <div class="card-body">
                            @if(is_null($userData))
                                <h5 style="color:red">Alguma coisa deu errado. Por favor tente outra vez!</h5>
                            @else
                                <h5 class="card-title">Tem uma conta no Github?</h5>
                            @endif
                            <h5 class="card-title">Tem uma conta no Github?</h5>
                            <p class="card-text">Então clique no botão abaixo que logar com sua conta do Github.</p>
                            <a href="{{ htmlspecialchars($authUrl) }}" class="btn btn-info">Logar</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!is_null($userData))
            <div class="container">
                <div class="box2">
                    <div class="card mb-3" style="max-width: 540px; margin: 0 auto;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ $userData->picture }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Detalhes da conta</h5>
                                    <p class="card-text"><b>ID:</b> {{ $userData->oauth_uid }}.</p>
                                    <p class="card-text"><b>Name:</b> {{ $userData->name }}.</p>
                                    <p class="card-text"><b>Login Username:</b> {{ $userData->login }}.</p>
                                    <p class="card-text"><b>Email:</b> {{ $userData->email }}.</p>
                                    <p class="card-text"><b>Location:</b> {{ $userData->location }}.</p>
                                    <p class="card-text"><b>Profile Link:</b> <a href="{{ $userData->link }}" target="_blank">Visitar a página do GitHub</a>.</p>

                                    <a href="{{ route('logout') }}" class="btn btn-info">Sair</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    @endif

@endsection
