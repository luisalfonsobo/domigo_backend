<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Usuario;
use App\Empresa;
use App\Sitio;
use App\Mensajero;
use App\Cliente;
class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Usuario::all();
    }
    
    public function autenticar(Request $r){

        $data = $r->all();
        $usuario = $data["usuario"];
        $clave = $data["clave"];
        
        $user = Usuario::where('login', $usuario)->where('clave', sha1($clave))->first();
        
        if (empty($user)){
            return JsonResponse::create(array('message' => "100", "request" =>json_encode('Usuario o Clave Incorrecta')), 200);
        }
        
        if($user){
            
            if($user->estado != 'ACTIVO'){
                return JsonResponse::create(array('message' => "200", "request" =>json_encode("Usuario Bloqueado")), 200);
            }
            
            if($user->tipoAcceso==1){
                //ADMIN
                $object = NULL;
                
            }elseif($user->tipoAcceso==2){
                //EMPRESA
                $object = Empresa::where("idUsuario",$user->id)->first();
                
            }elseif($user->tipoAcceso==3){
                //MENSAJERO
                $object = Mensajero::where("idUsuario",$user->id)->first();
                
            }elseif($user->tipoAcceso==4){
                
                //CLIENTE
                $object = Cliente::where("idUsuario",$user->id)->first();
                
            }elseif($user->tipoAcceso==5){
                
                //SITIO
                //$object = Cliente::where("idUsuario",$user->id);
                
            }
            
            $array = array();
            $array[0] = $user;
            $array[1] = $object;

            return JsonResponse::create(array('message' =>"Correcto", "request" =>json_encode($array)), 200);
            
            
        }
        
        
    }
    
    public function actualizarToken(Request $r){

        $data = $r->all();
        $idUsuario = $data["idUsuario"];
        $token = $data["token"];
        $device = $data["device"];
        
        $user = Usuario::find($idUsuario);
        
        if($user->tipoAcceso == 3){
            
            $mensajero = Mensajero::where("idUsuario",$idUsuario)->first();
            $m = Mensajero::find($mensajero->id);
            $m->token = $token;
            $m->device_type = $device;
        
            $m->save();
            
        }elseif($user->tipoAcceso == 4){
            
            $cliente = Cliente::where("idUsuario",$idUsuario)->first();
            $m = Cliente::find($cliente->id);
            $m->token = $token;
            $m->device_type = $device;
        
            $m->save();
            
        }
        
        
        
        return JsonResponse::create(array('message' =>"Correcto", "request" =>json_encode($r)), 200);
        
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
    public function store()
    {
        //
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
    public function update($id)
    {
        //
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
