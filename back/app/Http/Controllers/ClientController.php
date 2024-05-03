<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Loan;
use Illuminate\Http\Request;

class ClientController extends Controller{
    public function index(){
        return Client::with('loans')->get();
    }
    public function store(Request $request){
        $client = Client::where('ci', $request->ci)->get();
        if(count($client) > 0){
            return response()->json(['message' => 'Ya existe un cliente con la cedula ingresada'], 400);
        }
        return Client::create($request->all());
    }
    public function update(Request $request, $id){
        $client = Client::findOrFail($id);
        $client->update($request->all());
        return $client;
    }
    public function delete(Request $request, $id){
        $loand = Loan::where('client_id', $id)->get();
        if(count($loand) > 0){
            return response()->json(['message' => 'No se puede eliminar el cliente, tiene prestamos asociados'], 400);
        }
        $client = Client::findOrFail($id);
        $client->delete();
        return 204;
    }
}
