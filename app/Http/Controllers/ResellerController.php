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
                'name' => 'required|string|min:2|max:175',
                'email' => 'required|email|max:175|unique:resellers,email',
                'document' => 'required|string|min:5|max:20|unique:resellers,document',
                'phone' => 'nullable|string|min:5|max:25',
                'whatsapp' => 'nullable|string|min:5|max:25',
                'minimum_value' => 'required|numeric',
                'password' => 'required|string|min:8|max:21|confirmed'
            ));
            if (!$validate->fails()) {
                $reseller = new Reseller();
                $reseller->name = $dataRequest['name'];
                $reseller->email = $dataRequest['email'];
                $reseller->document = $dataRequest['document'];
                $reseller->phone = isset($dataRequest['phone']) ? $dataRequest['phone'] : null ;
                $reseller->whatsapp = isset($dataRequest['whatsapp']) ? $dataRequest['whatsapp'] : null ;
                $reseller->minimum_value = $dataRequest['minimum_value'];
                $reseller->password = \Hash::make($dataRequest['password']);
                $reseller->save();
                $response = array(
                    'status' => 200,
                    'response' => $reseller
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
                'response' => array('errors' => 'Error en el servidor ' . $e->getMessage())
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
                'email' => 'required|email|max:175|unique:resellers,email,' . $id,
                'document' => 'required|string|min:5|max:20|unique:resellers,document, '. $id,
                'phone' => 'nullable|string|min:5|max:25',
                'whatsapp' => 'nullable|string|min:5|max:25',
                'minimum_value' => 'required|numeric'
            ));
            if (!$validate->fails()) {
                $reseller = Reseller::find($id);
                if ($reseller) {
                    $reseller->name = $dataRequest['name'];
                    $reseller->email = $dataRequest['email'];
                    $reseller->document = $dataRequest['document'];
                    $reseller->phone = isset($dataRequest['phone']) ? $dataRequest['phone'] : null ;
                    $reseller->whatsapp = isset($dataRequest['whatsapp']) ? $dataRequest['whatsapp'] : null ;
                    $reseller->minimum_value = $dataRequest['minimum_value'];
                    $reseller->update();
                    $response = array(
                        'status' => 200,
                        'response' => $reseller
                    );
                } else {
                    $response = array(
                        'status' => 404,
                        'response' => array('errors' => 'No existe el reseller')
                    );
                }
            } else {
                $response = array(
                    'status' => 400,
                    'response' => array('errors' => $validate->errors())
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
    public function destroy(String $id)
    {
        try {
            $reseller = Reseller::find($id);
            if ($reseller) {
                $reseller->enable = false;
                $reseller->update();
                $response = array(
                    'status' => 200,
                    'response' => $reseller
                );
            } else {
                $response = array(
                    'status' => 404,
                    'response' => array('errors' => 'No existe el reseller')
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
