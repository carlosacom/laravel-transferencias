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
                'show',
                'index',
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
    public function index()
    {
        return User::where('enable', true)->get()->load('role');
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
    public function show(String $id)
    {
        $user = User::find($id);
        if ($user) {
            $response = array(
                'status' => 200,
                'response' => $user->load('role')
            );
        } else {
            $response = array(
                'status' => 404,
                'response' => array('errors' => 'No existe el usuario')
            );
        }
        return response()->json($response['response'],$response['status']);
    }
    public function update(String $id, Request $request)
    {
        try {
            $dataRequest = array_map('trim', $request->all());
            $validate = \Validator::make($dataRequest, array(
                'name' => 'required|string|min:2|max:175',
                'email' => 'required|email|max:175|unique:users,email,' .$id,
                'phone' => 'nullable|string|min:5|max:25',
                'role_id' => 'required|numeric|exists:roles,id',
            ));
            if (!$validate->fails()) {
                $user = User::find($id);
                if ($user) {
                    $user->name = $dataRequest['name'];
                    $user->role_id = $dataRequest['role_id'];
                    $user->email = $dataRequest['email'];
                    $user->phone = isset($dataRequest['phone']) ? $dataRequest['phone'] : null ;
                    $user->update();
                    $response = array(
                        'status' => 200,
                        'response' => $user->load('role')
                    );
                } else {
                    $response = array(
                        'status' => 404,
                        'response' => array('errors' => 'No existe el usuario')
                    );
                }
            } else {
                $response = array(
                    'status' => 400,
                    'response' => array('errors' => $validate->errors())
                );
            }
        } catch (\Expetion $e) {
            $response = array(
                'status' => 500,
                'response' => array('errors' => 'Error en el servidor ' . $e->getMessage())
            );
        }
        return response()->json($response['response'],$response['status']);
    }
    public function destroy(String $id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->enable = false;
                $user->update();
                $response = array(
                    'status' => 200,
                    'response' => $user->load('role')
                );
            } else {
                $response = array(
                    'status' => 404,
                    'response' => array('errors' => 'No existe el usuario')
                );
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => 500,
                'response' => array('errors' => 'Error en el servidor ' . $e->getMessage())
            );
        }
        return response()->json($response['response'],$response['status']);
    }
}
