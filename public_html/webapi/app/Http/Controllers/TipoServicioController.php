<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TipoServicio;
use DB;

class TipoServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TipoServicio::all();
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
            
            $tipoServicio = new TipoServicio();
            
            $tipoServicio->descripcion = isset($data["descripcion"]) ?$data["descripcion"] : NULL;
            $tipoServicio->valor =  isset($data["precio"]) ?$data["precio"] : NULL;
            $tipoServicio->nombre = isset($data["nombre"]) ?$data["nombre"] : NULL;
            $tipoServicio->estado = isset($data["estado"]) ?$data["estado"] : NULL;
            $tipoServicio->save();

            return JsonResponse::create(array('message' => "Tipo de Servicio Guardado Correctamente", "request" => json_encode($tipoServicio)), 200);
            
        } catch (Exception $exc) {
            return JsonResponse::create(array('message' => "Error al Guardar TipoServicio"), 401);
        }
    }

    public function tipoServiciosActivos(){
         try {
             $result =  $result = DB::select(DB::raw(
                        "Select * from tiposervicio
                        WHERE  estado = 'ACTIVO'"
                    ));
             return $result;
             
             } catch (Exception $exc) {
            return JsonResponse::create(array('message' => "Error al mostrar TipoServicio"), 401);
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
        try {
            
            $data = $request->all();
            
            $tipoServicio = TipoServicio::find($id);
            
            $tipoServicio->descripcion = isset($data["descripcion"]) ?$data["descripcion"] : NULL;
            $tipoServicio->valor =  isset($data["precio"]) ?$data["precio"] : NULL;
            $tipoServicio->nombre = isset($data["nombre"]) ?$data["nombre"] : NULL;
            $tipoServicio->estado = isset($data["estado"]) ?$data["estado"] : NULL;
            $tipoServicio->save();

            return JsonResponse::create(array('message' => "Tipo de Servicio Modificado Correctamente", "request" => json_encode($tipoServicio)), 200);
            
        } catch (Exception $exc) {
            return JsonResponse::create(array('message' => "Error al Guardar TipoServicio"), 401);
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
