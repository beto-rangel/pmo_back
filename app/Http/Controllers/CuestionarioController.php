<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\helpers\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\InternalEventRepository as Internal;
use Carbon\Carbon;
use DB;
use Mail;

use App\Entities\Commodities21;
use App\Entities\Commodities3;

class CuestionarioController extends Controller
{
	protected $internal;

	public function __construct(Internal $internal)
    {
        $this->internal = $internal;
    }

    public function getSucursalByCr($cr){
        $data = DB::table('sucursales')->where('cr', $cr)->get();

        return JsonResponse::collectionResponse($data);
    }

    public function getUserByDar($division){
        $data = DB::table('consolidado_nombres_dares')->where('division', $division)->groupBy('ddz_dar_hub')->get();

        return JsonResponse::collectionResponse($data);
    }

    public function getUserPhone($nombre_dar){
        $data = DB::table('consolidado_nombres_dares')->where('ddz_dar_hub', $nombre_dar)->get();

        return JsonResponse::collectionResponse($data);
    }

    public function getTareasCommodities(){
        $data = DB::table('tareas')
	            ->where('tipo_solicitud', 1)
	            ->get();


        return JsonResponse::collectionResponse($data);
    }

    public function getTareasEquipamiento(){
        $data = DB::table('tareas')
	            ->where('tipo_solicitud', 2)
	            ->get();

        return JsonResponse::collectionResponse($data);
    }

    public function getIdByTarea($tarea){
        $data = DB::table('tareas')
	            ->where('pregunta', $tarea)
	            ->get();

        return JsonResponse::collectionResponse($data);
    }

    public function getCommoditiesPasos($tarea_id){

        $data = DB::table('commodities_paso2')
	            ->where('tarea_id', $tarea_id)
	            ->get();

        return JsonResponse::collectionResponse($data);
    }

    public function getCommoditiesCombos($tarea_id){

	    $data2 = DB::table('commodities_paso2_combos')
	            ->where('tarea_id', $tarea_id)
	            ->get();

        return JsonResponse::collectionResponse($data2);
    }

    public function getCommoditiesPasos21($tarea_id){

        $data = DB::table('commodities_paso2_1')
	            ->where('tarea_id', $tarea_id)
	            ->get();

        return JsonResponse::collectionResponse($data);
    }

    public function getCommoditiesCombos21($tarea_id){

	    $data2 = Commodities21::with('preguntas')->where('tarea_id', $tarea_id)->get();

        return JsonResponse::collectionResponse($data2);
    }

    public function getCommodities3Pasos($tarea_id){

	    $data2 = DB::table('commodities_paso3')->where('tarea_id', $tarea_id)->get();

        return JsonResponse::collectionResponse($data2);
    }


    public function getCommodities3Combos($tarea_id){

	   $data2 = Commodities3::with('preguntas')->where('tarea_id', $tarea_id)->get();

        return JsonResponse::collectionResponse($data2);
    }

    public function getInfoCr($cr){

	   $data = DB::table('sucursales')->where('cr', $cr)->get();

        return JsonResponse::collectionResponse($data);
    }

