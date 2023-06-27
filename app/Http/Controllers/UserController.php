<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Memoir;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with(['roles', 'subarea', 'subarea.area'])->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with(['memoir', 'memoir.team'])->find($id);
        $progress = Progress::where('owner', '=', $user->name)->with(['progressable', 'progressable.activity'])->get();
        return response()->json([
            'user' => $user,
            'progress' => $progress
        ]);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'subarea_id' => $request->get('subarea_id')
        ]);

        $user->assignRole($request->get('rol'));

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function destroy($id)
    {

        $user = User::find($id);
        $user->delete();

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function assignTeam(Request $request)
    {
        $user = User::find($request->get('owner_id'));
        $memoir = $user->memoir;

        $memoir->update([
            'team_id' => $request->get('team_id')
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function getSellers()
    {
        $users = User::where('subarea_id', 2)->get();
        return response()->json($users);
    }
}
