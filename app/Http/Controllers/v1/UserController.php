<?php

namespace App\Http\Controllers\v1;
use App\helpers\JsonResponse;
use App\Entities\Barrios;
use App\Entities\GestionBarrios;
use App\Entities\ComentariosBarrios;
use App\Repositories\Eloquent\UserRepository as User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Traits\JWTTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Eloquent\InternalEventRepository as Internal;
use Carbon\Carbon;

class UserController extends Controller
{
    use JWTTrait;

    protected $user;
    protected $hidden = ['password', 'remember_token'];
    protected $internal;


    /**
     * UserController constructor.$userId = Auth::id();
     *
     * @param User $user
     *
     */

    public function __construct(User $user, Internal $internal)
    {
        $this->user = $user;
        $this->internal = $internal;
    }

    /**
     * Devuelve todas los usuarios en el almacenamiento.
     *
     * @return \Illuminate\Http\response
     */

    public function getDivisions(){
        $divisiones = DB::table('cat_divisiones')->get();
        return JsonResponse::collectionResponse($divisiones);
    }

    public function getRoles(){
        $data = DB::table('cat_rol')->get();
        return JsonResponse::collectionResponse($data);
    }

    public function index(Request $request)
    {

        $users = $this->user->all();
        
        return JsonResponse::collectionResponse($users);
    }

    private function getDivisionesByUser($user_id)
    {
        $divisiones = DB::table('users')
            ->join('users_divisiones', 'users.id', '=', 'users_divisiones.user_id')
            ->where('users_divisiones.user_id', $user_id)
            ->select('users_divisiones.*')
            ->get();

        return $divisiones;
    }

    private function getRolesByUser($user_id)
    {
        $roles = DB::table('users')
            ->join('users_rol', 'users.id', '=', 'users_rol.user_id')
            ->where('users_rol.user_id', $user_id)
            ->groupBy('cat_rol_id')
            ->select('users_rol.*')
            ->get();

        return $roles;
    }

    /**
     * nuevo usuario  en el sistema
     *
     * @param \Illuminate\http\Request $request
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $data = $request->all();
        $now = Carbon::now('America/Mexico_City')->subHour();
        $user = Auth::user();

        $usuario_id =  DB::table('users')->insertGetId(array(
            'name'      => array_get($request, 'name'),
            'email'     => array_get($request, 'email'),
            'role'      => array_get($request, 'role'),
            'status'    => array_get($request, 'status'),
            'password'  => Hash::make(array_get($request, 'password')),
            'created_at'    => $now,
            'updated_at'    => $now

        ));

        $this->internal->create(array(
            'user_id'   => $user['id'],
            'evento'    => 'El usuario: '.$user['name']. ' ha creado el usuario: ' .array_get($request, 'name'),
            'created_at'    => $now,
            'updated_at'    => $now
        ));

        return JsonResponse::singleResponse([
            "message" => "se ha registrado un nuevo usuario",
        ],200);
    }

    public function show($user_id)
    {
        try { 
            $usuarios = $this->user->findOrFail($user_id);

            return JsonResponse::singleResponse($usuarios->toArray()); 
 
        } catch (ModelNotFoundException $exception) { 
            \Log::error("Mostrando un usuario...", [ 
                "model"   => $exception->getModel(), 
                "message" => $exception->getMessage(), 
                "code"    => $exception->getCode() 
            ]);  
 
            return JsonResponse::errorResponse("No se puede mostrar el usuario, informacion no encontrada", 404); 
        } 
    }

    /**
     * Actualiza el usuario en especifico  en el almacenamiento
     *
     * @param UpdateUserRequest $request
     * @param  int              $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$user_id )
    {
        $data = $request->all();
        $now  = Carbon::now('America/Mexico_City')->subHour();
        $user = Auth::user();

        DB::table('users')->where('id', $user_id)->update(array(
            'name'       => array_get($request, 'name'),
            'email'      => array_get($request, 'email'),
            'role'       => array_get($request, 'role'),
            'status'     => array_get($request, 'status'),
            'created_at' => $now,
            'updated_at' => $now
        ));

        $this->internal->create(array(
            'user_id'   => $user['id'],
            'evento'    => 'El usuario: '.$user['name']. ' ha editado el usuario: ' .array_get($request, 'name'),
            'created_at'    => $now,
            'updated_at'    => $now
        ));

        return JsonResponse::singleResponse(["message" => "Se ha actualizado un Usuario"]);

    }
    /**
     *Elimina un usuario en espesifico dentro del almacenamiento.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $now = Carbon::now('America/Mexico_City');
            $user_delete = $this->user->find($id);
            $user = Auth::user();
            $user_id = Auth::user()->id;
                
            if($user_id == $id){
                return JsonResponse::errorResponse("No es posible auto destruirte", 404);
            }else{
                $this->internal->create(array(
                    'user_id'   => $user['id'],
                    'evento'    => 'El usuario: '.$user['name']. ' ha eliminado el usuario: ' .$user_delete['name'],
                    'created_at'    => $now,
                    'updated_at'    => $now
                ));
                $this->user->delete($id); 
            }
            
            return JsonResponse::singleResponse([ "message" => "El usuario ha sido eliminado." ]);
        } catch (ModelNotFoundException $exception) {
            \Log::error("Eliminando usuario...", [
                "model"   => $exception->getModel(),
                "message" => $exception->getMessage(),
                "code"    => $exception->getCode()
            ]);

            return JsonResponse::errorResponse("No es posible eliminar el usuario, informacion no encontrado.", 404);
        }

    }

    // Actualización de Contraseña Usuario
    public function updatePassword(Request $request,$user_id )
    {
        $data = $request->all();
        $now = Carbon::now('America/Mexico_City');
        $user_pass = $this->user->find($user_id);

        $pass_anterior = array_get($request, 'password_anterior');
        $pass_anterior2 = Hash::check($pass_anterior, $user_pass['password']);

        //var_dump($user['password']);
        //var_dump($pass_anterior2);

        if($pass_anterior2 == true){
            DB::table('users')->where('id', $user_id)
                ->update(array(
                'password'      => Hash::make(array_get($request, 'password')),
                'updated_at'    => $now
            ));
        }else{
            return JsonResponse::errorResponse("No es posible cambiarla, password anterior es incorrecto.", 404);
        }

        $user = Auth::user();
                $this->internal->create(array(
                'user_id'   => $user['id'],
                'evento'    => 'El usuario: '.$user['name']. ' ha actualizado la contraseña del usuario: ' .$user_pass['name'],
                'created_at'    => $now,
                'updated_at'    => $now
            ));

        return JsonResponse::singleResponse(["message" => "Se ha actualizado la contraseña del Usuario"]);

    }
}
