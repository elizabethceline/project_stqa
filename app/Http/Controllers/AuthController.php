<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function indexAdmin()
    {
        return view('admin.login', [
            'title' => 'Login Page'
        ]);
    }

    public function loginAdmin(Request $request)
    {
        $validator = [
            'email' => 'required|string|exists:admins,email|',
            'password' => 'required|string'
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak ditemukan',
        ];

        $attributes = [
            'email' => 'E-mail',
            'password' => 'Password',
        ];

        $valid = Validator::make($request->only(['email', 'password']), $validator, $messages, $attributes);

        if ($valid->fails()) {
            return back()->with('error', $valid->errors()->first());
        }

        $pass = Admin::where('email', $request->email)->first()->password;

        if (Hash::check($request->password, $pass)) {
            session([
                'admin' => $request->email,
            ]);
            return redirect()->route('admin.home')->with('success', 'Login Berhasil');
        } else {
            return back()->with('error', 'Password Salah');
        }
    }
}