    public function storeCuestionario(Request $request){

        $now                                   = Carbon::now('America/Mexico_City')->subHour();
        $correo                                = array_get($request, 'correo');
        $division                              = array_get($request, 'division');
        $nombre_completo_dar                   = array_get($request, 'nombre_completo_dar');
        $telefono_dar                          = array_get($request, 'telefono_dar');
        $tipo_de_solicitud                     = array_get($request, 'tipo_de_solicitud');
        $tarea_a_realizar_commodities          = array_get($request, 'tarea_a_realizar_commodities');
        $tarea_a_realizar_commodities_id       = array_get($request, 'tarea_a_realizar_commodities_id');
        $descripcion_de_solicitud              = array_get($request, 'descripcion_de_solicitud');
        $tarea_a_realizar_equipamiento         = array_get($request, 'tarea_a_realizar_equipamiento');
        $equipo_reponer                        = array_get($request, 'equipo_reponer');
        $descripcion_equipamiento              = array_get($request, 'descripcion_equipamiento');
        $cuanta_con_nodo                       = array_get($request, 'cuanta_con_nodo');
        $cuanta_con_nodo_id                    = array_get($request, 'cuanta_con_nodo_id');
        $que_programa                          = array_get($request, 'que_programa');
        $cuenta_con_facilidades_comp           = array_get($request, 'cuenta_con_facilidades_comp');
        $cuenta_con_facilidades_comp_id        = array_get($request, 'cuenta_con_facilidades_comp_id');
        $cuenta_con_facilidades_tel            = array_get($request, 'cuenta_con_facilidades_tel');
        $cuenta_con_facilidades_tel_id         = array_get($request, 'cuenta_con_facilidades_tel_id');
        $cuenta_con_reub_ext                   = array_get($request, 'cuenta_con_reub_ext');
        $cuenta_con_reub_ext_id                = array_get($request, 'cuenta_con_reub_ext_id');
        $cuenta_con_reub_perf                  = array_get($request, 'cuenta_con_reub_perf');
        $cuenta_con_reub_perf_id               = array_get($request, 'cuenta_con_reub_perf_id');
        $cuenta_con_reub_perf_otro             = array_get($request, 'cuenta_con_reub_perf_otro');
        $cuenta_con_reub_gest                  = array_get($request, 'cuenta_con_reub_gest');
        $cuenta_con_reub_gest_id               = array_get($request, 'cuenta_con_reub_gest_id');
        $cuenta_con_reub_vent                  = array_get($request, 'cuenta_con_reub_vent');
        $cuenta_con_reub_vent_id               = array_get($request, 'cuenta_con_reub_vent_id');
        $visita_seg                            = array_get($request, 'visita_seg');
        $que_programa_requieres_nodo           = array_get($request, 'que_programa_requieres_nodo');
        $cuenta_con_nodo_facilidades           = array_get($request, 'cuenta_con_nodo_facilidades');
        $cuenta_con_nodo_facilidades_id        = array_get($request, 'cuenta_con_nodo_facilidades_id');
        $marca_nodo                            = array_get($request, 'marca_nodo');
        $modelo_nodo                           = array_get($request, 'modelo_nodo');
        $serie_nodo                            = array_get($request, 'serie_nodo');
        $inventario_nodo                       = array_get($request, 'inventario_nodo');
        $cuenta_con_sw                         = array_get($request, 'cuenta_con_sw');
        $cuenta_con_sw_id                      = array_get($request, 'cuenta_con_sw_id');
        $marca_sw                              = array_get($request, 'marca_sw');
        $modelo_sw                             = array_get($request, 'modelo_sw');
        $serie_sw                              = array_get($request, 'serie_sw');
        $inventario_sw                         = array_get($request, 'inventario_sw');
        $photo                                 = array_get($request, 'photo');
        $usuario_se_encuentra_en               = array_get($request, 'usuario_se_encuentra_en');
        $usuario_se_encuentra_en_id            = array_get($request, 'usuario_se_encuentra_en_id');
        $usuario_de_red_m_final                = array_get($request, 'usuario_de_red_m_final');
        $nombre_de_usuario_final               = array_get($request, 'nombre_de_usuario_final');
        $usuario_se_encuentra_en_2             = array_get($request, 'usuario_se_encuentra_en_2');
        $usuario_se_encuentra_en_2_id          = array_get($request, 'usuario_se_encuentra_en_2_id');
        $telefono_contacto_personal            = array_get($request, 'telefono_contacto_personal');
        $fecha_en_que_puedes_acudir_a_sucursal = array_get($request, 'fecha_en_que_puedes_acudir_a_sucursal');
        $cr                                    = array_get($request, 'cr');
        $nombre_contacto_en_sitio              = array_get($request, 'nombre_contacto_en_sitio');
        $telefono_contacto_en_sitio            = array_get($request, 'telefono_contacto_en_sitio');

        if($photo != ''){
            $tarea_a_realizar_commodities = '';
        }

        if($photo == ''){
            $tarea_a_realizar_equipamiento = '';
        }


        if($correo != null){
            $commodities_id = DB::table('commodities')->insertGetId(array(
                'correo'                                => $correo,
                'division'                              => $division,
                'nombre_completo_dar'                   => $nombre_completo_dar,
                'telefono_dar'                          => $telefono_dar,
                'tipo_de_solicitud'                     => $tipo_de_solicitud,
                'tarea_a_realizar_commodities'          => $tarea_a_realizar_commodities,
                'tarea_a_realizar_commodities_id'       => $tarea_a_realizar_commodities_id,
                'descripcion_de_solicitud'              => $descripcion_de_solicitud,
                'tarea_a_realizar_equipamiento'         => $tarea_a_realizar_equipamiento,
                'equipo_reponer'                        => $equipo_reponer,
                'descripcion_equipamiento'              => $descripcion_equipamiento,
                'cuanta_con_nodo'                       => $cuanta_con_nodo,
                'cuanta_con_nodo_id'                    => $cuanta_con_nodo_id,
                'que_programa'                          => $que_programa,
                'cuenta_con_facilidades_comp'           => $cuenta_con_facilidades_comp,
                'cuenta_con_facilidades_comp_id'        => $cuenta_con_facilidades_comp_id,
                'cuenta_con_facilidades_tel'            => $cuenta_con_facilidades_tel,
                'cuenta_con_facilidades_tel_id'         => $cuenta_con_facilidades_tel_id,
                'cuenta_con_reub_ext'                   => $cuenta_con_reub_ext,
                'cuenta_con_reub_ext_id'                => $cuenta_con_reub_ext_id,
                'cuenta_con_reub_perf'                  => $cuenta_con_reub_perf,
                'cuenta_con_reub_perf_id'               => $cuenta_con_reub_perf_id,
                'cuenta_con_reub_perf_otro'             => $cuenta_con_reub_perf_otro,
                'cuenta_con_reub_gest'                  => $cuenta_con_reub_gest,
                'cuenta_con_reub_gest_id'               => $cuenta_con_reub_gest_id,
                'cuenta_con_reub_vent'                  => $cuenta_con_reub_vent,
                'cuenta_con_reub_vent_id'               => $cuenta_con_reub_vent_id,
                'visita_seg'                            => $visita_seg,
                'que_programa_requieres_nodo'           => $que_programa_requieres_nodo,
                'cuenta_con_nodo_facilidades'           => $cuenta_con_nodo_facilidades,
                'cuenta_con_nodo_facilidades_id'        => $cuenta_con_nodo_facilidades_id,
                'marca_nodo'                            => $marca_nodo,
                'modelo_nodo'                           => $modelo_nodo,
                'serie_nodo'                            => $serie_nodo,
                'inventario_nodo'                       => $inventario_nodo,
                'cuenta_con_sw'                         => $cuenta_con_sw,
                'cuenta_con_sw_id'                      => $cuenta_con_sw_id,
                'marca_sw'                              => $marca_sw,
                'modelo_sw'                             => $modelo_sw,
                'serie_sw'                              => $serie_sw,
                'inventario_sw'                         => $inventario_sw,
                'photo'                                 => $photo,
                'usuario_se_encuentra_en'               => $usuario_se_encuentra_en,
                'usuario_se_encuentra_en_id'            => $usuario_se_encuentra_en_id,
                'usuario_de_red_m_final'                => $usuario_de_red_m_final,
                'nombre_de_usuario_final'               => $nombre_de_usuario_final,
                'usuario_se_encuentra_en_2'             => $usuario_se_encuentra_en_2,
                'usuario_se_encuentra_en_2_id'          => $usuario_se_encuentra_en_2_id,
                'telefono_contacto_personal'            => $telefono_contacto_personal,
                'fecha_en_que_puedes_acudir_a_sucursal' => $fecha_en_que_puedes_acudir_a_sucursal,
                'cr'                                    => $cr,
                'nombre_contacto_en_sitio'              => $nombre_contacto_en_sitio,
                'telefono_contacto_en_sitio'            => $telefono_contacto_en_sitio,                
                'created_at'                            => $now,
                'updated_at'                            => $now,
            ));
    
            if($photo != ''){
                DB::table('commodities_photo')->insert(array(
                    'commodities_id' => $commodities_id,
                    'photo'          => $photo,
                    'created_at'     => $now,
                    'updated_at'     => $now
                ));
            }

            //$userId = Auth::id();
            $this->internal->create(array(
                //'user_id'       => $userId,
                'evento'        => 'Se ha creado un nuevo elemento en la tabla commodities con id:  ' . $commodities_id . ' creado con el correo: ' . $correo ,
                'created_at'    => $now,
                'updated_at'    => $now
            ));

            $data_for_email = [
                'correo'   => $correo
            ];

            Mail::send('emails.envioExitosoCuestionario', $data_for_email, function ($m) use ($correo) {
                $m->from('marcoantonio.negrete.contractor@bbva.com', 'PMO');
                $m->to($correo)->subject("Se ha enviado de forma exitosa el cuestionario");
                $m->cc('marioalberto.rangel.contractor@bbva.com')->subject("Se ha enviado de forma exitosa el cuestionario");
                $m->cc('marcoantonio.negrete.contractor@bbva.com')->subject("Se ha enviado de forma exitosa el cuestionario");
              });

            return JsonResponse::singleResponse(["message" => "Info insertada" , 
              //"Data" => $data, 
            ]);
        }else{
            return JsonResponse::errorResponse("No es posible crear el registro", 404);
        }
           
    }

