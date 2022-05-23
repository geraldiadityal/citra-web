<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomChatRequest;
use App\Models\CitraPartner;
use App\Models\RoomChat;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoomChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $query  = RoomChat::with(['user', 'partner']);

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '<a class="inline-block border border-blue-700 bg-blue-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-blue-800 focus:outline-none focus:shadow-outline" 
                    href="' . route('dashboard.room.show', $item->id) . '">
                    Show</a>
                    <a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none
                            hover:bg-gray-800 focus:outline-none focus:shadow-outline" href="' . route('dashboard.room.edit', $item->id) . '">Edit</a>
                            <form class="inline-block" action="' . route('dashboard.room.destroy', $item->id) . '" method="POST">
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                        Hapus
                    </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.dashboard.room.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partners_id = CitraPartner::all();
        $users_id = User::all();
        return view('pages.dashboard.room.create', compact('partners_id', 'users_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomChatRequest $request)
    {
        $data = $request->all();

        RoomChat::create($data);
        return redirect()->route('dashboard.room.index')->with('success', 'Room Chat has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoomChat  $room
     * @return \Illuminate\Http\Response
     */
    public function show(RoomChat $room)
    {


        $partner = CitraPartner::with(['user'])->find($room->partners_id);

        return view('pages.dashboard.room.show', compact('room', 'partner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RoomChat  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(RoomChat $room)
    {
        $old_partner = CitraPartner::with(['user'])->find($room->partners_id);
        $partners_id = CitraPartner::all();
        $users_id = User::all();
        return view('pages.dashboard.room.edit', [
            'item' => $room,
            'old_partner' => $old_partner,
            'users_id' => $users_id,
            'partners_id' => $partners_id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoomChat  $room
     * @return \Illuminate\Http\Response
     */
    public function update(RoomChatRequest $request, RoomChat $room)
    {
        $data = $request->all();

        $room->update($data);
        return redirect()->route('dashboard.room.index')
            ->with('success', 'Room Chat has been update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoomChat  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoomChat $room)
    {
        $room->delete();
        return redirect()->route('dashboard.room.index')->with(
            'success',
            'Room Chat has been deleted'
        );
    }
}
