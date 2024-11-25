<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customer;
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

    public function logoutAdmin()
    {
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function indexUser()
    {
        return view('user.login', [
            'title' => 'Login Page'
        ]);
    }

    public function loginUser(Request $request)
    {
        $validator = [
            'email' => 'required|string|exists:customers,email|',
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

        $pass = Customer::where('email', $request->email)->first()->password;

        if (Hash::check($request->password, $pass)) {
            session([
                'customer' => Customer::where('email', $request->email)->first()->id,
            ]);
            return redirect()->route('user.home')->with('success', 'Login Berhasil');
        } else {
            return back()->with('error', 'Password Salah');
        }
    }

    public function logoutUser()
    {
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('user.login');
    }
}