    public function saveArchivoCuestionario(Request $request)
    {
        try{
            $folio = $request->input('folio');
            $name = $request->input('name');

            $seccion = $request->input('seccion');
            $namecarpeta = $request->input('carpeta');

            $carpeta1 =  $seccion. '/'. $folio;

            $carpetaC =  $carpeta1. '/'. $namecarpeta;

            /*if (!file_exists('storage/'.$seccion)) {
                mkdir('storage/'.$seccion,  0777, true);
            }

            if (!file_exists('storage/'.$carpeta1)) {
                mkdir('storage/'.$carpeta1,  0777, true);
            }

            if (!file_exists('storage/'.$carpetaC)) {
                mkdir('storage/'.$carpetaC,  0777, true);
            }*/
            //obtenemos el campo file definido en el formulario
            $file = $request->file('file');

            if (!isset($name))
                $name = $file->getClientOriginalName();
            //obtenemos el nombre del archivo


            //indicamos que queremos guardar un nuevo archivo en el disco local
            \Storage::disk('local')->put($carpetaC.'/'.$name, \File::get($file));
            //\Storage::move('old/file1.jpg', 'new/file1.jpg');

            return JsonResponse::singleResponse(["message" => "El archivo ha sido guardado exitosamente"
            ], 200);
        }catch (\Exception $e)
        {
            return JsonResponse::errorResponse("Imposible guardar  el archivo", 500);
        }
    }

    
}