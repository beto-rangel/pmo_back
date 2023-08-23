<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\helpers\JsonResponse;
use Carbon\Carbon;
use App\Repositories\Eloquent\InternalEventRepository as Internal;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\JWTTrait;
use DB;

class APILoginController extends Controller
{
     public function __construct(Internal $internal)
    {
        $this->internal = $internal;
    }

    use JWTTrait;

    public function login() {
        // get email and password from request
        $credentials = request(['email', 'password']);
        $id = User::where('email', $credentials["email"])->get()->all();
        
        // try to auth and get the token using api authentication
        if (!$token = auth('api')->attempt($credentials)) {
            // if the credentials are wrong we send an unauthorized error in json format
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $now = Carbon::now('America/Mexico_City');
        if($id[0]){ 
            $this->internal->create(array(
                'user_id'       => $id[0]->id,
                'evento'        => 'El usuario: '.$id[0]->name. ' ha iniciado sesión',
                'created_at'    => $now,
                'updated_at'    => $now
            ));
        }
        return response()->json([
            'token' => $token,
            'type' => 'bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 4800, // time to expiration
            "Usuario" =>($id[0]->toArray())
            
        ]);

    }

    public function getPrueba(){

        $data = DB::select("SELECT a.atm,c.name,b.paso, a.created_at,concat('/',a.id_unico,'/',b.paso,'/',d.url) as url,a.comentario from comentarios a 
                                    LEFT JOIN cat_pasos_evidencia b 
                                    on a.paso=b.id
                                    LEFT JOIN users c
                                    on a.user_id=c.id
                                    LEFT JOIN evidencias d
                                    on a.id_unico=d.id_unico and  a.paso=d.paso");
        
        return JsonResponse::singleResponse(["message" => "Info encontrada" , 
            "Data" => $data, 
        ]);
    } 
}
