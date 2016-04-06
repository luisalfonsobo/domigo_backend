<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Servicio;
use App\TipoServicio;
use App\Mensajero;
use App\Empresa;
use App\Cliente;
use App\Usuario;
use Carbon\Carbon;
use WebSocket\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Utilities\Util;

class ServicioController extends Controller
{
    
    public function getServiciosTomados(Request $request){
        
    }
    
    public function getByEstado($estado){
        
        return Servicio::join('cliente', 'servicio.idCliente', '=', 'cliente.id')
                ->join('tipoServicio', 'servicio.idTipoServicio', '=', 'tipoServicio.id')
                ->where('servicio.estado','=',$estado)
                ->select('servicio.*','cliente.nombres as nombresCliente', 'cliente.apellidos as apellidosCliente', 'tipoServicio.nombre')
                ->get();
        
    }
    
    
    public function getByCliente($idCliente){
        
        return Servicio::join('cliente', 'servicio.idCliente', '=', 'cliente.id')
                ->join('tipoServicio', 'servicio.idTipoServicio', '=', 'tipoServicio.id')
                ->leftJoin('mensajero', 'servicio.idMensajero', '=', 'mensajero.id')
                ->where('cliente.id','=',$idCliente)
                ->select('servicio.*','cliente.nombres as nombresCliente', 'cliente.apellidos as apellidosCliente', 'tipoServicio.nombre', 'mensajero.nombres as nombresMensajero', 'mensajero.apellidos as apellidosMensajero', 'mensajero.telefono as telefonoMensajero')
                ->orderBy('servicio.id', 'desc')
                ->get();
        
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
            
            
            //---------------Verificar Mensajeros--------//
            $usuarios = array();
            $km = 3;
            $mensajeros = true;
            
            $sql = 'SELECT m.idUsuario, m.token, m.device_type,(6371 * ACOS( SIN(RADIANS(lat)) * SIN(RADIANS(' . $data["latitud"] . ')) + COS(RADIANS(lng - ' . $data["longitud"] . ')) * COS(RADIANS(lat)) * COS(RADIANS(' . $data["latitud"] . ')) ) ) AS distance FROM mensajero m  WHERE m.estado = "ACTIVO" HAVING distance < ' . $km . ' ORDER BY distance ASC';
            $usuarios = DB::select($sql);
            
            if(count($usuarios)<1){
                
                $km = 5;
                $sql = 'SELECT m.idUsuario, m.token, m.device_type, (6371 * ACOS( SIN(RADIANS(lat)) * SIN(RADIANS(' . $data["latitud"] . ')) + COS(RADIANS(lng - ' . $data["longitud"] . ')) * COS(RADIANS(lat)) * COS(RADIANS(' . $data["latitud"] . ')) ) ) AS distance FROM mensajero m  WHERE m.estado = "ACTIVO" HAVING distance < ' . $km . ' ORDER BY distance ASC';
                $usuarios = DB::select($sql);
                
            }
            
            if(count($usuarios)<1){
                
                $km = 7;
                $sql = 'SELECT m.idUsuario, m.token, m.device_type, (6371 * ACOS( SIN(RADIANS(lat)) * SIN(RADIANS(' . $data["latitud"] . ')) + COS(RADIANS(lng - ' . $data["longitud"] . ')) * COS(RADIANS(lat)) * COS(RADIANS(' . $data["latitud"] . ')) ) ) AS distance FROM mensajero m  WHERE m.estado = "ACTIVO" HAVING distance < ' . $km . ' ORDER BY distance ASC';
                $usuarios = DB::select($sql);
                
            }
            
            if(count($usuarios)<1){
                $empresas=true;
                $mensajeros = false;
                $km = 10;
                $sql = 'SELECT m.idUsuario, m.token, m.device_type, (6371 * ACOS( SIN(RADIANS(lat)) * SIN(RADIANS(' . $data["latitud"] . ')) + COS(RADIANS(lng - ' . $data["longitud"] . ')) * COS(RADIANS(lat)) * COS(RADIANS(' . $data["latitud"] . ')) ) ) AS distance FROM empresa m  WHERE m.estado = "ACTIVO" AND m.cerrado=0 HAVING distance < ' . $km . ' ORDER BY distance ASC';
                $usuarios = DB::select($sql);
                
            }
            
            if(count($usuarios)<1){
                $mensajeros = false;
                $empresas = false;
            }
            
            if($mensajeros==true){
                $users= "mensajeros";
            }elseif($empresas==true){
                $users= "empresas";
            }else{
                return JsonResponse::create(array('message' => "No hay Empresas de mensajería Disponibles", "request" =>'KO'), 200); 
            }
            
            
            $servicio = new Servicio();
            $servicio->idCliente = $data["idCliente"];
            $servicio->idTipoServicio = isset($data["tipoServicio"]) ?$data["tipoServicio"] : NULL;
            $servicio->direccionOrigen = isset($data["direccionOrigen"]) ? $data["direccionOrigen"] : NULL;
            $servicio->direccionDestino = isset($data["direccionDestino"]) ? $data["direccionDestino"] : NULL;
            $servicio->detalles = isset($data["detalles"]) ? $data["detalles"] : NULL;
            $servicio->telefono = isset($data["telefono"]) ? $data["telefono"] : NULL;
            
            $servicio->latOrigen = isset($data["latitud"]) ? $data["latitud"] : NULL;
            $servicio->lngOrigen = isset($data["longitud"]) ? $data["longitud"] : NULL;
            $servicio->referencia = isset($data["referencias"]) ? $data["referencias"] : NULL;
            
            $servicio->estado = 'PENDIENTE';
            
            $date = Carbon::now();
            $servicio->fecha         = $date->toDateString();
            $servicio->hora          = $date->toTimeString();
            
            $tipoServicio = TipoServicio::find($servicio->idTipoServicio);
            $servicio->valor = $tipoServicio->valor;
            $servicio->save();
            
            Util::enviarNotificacion($usuarios,array("accion"=>"servicio",
                                            "data"=>array(
                                                "servicio"=>$servicio,
                                                "message"=>"Nuevo Servicio en Espera",
                                                "servidores"=>$users,
                                                "flag"=>"servicio"
                                                )
                                        ) );
            
//            $client = new Client("ws://192.168.0.34:9092/Socket");
//            $client->send( json_encode(array("accion"=>"servicio",
//                                            "usuarios"=>$usuarios,
//                                            "data"=>array(
//                                                "servicio"=>$servicio,
//                                                "message"=>"Nuevo Servicio en Espera",
//                                                "servidores"=>$users
//                                                )
//                                        ) ) );
            
            return JsonResponse::create(array('message' => "Tu servicio solicitado correctamente y será confirmado en instantes", "request" =>'Correcto'), 200); 
            
        } catch (Exception $e) {
            return JsonResponse::create(array('message' => "Error al solicitar Servicio, intenta nuevamente", "exception"=>$e->getMessage(), "request" =>json_encode("Error")), 401);
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
    
    public function updateEstado(Request $r, $id)
    {
        $data = $r->all();
        
        $servicio = Servicio::find($id);
        if($data["estado"]=="ACEPTADO"){
            
            if($servicio->idMensajero != null){
            
                return JsonResponse::create(array('message' => "Lo sentimos, alguien tomó el servicio primero", "request" =>  "KO"), 200);

            }
            $servicio->idMensajero=$data["idMensajero"];
            $servicio->estado=$data["estado"];
            $servicio->save();
            
            $cliente = new Cliente();
            $c = $cliente::find($servicio->idCliente);
            
            $idUsuario = $c->idUsuario;
            
            $usuario = Usuario::find($idUsuario);
            
            $usuarios = Array($usuario);
            
            Util::enviarNotificacion($usuarios,array("accion"=>"aceptado",
                                            "data"=>array(
                                                "usuario"=>$idUsuario,
                                                "message"=>"Felicidades! Te Aceptaron un servicio",
                                                "flag"=>"aceptado"
                                                )
                                        ) );
          
            return JsonResponse::create(array('message' => "Servicio Aceptado Correctamente", "request" =>  "OK"), 200);
        }
        
        if($data["estado"]=="CANCELADO"){
            
            $servicio->estado=$data["estado"];
            $servicio->save();
            return JsonResponse::create(array('message' => "Servicio Cancelado Correctamente", "request" =>  "KO"), 200);
            
        }
        
        if($data["estado"]=="RECHAZADO"){
            
            $servicio->estado=$data["estado"];
            $servicio->save();
            return JsonResponse::create(array('message' => "Servicio Cancelado Correctamente", "request" =>  "KO"), 200);
            
        }
        
        

        
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
