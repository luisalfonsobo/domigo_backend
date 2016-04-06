<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Cliente;
use App\Usuario;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cliente::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try {
            
            $data = $request->all();
            
            $usuario = new Usuario();
            $usuario->nombre = $data["nombres"]." ".$data["apellidos"];
            $usuario->login = isset($data["email"]) ?$data["email"] : NULL;
            $usuario->clave = isset($data["pass"]) ? sha1($data["pass"]) : NULL;
            $usuario->tipoAcceso = 4;
            $usuario->estado = 'ACTIVO';
            $usuario->save();
            
            $cliente = new Cliente();   
            
            $cliente->nombres         = isset($data["nombres"]) ? ($data["nombres"]) : NULL;
            $cliente->apellidos       = isset($data["apellidos"]) ? ($data["apellidos"]) : NULL;
            $cliente->cedula = isset($data["cedula"]) ? ($data["cedula"]) : NULL;
            $cliente->idCiudad     = isset($data["idCiudad"]) ? ($data["idCiudad"]) : NULL;
           
            $cliente->telefono        = isset($data["telefono"]) ? ($data["telefono"]) : NULL;
            $cliente->email          = isset($data["email"]) ? ($data["email"]) : NULL;
//            $cliente->keyConf = "..";
            $cliente->confirmado = 0;
            $cliente->idUsuario = $usuario->id;
            
            $cliente->save();
            
            $array = array();
            $array[0] = $usuario;
            $array[1] = $cliente;
            return JsonResponse::create(array('message' => "Cliente Guardado Correctamente", "request" =>json_encode($array)), 200); 
        } catch (Exception $e) {
            return JsonResponse::create(array('message' => "No se pudo guardar el cliente", "exception"=>$e->getMessage()), 401);
        }
    
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
