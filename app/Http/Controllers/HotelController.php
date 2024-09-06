<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Http\Resources\HotelResource;
use App\Http\Resources\UsersInRoomsInHotel;
use App\Models\Room;
use Illuminate\Support\Facades\Gate;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Hotel::class);
        $hotels = Hotel::all();
        return HotelResource::collection($hotels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelRequest $request)
    {
        Gate::authorize('create', Hotel::class);
        $data = $request->validated();

        $new_hotel = Hotel::create($data);
        return response(new HotelResource($new_hotel), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        Gate::authorize('update', $hotel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        Gate::authorize('delete', $hotel);
        $hotel->delete();

        return response([
            'data' => [
                'message' => 'Deleted'
            ]
        ]);
    }

    public function addRoom(Hotel $hotel, Room $room)
    {
        Gate::authorize('update', $room);
        
        $room->update([
            'hotel_id' => $hotel->id
        ]);

        return response([
            'data' => [
                'name' => $room->name,
                'title' => $room->desc_data
            ]
        ]);
    }
    public function getUsersInRoomsInHotels()
    {
        $hotels = Hotel::with('rooms.users')->get();
        return UsersInRoomsInHotel::collection($hotels);
    }
}
