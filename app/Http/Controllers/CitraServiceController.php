<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitraServiceRequest;
use App\Models\CitraService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CitraServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = CitraService::query();

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                    <a class="inline-block border border-blue-700 bg-blue-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-blue-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('dashboard.service.question.index', $item->id) . '">
                            Question
                        </a>
                    <a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none
                            hover:bg-gray-800 focus:outline-none focus:shadow-outline" href="' . route('dashboard.service.edit', $item->id) . '">Edit</a>
                            <form class="inline-block" action="' . route('dashboard.service.destroy', $item->id) . '" method="POST">
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                        Hapus
                    </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.dashboard.service.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.dashboard.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CitraServiceRequest $request)
    {
        $data = $request->all();

        CitraService::create($data);

        return redirect()->route('dashboard.service.index')->with('success', 'Citra Service has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CitraService  $service
     * @return \Illuminate\Http\Response
     */
    public function show(CitraService $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CitraService  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(CitraService $service)
    {
        return view('pages.dashboard.service.edit', [
            'item' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CitraService  $service
     * @return \Illuminate\Http\Response
     */
    public function update(CitraServiceRequest $request, CitraService $service)
    {
        $data = $request->all();

        $service->update($data);

        return redirect()->route('dashboard.service.index')->with(
            'success',
            'Citra Service has been updated'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CitraService  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(CitraService $service)
    {
        $service->delete();

        return redirect()->route('dashboard.service.index')->with(
            'success',
            'Citra Service has been deleted'
        );
    }
}
