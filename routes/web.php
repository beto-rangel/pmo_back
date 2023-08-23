<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('login', 'APILoginController@login');

Route::group(['prefix' => 'descarga'], function (\Illuminate\Routing\Router $router) {

    $router->get('storage/{seccion}/{id}/{carpeta}/{file}', function ($seccion,$id,$carpeta,$archivo) { //obtorsener archivos
        $public_path = storage_path();
        $ruta =$seccion.'/'.$id.'/'.$carpeta.'/' .$archivo;
        $url = $public_path . '/app/'.$ruta;


        //verificamos si el archivo existe y lo retornamos

        if (Storage::exists($ruta)) {
            // dd($url);
            return Response::make(file_get_contents($url), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$archivo.'"'
            ]);

        }
        //si no se encuentra lanzamos un error 404.
        // abort(404);
    });


    $router->get('storage/{file}', function ($archivo) { //obtorsener archivos
        $public_path = public_path();
        $url = $public_path . '/storage/' . $archivo;
        //verificamos si el archivo existe y lo retornamos

        if (Storage::exists($archivo)) {
            return response()->download($url);

        }
        //si no se encuentra lanzamos un error 404.
        // abort(404);
    });
});

Route::group(['namespace' => 'v1', 'prefix' => 'v1/archivos'], function (\Illuminate\Routing\Router $router) {
    $router->post('storage/create', 'StorageController@save');
});

Route::group(['middleware' => ['jwt.auth', 'cors'],'prefix' => 'v1'], function () {

    Route::group(['namespace' => 'v1'], function () {

        Route::get('usuarios', 'UserController@index');
        Route::get('usuarios/{user_id}', 'UserController@show');
        Route::post('usuarios', 'UserController@store');
        Route::post('usuarios/{user_id}', 'UserController@update');
        Route::delete('usuarios/{user_id}', 'UserController@destroy');
        Route::put('usuarios/{user_id}/resetPassword', 'UserController@updatePassword');

        //CUESTIONARIO CONTROLLER
        Route::get('sucursalByCr/{cr}', 'CuestionarioController@getSucursalByCr');


       

        Route::group(['prefix' => 'archivos'], function (\Illuminate\Routing\Router $router) {
            $router->post('storage/create', 'StorageController@save');
        });



        
    });

});

Route::get('sucursalByCr/{cr}', 'CuestionarioController@getSucursalByCr');
Route::get('user_dar/{division}', 'CuestionarioController@getUserByDar');
Route::get('user_dar_telefono/{nombre_completo_dar}', 'CuestionarioController@getUserPhone');
Route::get('tareasCommodities', 'CuestionarioController@getTareasCommodities');
Route::get('tareasEquipamiento', 'CuestionarioController@getTareasEquipamiento');

Route::get('getIdByTarea/{tarea}', 'CuestionarioController@getIdByTarea');
Route::get('getTablesCommoditiesPasos/{tarea_id}', 'CuestionarioController@getCommoditiesPasos');
Route::get('getTablesCommoditiesCombos/{tarea_id}', 'CuestionarioController@getCommoditiesCombos');

Route::get('getTablesCommoditiesPasos21/{tarea_id}', 'CuestionarioController@getCommoditiesPasos21');
Route::get('getTablesCommoditiesCombos21/{tarea_id}', 'CuestionarioController@getCommoditiesCombos21');

Route::get('commodities_paso3/{tarea_id}', 'CuestionarioController@getCommodities3Pasos');
Route::get('commodities_paso3_combos/{tarea_id}', 'CuestionarioController@getCommodities3Combos');

Route::get('buscarCr/{cr}', 'CuestionarioController@getInfoCr');

Route::post('postCuestionario', 'CuestionarioController@storeCuestionario');

Route::post('postArchivo', 'CuestionarioController@saveArchivoCuestionario');




