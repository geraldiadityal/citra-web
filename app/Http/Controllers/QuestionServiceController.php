<?php

namespace App\Http\Controllers;

use App\Models\CitraService;
use App\Models\QuestionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuestionServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CitraService $service)
    {

        if (request()->ajax()) {
            $query = QuestionService::where('services_id', $service->id);

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '<a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none
                            hover:bg-gray-800 focus:outline-none focus:shadow-outline" href="' . route('dashboard.question.edit', $item->id) . '">
                            Edit</a>
                            <form class="inline-block" action="' . route('dashboard.question.destroy', $item->id) . '" method="POST">
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                        Hapus
                    </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.dashboard.question.index', compact('service'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CitraService $service)
    {
        return view('pages.dashboard.question.create', compact('service'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CitraService $service)
    {

        QuestionService::create([
            'services_id' => $service->id,
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return redirect()->route('dashboard.service.question.index', $service->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionService  $questionService
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionService $questionService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionService  $questionService
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionService $question)
    {
        return view('pages.dashboard.question.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionService  $questionService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionService $question)
    {
        $data = $request->all();

        $question->update($data);
        return redirect()->route('dashboard.service.question.index', $question->services_id)->with(
            'success',
            'Question Service has been updated'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionService  $questionService
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionService $question)
    {
        $question->delete();

        return redirect()->route('dashboard.service.question.index', $question->services_id);
    }
}
