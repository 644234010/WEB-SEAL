<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('myprofile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('myprofile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'phone_number' => 'required|string|max:11',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('myprofile.show')->with('success', 'ข้อมูลถูกอัปเดตเรียบร้อยแล้ว');
    }
}
