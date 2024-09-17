<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\SiswaUpdateRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UserAdminRequest;
use App\Http\Resources\AdminResponse;
use App\Http\Resources\SiswaResponse;
use App\Models\UserAdmin;
use App\Models\Siswa;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //

    public function store(AdminRequest $request): JsonResponse 
    {
        $data = $request->validated();

        if(UserAdmin::where('username', $data["username"])->count()==1 ){
            throw new HttpResponseException(response([
                'errors' => [
                    "message"=>[
                        "Username has been used."
                    ]
                ]
                    ], 400));
        }

        $admin = new UserAdmin($data);
        $admin->password = Hash::make($data['password']);
        $admin->save();

        return response()->json([
            $admin
        ], 201);
    } 

    public function login(UserAdminRequest $request) : JsonResponse 
    {
        $data = $request->validated();
        $user = UserAdmin::where("username", $data["username"])->first();

        if(!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors"=>[
                    "message"=>[
                        "Username or password is wrong."
                    ]
                ]
            ], 401));
        }
        $user->token = Str::uuid()->toString();
        $user->save(); 
        return response()->json([
            "username" => $user->username,
            "token" => $user->token,
        ], 201);
    }
  
    public function get(Request $request): JsonResponse
    {
        $admin = Auth::user();
        return response()->json([
            $admin
        ], 200);
    }

    // public function getAdmin(int $id): AdminResponse
    // {
    //     $admin = UserAdmin::find($id);
    //     if(!$admin){
    //         throw new HttpResponseException(response([
    //             "errors"=>[
    //                 "message" => [
    //                     "Not found."
    //                 ]
    //             ]
    //                 ],404));
    //     }
    //     return new AdminResponse($admin);
    // }

    // public function updateSiswaByAdmin(SiswaUpdateRequest $request, int $id) : JsonResponse
    // {
    //     // validasi data 
    //     $data = $request->validated();
    //     $siswa = Siswa::with("kelas:id,kelas")->find($id);

    //     if(!$siswa){
    //         throw new HttpResponseException(response([
    //             "errors"=>[
    //                 "message" => [
    //                     "Not found."
    //                 ]
    //             ]
    //                 ],404));
    //     }

    //     if(isset($data['password'])){
    //         $data["password"] = Hash::make($data['password']);
    //     }else {
    //         // If password is not set, remove it from the data array
    //         unset($data['password']);
    //     }
    //     $siswa->fill($data);
    //     $siswa->save();

    //     return (new SiswaResponse($siswa))->response()->setStatusCode(201);
    // }

    public function update(UpdateAdminRequest $request, int $id) : JsonResponse
    {

        @dd($request);
        $data = $request->validated();

        $admin = UserAdmin::find($id);
        if(isset($data['username'])){
            $admin->username = $data['username'];
        }
        if(isset($data['password'])){
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();
        return response()->json([
            $admin
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $admin = UserAdmin::find(Auth::user()->id);
        $admin->token = null;
        $admin->save();
        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }

    public function getAllAdmin() : JsonResponse {
        $admins = UserAdmin::select('id', 'username', 'password')->get();
        return response()->json([
           $admins
        ], 200);
    }

    public function delete(int $id): JsonResponse {
        $data = UserAdmin::find($id);

        if(!$data){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message" => [
                        "Not found."
                    ]
                ]
                    ],404));
        }

        $data->delete();
        $admins = UserAdmin::select('id', 'username', 'password')->get();
        return response()->json([
            $admins
        ], 200);
    }
}
