<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EditinController extends Controller
{
    public function userall()
    {
        $users = User::where('type', 4)->paginate(5);

        return view('editinventory.userall', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        // Convert any potential array values to strings
        $user->address = is_array($user->address) ? json_encode($user->address) : $user->address;
        $user->address1 = is_array($user->address1) ? json_encode($user->address1) : $user->address1;
        $user->address2 = is_array($user->address2) ? json_encode($user->address2) : $user->address2;
        $user->address3 = is_array($user->address3) ? json_encode($user->address3) : $user->address3;
        
        return view('editinventory.edituse', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'required|string',
            'address1' => 'required|string',
            'address2' => 'required|string',
            'address3' => 'required|string',
            'phone_number' => 'required|string|max:10',
        ]);
    
        $user->update($request->all());
    
        return redirect()->route('editinventory.userall')->with('status', 'User updated successfully');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate(5);
    
        if ($request->ajax()) {
            return response()->json([
                'users' => view('editinventory.user_list', compact('users'))->render(),
                'pagination' => view('editinventory.pagination', compact('users'))->render(),
            ]);
        }
    
        return view('editinventory.userall', compact('users', 'query'));
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    
        return redirect()->route('editinventory.userall')->with('status', 'User deleted successfully');
    }
}
