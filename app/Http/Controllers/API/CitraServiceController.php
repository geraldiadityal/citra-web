<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CitraService;
use Illuminate\Http\Request;

class CitraServiceController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');

        if ($id) {
            $service = CitraService::with(['partners', 'questions', 'clients'])->find($id);

            if ($service) {
                return ResponseFormatter::success($service, 'Data services berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data services tidak ada', 404);
            }
        }

        $service = CitraService::with(['partners', 'questions', 'clients']);

        return ResponseFormatter::success($service->paginate($limit), 'Data services berhasil diambil');
    }
}
