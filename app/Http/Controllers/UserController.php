<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function createAdmin(Request $request) {
        // dd(123);
        $data = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|string'
        ]);
        // dd(123);
        $data['role'] = 'admin';

        
        User::create($data);

        return response()->json([
            'data' => ['message' => 'Administrator created']
        ]);
    }
}
