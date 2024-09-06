<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    function createAdmin(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|string'
        ]);
        $data['role'] = 'admin';


        User::create($data);

        return response()->json([
            'data' => ['message' => 'Administrator created']
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        Gate::authorize('create', User::class);
        $data = $request->validated();
        $data['room_id'] = $data['id_childdata'];
        unset($data['id_childdata']);
        User::create($data);

        return response([
            'data' => [
                'message' => 'Created'
            ]
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        $data = $request->validated();
        User::where('id', $user->id)->update($data);

        return response([
            'data' => [
                'id' => $user->id,
                'message' => 'Updated'
            ]
        ]);
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();

        return response([
            'data' => [
                'message' => 'Deleted'
            ]
        ]);
    }

    public function changeRoom(Room $new_room, User $user)
    {
        Gate::authorize('update', $user);
        $user->update([
            'room_id' => $new_room->id
        ]);

        return response([
            'data' => [
                'message' => 'Changed'
            ]
        ]);
    }

    
}
