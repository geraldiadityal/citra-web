<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitraClientRequest;
use App\Models\CitraClient;
use App\Models\CitraService;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CitraClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {

            $query = CitraClient::with(['user', 'service']);
            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '<a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none
                            hover:bg-gray-800 focus:outline-none focus:shadow-outline" href="' . route('dashboard.client.edit', $item->id) . '">Edit</a>
                            <form class="inline-block" action="' . route('dashboard.client.destroy', $item->id) . '" method="POST">
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                        Hapus
                    </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.dashboard.client.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users_id = User::all();
        $services_id = CitraService::all();
        return view('pages.dashboard.client.create', compact('users_id', 'services_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CitraClientRequest $request)
    {
        $data = $request->all();

        CitraClient::create($data);

        return redirect()->route('dashboard.client.index')->with('success', 'Citra Client has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CitraClient  $client
     * @return \Illuminate\Http\Response
     */
    public function show(CitraClient $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CitraClient  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(CitraClient $client)
    {
        $users_id = User::all();
        $services_id = CitraService::all();
        return view('pages.dashboard.client.edit', [
            'item' => $client,
            'users_id' => $users_id,
            'services_id' => $services_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CitraClient  $client
     * @return \Illuminate\Http\Response
     */
    public function update(CitraClientRequest $request, CitraClient $client)
    {
        $data = $request->all();

        $client->update($data);

        return redirect()->route('dashboard.client.index')
            ->with('success', 'Citra Client has been update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CitraClient  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(CitraClient $client)
    {
        $client->delete();
        return redirect()->route('dashboard.client.index')->with(
            'success',
            'Citra Client has been deleted'
        );
    }
}
