<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function update(UpdateUserRequest $r)
    {
        try
        {
            $data = $r->all();
            DB::beginTransaction();

            $AuthControl = new AuthController();
            $user = $AuthControl->findUserByEmail(session("email"));

            $this->isUserNull($user);

            $data['email'] = session("email");

            $user->update($data);

            DB::commit();
            return response()->json("ok", 200);
        }
        catch (\Throwable $th) 
        {
            DB::rollBack();
            throw new HttpResponseException(response()->json([
                'message' => $th
            ], 500));
        }
    }

    public function isUserNull($user, $status = 404)
    {
        if (!$user) {
            throw new HttpResponseException(response()->json([
                'message' => 'User not found'
            ], $status));
        }
        
        return;
    }

    function get()
    {
        try
        {
            $id = (int) session("id");

            $user = User::find($id);

            $this->isUserNull($user);

            return response()->json($user, 200);
        }
        catch (\Throwable $th) 
        {
            throw new HttpResponseException(response()->json([
                'message' => $th
            ], 500));
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
            throw new HttpResponseException(response()->json([
                'message' => $th
            ], 500));
        }
    }



}
