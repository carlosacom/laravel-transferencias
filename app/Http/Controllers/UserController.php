<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('userAuth', array(
            'except' => [
                'login'
            ]
        ));
        $this->middleware('userAdmin', array(
            'only' => [
                'store',
            ]
        ));
    }
    public function login(Request $request) 
    {
        $jwtAuth = new JwtAuth('user');
        $dataRequest = array_map('trim',$request->all());
        $validate = \Validator::make($dataRequest, array(
            'email' => 'required|email|max:175|exists:users,email',
            'password' => 'required|string|min:8|max:21'
        ));
        if (!$validate->fails()) {
            $response = $jwtAuth->sign_up($dataRequest['email'], $dataRequest['password']);
        } else {
            $response = array(
                'status' => 400,
                'response' => array('errors' => $validate->errors())
            );
        }
        return response()->json($response['response'],$response['status']);
    }
    public function store(Request $request) 
    {
        try {
            $dataRequest = array_map('trim', $request->all());
            $validate = \Validator::make($dataRequest, array(
                'name' => 'required|string|min:2|max:175',
                'email' => 'required|email|max:175|unique:users,email',
                'phone' => 'nullable|string|min:5|max:25',
                'role_id' => 'required|numeric|exists:roles,id',
                'password' => 'required|string|min:8|max:21|confirmed',
            ));
            if (!$validate->fails()) {
                $user = new User();
                $user->role_id = $dataRequest['role_id'];
                $user->name = $dataRequest['name'];
                $user->email = $dataRequest['email'];
                $user->phone = isset($dataRequest['phone']) ? $dataRequest['phone'] : null ;
                $user->password = \Hash::make($dataRequest['password']);
                $user->save();
                $response = array(
                    'status' => 200,
                    'response' => $user
                );
            } else {
                $response = array(
                    'status' => 400,
                    'response' => array('errors' => $validate->errors())
                );
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => 500,
                'response' => array('errors' => 'Error en el servidor' . $e->getMessage())
            );
        }
        return response()->json($response['response'],$response['status']);
    }
}
