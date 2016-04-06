<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sitio;
use App\Usuario;

class SitioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Sitio::join('usuario', 'sitio.idUsuario', '=', 'usuario.id')
            ->select('sitio.*',"usuario.login","usuario.estado")
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $r)
    {
        $data = $r->all();
        if($r->isJson()){
            
            $usuario = new Usuario();
            $usuario->nombre = isset($data["nombre"]) ?$data["nombre"] : NULL;
            $usuario->login = isset($data["email"]) ?$data["email"] : NULL;
            $usuario->clave = isset($data["pass"]) ? sha1($data["pass"]) : NULL;
            $usuario->tipoAcceso = 5;
            $usuario->estado = 'INACTIVO';
            $usuario->save();
            
            $sitio = new Sitio();

            $sitio->nombre = isset($data["nombre"]) ?$data["nombre"] : NULL;
            $sitio->direccion = isset($data["direccion"]) ?$data["direccion"] : NULL;
            $sitio->telefono = isset($data["telefono"]) ?$data["telefono"] : NULL;
            $sitio->email = isset($data["email"]) ?$data["email"] : NULL;
            $sitio->nit = isset($data["nit"]) ?$data["nit"] : NULL;
//            $sitio->lat = isset($data["lat"]) ?$data["lat"] : NULL;
//            $sitio->lng = isset($data["lng"]) ?$data["lng"] : NULL;
            $sitio->idUsuario = $usuario->id;
            
            $sitio->save();
            
            $array = array();
            $array[0] = $usuario;
            $array[1] = $sitio;

            return JsonResponse::create(array('message' => "Sitio Guardado Correctamente", "request" => json_encode($array)), 200);
        }else{
            return JsonResponse::create(array('message' => "Error al Guardar Sitio"), 401);
        }
        //return JsonResponse::create(array('msj' => 'Please log in.'), 401);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $r, $id)
    {
        $data = $r->all();
        
        if($r->isJson()){
            $sitio = Sitio::find($id);
            $sitio->id = $data["id"];
            $sitio->nombre = isset($data["nombre"]) ?$data["nombre"] : NULL;
            $sitio->direccion = isset($data["direccion"]) ?$data["direccion"] : NULL;
            $sitio->telefono = isset($data["telefono"]) ?$data["telefono"] : NULL;
            $sitio->email = isset($data["email"]) ?$data["email"] : NULL;
            $sitio->nit = isset($data["nit"]) ?$data["nit"] : NULL;

            $sitio->save();

            return JsonResponse::create(array('message' => "Sitio Modificada Correctamente", "request" =>  $data["id"]), 200);
        }else{
            return JsonResponse::create(array('message' => "Error al Modificar Sitio"), 401);
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateEstado(Request $r, $id)
    {
        $data = $r->all();
        
        if($r->isJson()){
            $sitio = Sitio::find($id);
            $usuario = Usuario::find($sitio->idUsuario);
            $usuario->estado = $data["estado"];
            $usuario->save();

            return JsonResponse::create(array('message' => "Estado de la Empresa Modificada Correctamente", "request" =>  $data["id"]), 200);
        }else{
            return JsonResponse::create(array('message' => "Error al Modificar Empresa"), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
