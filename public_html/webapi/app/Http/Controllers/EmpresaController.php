<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Empresa;
use App\Usuario;
use WebSocket\Client;
use App\Http\Utilities\Util;

class EmpresaController extends Controller
{
    
    public function sendBroadcast(){
        
//        $client = new Client("ws://192.168.1.47:9092/Socket");
//        $client->send( json_encode(array("accion"=>"broadcast","data"=>array("message"=>"Hola Putitos")) ) );
        Util::enviarNotificacionIndividual("APA91bE0tOiyBC8mjcR05S6aZZ0ciaWsrwOBY0XOg66N20V1l-_JBXsowexbKxIKw-F_9UdOHVs1ax1GCRSrHBhsxKM11irJhEqMLX-NIT0MlqWtnZfRqibykg9IsgCrD1rY8SNqU9PU",1,array("accion"=>"servicio",
                                            "data"=>array(
                                                "servicio"=>"servicio",
                                                "message"=>"Nuevo Servicio en Espera",
                                                "servidores"=>"servidores",
                                                "flag"=>"servicio"
                                                )
                                        ));
        echo "Enviado Correctamente";
        
        
        
    }
    
    public function sendBroadcastPush(){
        // Put your device token here (without spaces):
        $deviceToken = 'bfb2bb6bc515b42ba8f344a4f55bf7bff000a7a7179db1779dc534057a522b44';

        // Put your private key's passphrase here:
        $passphrase = 'qaz123';

        // Put your alert message here:
        $message = 'My first push notification!';
        echo $_SERVER['DOCUMENT_ROOT'];
        ////////////////////////////////////////////////////////////////////////////////
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $_SERVER['DOCUMENT_ROOT'].'/pushApp.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);

        echo 'Connected to APNS' . PHP_EOL;

        // Create the payload body
        $body['aps'] = array(
                'alert' => $message,
                'sound' => 'default'
                );

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result)
                echo 'Message not delivered' . PHP_EOL;
        else
                echo 'Message successfully delivered' . PHP_EOL;

        // Close the connection to the server
        fclose($fp);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Empresa::join('usuario', 'empresa.idUsuario', '=', 'usuario.id')
            ->select('empresa.*',"usuario.login","usuario.estado")
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
            $usuario->tipoAcceso = 2;
            $usuario->estado = 'INACTIVO';
            $usuario->save();
            
            $empresa = new Empresa();

            $empresa->nombre = isset($data["nombre"]) ?$data["nombre"] : NULL;
            $empresa->direccion = isset($data["direccion"]) ?$data["direccion"] : NULL;
            $empresa->telefono = isset($data["telefono"]) ?$data["telefono"] : NULL;
            $empresa->email = isset($data["email"]) ?$data["email"] : NULL;
            $empresa->nit = isset($data["nit"]) ?$data["nit"] : NULL;
            $empresa->idUsuario = $usuario->id;
            $empresa->estado='INACTIVO';
            
            $empresa->save();
            
            $array = array();
            $array[0] = $usuario;
            $array[1] = $empresa;

            return JsonResponse::create(array('message' => "Empresa Guardada Correctamente", "request" => json_encode($array)), 200);
        }else{
            return JsonResponse::create(array('message' => "Error al Guardar Empresa"), 401);
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
            $empresa = Empresa::find($id);
            $empresa->id = $data["id"];
            $empresa->nombre = isset($data["nombre"]) ?$data["nombre"] : NULL;
            $empresa->direccion = isset($data["direccion"]) ?$data["direccion"] : NULL;
            $empresa->telefono = isset($data["telefono"]) ?$data["telefono"] : NULL;
            $empresa->email = isset($data["email"]) ?$data["email"] : NULL;
            $empresa->nit = isset($data["nit"]) ?$data["nit"] : NULL;
            
            $empresa->save();

            return JsonResponse::create(array('message' => "Empresa Modificada Correctamente", "request" =>  $data["id"]), 200);
        }else{
            return JsonResponse::create(array('message' => "Error al Modificar Empresa"), 401);
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
        $empresa = Empresa::find($id);
        $empresa->estado=$data["estado"];
        $empresa->save();
        $usuario = Usuario::find($empresa->idUsuario);
        $usuario->estado = $data["estado"];
        
        $usuario->save();

        return JsonResponse::create(array('message' => "Estado de la Central Modificada Correctamente", "request" =>  $data["estado"]), 200);
        
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
