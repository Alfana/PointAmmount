<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nasabah;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DataNasabah extends Controller
{
    public function data_nasabah(){
        return ['data_nasabah'=>Nasabah::all()];
    }
    
    public function store(Request $request)
    {   
        $token = Str::random(60);

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
            'password' => 'required|string',
          ]);
      
          if ($validator->fails()) {
            return $this->failed($validator->errors(),'Data tidak lengkap');
          }else{
            return User::forceCreate([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'api_token' => hash('sha256', $token)
            ]);
          }
    }
}
