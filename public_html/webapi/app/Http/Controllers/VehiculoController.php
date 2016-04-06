<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Vehiculo;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vehiculo::leftJoin('mensajero', 'mensajero.idVehiculo', '=', 'vehiculo.id')
                ->select('vehiculo.*', 'mensajero.nombres','mensajero.apellidos')
                ->get();
    }
    
    public function getByEmpresa($idEmpresa)
    {
        return Vehiculo::leftJoin('mensajero', 'mensajero.idVehiculo', '=', 'vehiculo.id')
                ->where('vehiculo.idEmpresa','=',$idEmpresa)
                ->select('vehiculo.*', 'mensajero.nombres','mensajero.apellidos')
                ->get();
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
            
            $vehiculo = new Vehiculo();
            
            $vehiculo->placa = isset($data["placa"]) ?$data["placa"] : NULL;
            $vehiculo->color =  isset($data["color"]) ?$data["color"] : NULL;
            $vehiculo->idEmpresa = isset($data["idEmpresa"]) ?$data["idEmpresa"] : NULL;
            
            $vehiculo->save();

            return JsonResponse::create(array('message' => "Vehiculo Guardado Correctamente", "request" => json_encode($vehiculo)), 200);
            
        } catch (Exception $exc) {
            return JsonResponse::create(array('message' => "Error al Guardar Vehiculo"), 401);
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
            
            $vehiculo = Vehiculo::find($id);
            
            $vehiculo->placa = isset($data["placa"]) ?$data["placa"] : NULL;
            $vehiculo->color =  isset($data["color"]) ?$data["color"] : NULL;
            
            $vehiculo->save();

            return JsonResponse::create(array('message' => "Vehiculo Guardado Correctamente", "request" => json_encode($vehiculo)), 200);
            
        } catch (Exception $exc) {
            return JsonResponse::create(array('message' => "Error al Guardar Vehiculo"), 401);
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
