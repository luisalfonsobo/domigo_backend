<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


//^^^^^CLIENTE^^^^^^^^
Route::resource('api/cliente','ClienteController');

//^^^^^DEPARTAMENTO^^^^^^^^
Route::resource('api/departamento','DepartamentoController');
Route::get('api/departamento/{idDepartamento}/municipios','DepartamentoController@getMunicipiosByDepartamento');



//^^^^^EMPRESAS^^^^^^^^
Route::resource('api/empresa','EmpresaController');
Route::put('api/empresa/{id}/estado','EmpresaController@updateEstado');
Route::get('api/empresa/{id}/mensajero','MensajeroController@getMensajeros');

Route::get('api/broadcast','EmpresaController@sendBroadcast');
Route::get('api/broadcastPush','EmpresaController@sendBroadcastPush');


//^^^^^MENSAJERO^^^^^^^^
Route::resource('api/mensajero','MensajeroController');
Route::get('api/cedula/{cedula}/mensajero','MensajeroController@getByCedula');
Route::put('api/mensajero/updateempresa/{id}','MensajeroController@updateEmpresa');
Route::put('api/mensajero/updatevehiculo/{id}','MensajeroController@updateVehiculo');
Route::put('api/geolocation/mensajero/{id}','MensajeroController@updateGeolocation');

//^^^^^SITIOS^^^^^^^^
//Route::resource('api/sitio','SitioController');

//^^^^^SERVICIOS^^^^^^^^
Route::resource('api/servicio','ServicioController');
Route::post('api/servicio/tomados','ServicioController@getServiciosTomados');
Route::put('api/estado/servicio/{id}','ServicioController@updateEstado');
Route::get('api/estado/{estado}/servicio','ServicioController@getByEstado');
Route::get('api/cliente/{idCliente}/servicio','ServicioController@getByCliente');

//^^^^^TIPOERVICIO^^^^^^^^
Route::resource('api/tiposervicio','TipoServicioController');
Route::get('api/activos/tiposervicio','TipoServicioController@tipoServiciosActivos');


//^^^^^VEHICULO^^^^^^^^
Route::resource('api/vehiculo','VehiculoController');
ROute::get('api/empresa/{idEmpresa}/vehiculo','VehiculoController@getByEmpresa');

//^^^^^USUARIOS^^^^^^^^
Route::post('api/user/login','UsuarioController@autenticar');
Route::post('api/token/usuario','UsuarioController@actualizarToken');
Route::resource('api/usuario','UsuarioController');
