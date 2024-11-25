<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function showCustomers()
    {
        $customers = Customer::with('books')->get();
        // dd($customers->toArray());
        return view('admin.customers', compact('customers'));
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }
}
