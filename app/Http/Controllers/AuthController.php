<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class AuthController extends Controller
{

    private function findUserByEmail(string $email)
    {
        try
        {
            $email = strtolower(trim($email));
            return User::where('email', $email)->first();
        }
        catch (\Throwable $th) 
        {
            return response()->json($th, 500);
        }
    }

    public function register(Request $r)
    {
        try
        {
            DB::beginTransaction();

            $data = $r->all();

            $user = $this->findUserByEmail($data['email']);

            if ($user) 
            {
                return response()->json("Email in use!", 409);
            }

            $data['password'] = Hash::make(trim($data['password']));

            User::create($data);

            $user = $this->findUserByEmail($data['email']);
            
            $UserControl = new UserController();

            $UserControl->isUserNull($user);

            session()->put("active", true);
            session()->put("id", $user->id);

            DB::commit();
            return response()->json($data, 201);
        }
        catch (\Throwable $th) 
        {
            DB::rollBack();
            return response()
                ->json($th, 500);
        }
    }

    public function login(Request $r)
    {
        try
        {
            $data = $r->all();

            $data['password'] = trim($data['password']);

            $user = $this->findUserByEmail($data['email']);

            $UserControl = new UserController();
            $UserControl->isUserNull($user, 401);

            if(!Hash::check($data['password'], $user->password))
            {
                return response()->json("", 401);
            }

            return response()->json("OK", 200);          
        }
        catch (\Throwable $th) 
        {
            return response()
                ->json($th, 500);
        }
    }

    public function logout()
    {
        try
        {
            session()->flush();
            return response()->json("ok", 200);
        }
        catch (\Throwable $th) 
        {
            return response()->json($th, 500);
        }
    }

}
