<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\UsersInRoom;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Room::class);
        $rooms = Room::all();
        return response()->json($rooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        Gate::authorize('create', Room::class);
        $data = $request->validated();

        // dd($data);
        Room::create($data);

        return response()->json([
            'data' => [
                'message' => 'Created'
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        Gate::authorize('delete', $room);
        
        $room->delete();
        return response([
            'data' => [
                'message' => 'Deleted'
            ]
        ]);
    }

    public function getUsersInRooms()
    {
        Gate::authorize('viewAny', User::class);
        Gate::authorize('viewAny', Room::class);

        $rooms = Room::with('users')->get();

        return UsersInRoom::collection($rooms);
    }
}
