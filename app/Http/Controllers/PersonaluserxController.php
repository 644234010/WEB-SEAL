<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;

class PersonaluserxController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('myprofileuser.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('myprofileuser.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'address1' => 'required|string|max:1000',
            'address2' => 'required|string|max:1000',
            'address3' => 'required|string|max:1000',
            'phone_number' => 'required|string|max:11',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->address1 = $request->address1;
        $user->address2 = $request->address2;
        $user->address3 = $request->address3;
        $user->phone_number = $request->phone_number;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('myprofileuser.show')->with('success', 'ข้อมูลถูกอัปเดตเรียบร้อยแล้ว');
    }
}
