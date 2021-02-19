<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Historial;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        
        $this->validateLogin($request);

        if(Auth::attempt(['nombre' => $request->usuario, 'password' => $request->password, 'condicion' => 1])){
            
            $user = User::findOrFail(Auth::user()->id);
            $user->ultimo_login = \Carbon\Carbon::now();
            $user->save();

            $historial = new Historial();
            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'LOGIN';
            $historial->descripcion = 'INGRESÓ AL SISTEMA CON SUS CREDENCIALES';
            $historial->save();
            
            return redirect()->route('main');
        }

        return back()
                ->withErrors(['usuario' => trans('auth.failed')])
                ->withInput(request(['usuario']));

    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]);
    }

    public function logout(Request $request)
    {
        $historial = new Historial();
        $historial->id_user = Auth::user()->id;
        $historial->nombre_accion = 'LOGOUT';
        $historial->descripcion = 'CERRÓ SU SESIÓN EN EL SISTEMA';
        $historial->save();

        Auth::logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
