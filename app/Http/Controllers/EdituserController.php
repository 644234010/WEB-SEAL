<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EdituserController extends Controller
{
    public function userall()
    {
        $users = User::paginate(5);
        return view('edituser.userall', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edituser.edituse', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // Add validation rules for other fields as needed
        ]);

        $user->update($request->all());

        return redirect()->route('edituser.userall', ['id' => $user->id]);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate(5);
    
        if ($request->ajax()) {
            return response()->json([
                'users' => view('edituser.user_list', compact('users'))->render(),
                'pagination' => view('edituser.pagination', compact('users'))->render(),
            ]);
        }
    
        return view('edituser.userall', compact('users', 'query'));
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    
        return redirect()->route('edituser.userall')->with('status', 'User deleted successfully');
    }
}
