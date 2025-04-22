<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function update(Request $r)
    {
        try
        {
            DB::beginTransaction();

            DB::commit();
            return response()->json("ok", 200);
        }
        catch (\Throwable $th) 
        {
            DB::rollBack();
            return response()->json($th, 500);
        }
    }

    public function isUserNull($user, $status = 404)
    {
        if ($user == null) {
            return response()->json('user not found', $status);
        }
        
        return null;
    }

    function get()
    {
        try
        {
            $user = User::find(session('id'));

            $this->isUserNull($user);

            return response()->json($user, 200);
        }
        catch (\Throwable $th) 
        {
            return response()->json($th, 500);
        }
    }

    function delete()
    {
        try
        {
            DB::beginTransaction();
        
            $user = User::find(session('id'));
            $this->isUserNull($user);
            $user->forceDelete();

            $AuthControl = new AuthController();
            $AuthControl->logout();

            DB::commit();
            return response()->json("ok", 200);
        }
        catch (\Throwable $th) 
        {
            DB::rollBack();
            return response()->json($th, 500);
        }
    }



}
