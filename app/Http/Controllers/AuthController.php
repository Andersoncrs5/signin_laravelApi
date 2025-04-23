<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogingRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class AuthController extends Controller
{

    public function findUserByEmail(string $email)
    {
        try
        {
            if (!$email)
            {
                throw new HttpResponseException(response()->json([
                    'message' => 'Email is required'
                ], 400));
            }

            $email = strtolower(trim($email));
            return User::where('email', $email)->first();
        }
        catch (\Throwable $th) 
        {
            throw new HttpResponseException(response()->json([
                'message' => $th
            ], 500));
        }
    }

    public function register(RegisterRequest $r)
    {
        try
        {
            DB::beginTransaction();

            $data = $r->all();

            $user = $this->findUserByEmail($data['email']);

            if ($user) 
            {
                throw new HttpResponseException(response()->json([
                    'message' => 'Email in used'
                ], 409));
            }

            $data['password'] = Hash::make(trim($data['password']));

            User::create($data);

            $user = $this->findUserByEmail($data['email']);
            
            $UserControl = new UserController();
            $UserControl->isUserNull($user);

            session()->put("active", true);
            session()->put("email", $user->email);
            session()->put("id", $user->id);

            DB::commit();
            return response()->json($data, 201);
        }
        catch (\Throwable $th) 
        {
            DB::rollBack();
            throw new HttpResponseException(response()->json([
                'message' => $th
            ], 500));
        }
    }

    public function login(LogingRequest $r)
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
                throw new HttpResponseException(response()->json([
                ], 401));
            }

            session()->put("active", true);
            session()->put("email", $user->email);
            session()->put("id", $user->id);

            return response()->json("OK", 200);          
        }
        catch (\Throwable $th) 
        {
            throw new HttpResponseException(response()->json([
                'message' => $th
            ], 500));
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
            throw new HttpResponseException(response()->json([
                'message' => $th
            ], 500));
        }
    }

}
