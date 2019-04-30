<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reseller;

class ResellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('userAuth');
        $this->middleware('userAdmin', array(
            'only' => ['store', 'destroy', 'update']
        ));
    }
    public function index()
    {
        return response()->json(Reseller::where('enable', true)->get()->load('discount_actual'));
    }
    public function show(String $id)
    {
        $reseller = Reseller::where(array(
            ['enable', 1],
            ['id', $id]
        ))->first();
        if ($reseller) {
            $response = array(
                'status' => 200,
                'response' => $reseller->load('discount_actual')
            );
        } else {
            $response = array(
                'status' => 404,
                'response' => array('errors' => 'No existe el reseller')
            );
        }
        return response()->json($response['response'],$response['status']);
    }
    public function store(Request $request)
    {
        try {
            $dataRequest = array_map('trim', $request->all());
            $validate = \Validator::make($dataRequest, array(
                ''
            ));
        } catch (\Exception $e) {
            $response = array(
                'status' => 500,
                'response' => array('errors' => 'Error en el servidor ' . $e->getMessage())
            );
        }
        return response()->json($response['response'],$response['status']);
    }
}
