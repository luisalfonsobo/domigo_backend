<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mensajero;
use App\Usuario;
use WebSocket\Client;

class MensajeroController extends Controller
{
    
    public function getMensajeros($id){
        
        return Mensajero::leftJoin('vehiculo', 'mensajero.idVehiculo', '=', 'vehiculo.id')
                ->where('mensajero.idEmpresa','=',$id)
                ->select('mensajero.*','vehiculo.placa')
                ->get();
        
    }   
    
    
    public function getByCedula($cedula){
        
        return Mensajero::where('cedula','=',$cedula)->first();
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateEmpresa(Request $r, $id)
    {
        $data = $r->all();
        
        $mensajero = Mensajero::find($id);
        $mensajero->idEmpresa = $data["idEmpresa"];
        $mensajero->estado='ACTIVO';
        $mensajero->save();
        
        $usuario = Usuario::find($mensajero->idUsuario);
        $usuario->estado = 'ACTIVO';
        
        $usuario->save();
        return JsonResponse::create(array('message' => "Felicidades, tienes un nuevo mensajero en tu Flota ! ", "request" =>  $data["idEmpresa"]), 200);
        
    }
    
    public function updateVehiculo(Request $r, $id)
    {
        $data = $r->all();
        $mensajero = Mensajero::find($id);
        $mensajero->idVehiculo = $data["idVehiculo"];
        $mensajero->save();

        return JsonResponse::create(array('message' => "", "request" =>  $data["idVehiculo"]), 200);
        
    }
    
    public function updateGeolocation(Request $r, $id){
        
        
        $data = $r->all();
        $mensajero = Mensajero::find($id);
        $mensajero->lat =$data["location"][0]["coords"]["latitude"];
        $mensajero->lng = $data["location"][0]["coords"]["longitude"];
        $mensajero->cont++;
        $mensajero->save();
        
        return JsonResponse::create(array('message' => "", "request" =>  json_encode($data)), 200);
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $usuario->tipoAcceso = 3;
            $usuario->estado = 'INACTIVO';
            $usuario->save();
            
            $mensajero = new Mensajero();            
            $mensajero->nombres         = $data["nombres"];
            $mensajero->apellidos       = $data["apellidos"];
            $mensajero->cedula = $data["cedula"];
            $mensajero->direccion       = $data["direccion"];
            $mensajero->telefono        = $data["telefono"];
            $mensajero->email          = $data["email"];
            $usuario->estado = 'INACTIVO';
            //$mensajero->keyConf = uniqid('Bme',true);
            //$mensajero->confirmado = 0;
            $mensajero->idUsuario = $usuario->id;
            $mensajero->save();
            
            $array = array();
            $array[0] = $usuario;
            $array[1] = $mensajero;
            
            return JsonResponse::create(array('message' => "Motociclista Guardado Correctamente", "request" =>json_encode($array)), 200); 
        } catch (Exception $e) {
            return JsonResponse::create(array('message' => "No se pudo guardar el cliente", "exception"=>$e->getMessage(), "request" =>json_encode($data)), 401);
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
