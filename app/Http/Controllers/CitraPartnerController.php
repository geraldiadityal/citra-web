<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitraPartnerRequest;
use App\Models\CitraPartner;
use App\Models\CitraService;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class CitraPartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = CitraPartner::with(['user', 'service']);

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '<a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none
                            hover:bg-gray-800 focus:outline-none focus:shadow-outline" href="' . route('dashboard.partner.edit', $item->id) . '">Edit</a>
                            <form class="inline-block" action="' . route('dashboard.partner.destroy', $item->id) . '" method="POST">
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                        Hapus
                    </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.dashboard.partner.index');
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
        return view('pages.dashboard.partner.create', compact('users_id', 'services_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CitraPartnerRequest  $CitraPartnerRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CitraPartnerRequest $request)
    {
        $data = $request->all();

        $partner = CitraPartner::create($data);
        $user = User::findOrFail($partner->users_id);
        $user->roles = "PARTNER";
        $user->save();

        return redirect()->route('dashboard.partner.index')->with('success', 'Citra Partner has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CitraPartner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(CitraPartner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CitraPartner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(CitraPartner $partner)
    {
        $users_id = User::all();
        $services_id = CitraService::all();
        return view('pages.dashboard.partner.edit', [
            'item' => $partner,
            'users_id' => $users_id,
            'services_id' => $services_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CitraPartnerRequest  $CitraPartnerRequest
     * @param  \App\Models\CitraPartner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(CitraPartnerRequest $request, CitraPartner $partner)
    {
        $data = $request->all();

        $partner->update($data);

        return redirect()->route('dashboard.partner.index')
            ->with('success', 'Citra Partner has been update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CitraPartner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(CitraPartner $partner)
    {
        $user = User::findOrFail($partner->users_id);
        $user->roles = "USER";
        $user->save();
        $partner->delete();
        return redirect()->route('dashboard.partner.index')->with(
            'success',
            'Citra Partner has been deleted'
        );
    }
}
