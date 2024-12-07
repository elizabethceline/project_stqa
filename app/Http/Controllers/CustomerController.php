<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $name = Customer::where('id', session('customer'))->first()->name;
        return view('user.home', [
            'title' => 'User Page',
            'name' => $name
        ]);
    }

    public function showCustomers()
    {
        $customers = Customer::with('books')->get();
        // dd($customers->toArray());
        return view('admin.customers', compact('customers'));
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('admin.users')->with('error', 'User not found');
        }
        if ($customer->books->count() > 0) {
            return redirect()->route('admin.users')->with('error', 'User cannot be deleted because they still have books');
        }
        $customer->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    public function showProfile()
    {
        $customer = Customer::where('id', session('customer'))->first();
        return view('user.profile', compact('customer'));
    }

    public function create(Request $request)
    {
        $validator = [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|unique:customers,email|max:100',
            'password' => 'required|string',
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'email' => ':attribute harus berupa email',
            'unique' => ':attribute sudah terdaftar',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maksimal :max karakter',
        ];

        $attributes = [
            'name' => 'Name',
            'email' => 'E-mail',
            'password' => 'Password',
            'bio' => 'Bio',
        ];

        $valid = Validator::make($request->only(['name', 'email', 'password', 'bio']), $validator, $messages, $attributes);

        if ($valid->fails()) {
            return back()->with('error', $valid->errors()->first());
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = bcrypt($request->password);
        $customer->bio = $request->bio;
        $customer->save();

        return redirect()->route('user.login')->with('success', 'Account created successfully');
    }

    public function update(Request $request)
    {
        $validator = [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|max:100|unique:customers,email,' . session('customer'),
            'bio' => 'string',
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'email' => ':attribute harus berupa email',
            'unique' => ':attribute sudah digunakan',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maksimal :max karakter',
        ];

        $attributes = [
            'name' => 'Name',
            'email' => 'E-mail',
            'password' => 'Password',
            'bio' => 'Bio',
        ];

        $valid = Validator::make($request->only(['name', 'email', 'password', 'bio']), $validator, $messages, $attributes);

        if ($valid->fails()) {
            return back()->with('error', $valid->errors()->first());
        }

        $customer = Customer::find(session('customer'));
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = bcrypt($request->password);
        $customer->bio = $request->bio;
        $customer->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
    }
}
