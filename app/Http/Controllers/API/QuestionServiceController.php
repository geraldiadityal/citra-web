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

            if ($question_service) {
                return ResponseFormatter::success($question_service, 'Data question berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data question tidak ada', 404);
            }
        }

        $question_service = QuestionService::query();

        if ($services_id) {
            $question_service->where('services_id', $services_id);
        }
        if ($question) {
            $question_service->where('question', 'like', '%' . $question . '%');
        }
        if ($answer) {
            $question_service->where('status', 'like', '%' . $answer . '%');
        }

        return ResponseFormatter::success($question_service->get(), 'Data partner berhasil diambil');
    }
}
