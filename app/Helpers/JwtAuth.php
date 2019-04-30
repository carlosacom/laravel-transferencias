<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use App\User;

class JwtAuth {
    private $pass;
    private $role_app;

    public function __construct(String $user)
    {
        $this->role_app = $user;
        switch ($user) {
            case 'user':
                $this->pass = 'Contraseña-Super-mega-secretaaskdlaksñdlakñsdlkañslkd';
                break;
            default : 
                $this->pass = null;
        }
    }

    public function sign_up(String $email, String $password) 
    {
        switch ($this->role_app) {
            case 'user' :
                $user = User::where(array(
                    ['email', $email],
                    ['enable', true]
                ))->first();
                break;
            default :
                $user = false;
        }
        if ($user) {
            if (\Hash::check($password, $user->password)) {
                $dataToken = array(
                    'sub' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'role' => $this->role_app, 
                    'iat' => time(),
                    'exp' => time() + (60 * 60 * 24)
                );
                $token = JWT::encode($dataToken, $this->pass, 'HS256');
                $response= array(
                    'status' => 200,
                    'response' => array('user' => $dataToken, 'token' => $token),
                );
            } else {
                $response = array(
                    'status' => 404,
                    'response' => array('errors' => 'Usuario o contraseña incorrecto'),
                );
            }
        } else {
            $response = array(
                'status' => 404,
                'response' => array('errors' => 'Usuario o contraseña incorrecto'),
            );
        }
        return $response;
    }
    public function checkToken(String $token)
    {
        try {
            $decoded = JWT::decode($token, $this->pass, ['HS256']);
            $response = ($decoded) ? $decoded : false;
        } catch(\UnexpectedValueException $e) {
                $response = false;
        } catch(\DomainException $e) {
                $response = false;
        }
        return $response;
    }
}
