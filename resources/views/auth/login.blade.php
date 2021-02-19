@extends('auth.contenido')

@section('login')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card p-4">
                    <form action="{{ route('login') }}" class="form-horizontal was-validate" method="post">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <img src="../img/logo-diana.png"/>
                            <h1 class="text-center">ACCEDER</h1>
                            <p class="text-muted text-center">CONTROL DE ACCESO AL SISTEMA</p>
                            <div class="form-group mb-3{{ $errors->has('usuario' ? 'is-invalid' : '') }}">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" name="usuario" id="usuario" value="{{ old('usuario')}}" class="form-control" placeholder="Usuario">
                                {!! $errors->first('usuario') !!}
                            </div>
                            <div class="form-group mb-4{{ $errors->has('password' ? 'is-invalid' : '') }}">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-danger px-4">ACCEDER</button>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" class="btn btn-link px-0">Olvidaste tu password?</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card text-white bg-danger py-5 d-md-down-none" style="width:44%">
                    <div class="card-body text-center">
                        <div>
                            <h2>MANTENIMIENTO PREVENTIVO Y RENDIMIENTO</h2>
                            <br>
                            <p>La función principal de la aplicación es facilitar la programación del mantenimiento preventivo 
                               de vehículos y procesar las bases de datos de despachos de combustible para el cálculo y reporte 
                               del indicador de rendimiento de combustible de la flota vehicular Diana.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
