<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\QuestionService;
use Illuminate\Http\Request;

class QuestionServiceController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');


        $services_id = $request->input('services_id');
        $question = $request->input('question');
        $answer = $request->input('answer');

        if ($id) {
            $question_service = QuestionService::find($id);

            if ($question) {
                return ResponseFormatter::success($question_service, 'Data question berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data question tidak ada', 404);
            }
        }

        $question_service = QuestionService::query();

        if ($services_id) {
            $question->where('services_id', $services_id);
        }
        if ($question) {
            $question->where('question', 'like', '%' . $question . '%');
        }
        if ($answer) {
            $question->where('status', 'like', '%' . $answer . '%');
        }

        return ResponseFormatter::success($question_service->paginate($limit), 'Data partner berhasil diambil');
    }
}
