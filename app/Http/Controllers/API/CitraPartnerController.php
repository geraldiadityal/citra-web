<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CitraPartner;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;

class CitraPartnerController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');

        $users_id = $request->input('user_id');
        $services_id = $request->input('service_id');

        if ($id) {
            $partner = CitraPartner::with(['chats', 'user', 'service'])->find($id);

            if ($partner) {
                return ResponseFormatter::success($partner, 'Data partner berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data partner tidak ada', 404);
            }
        }

        $partner = CitraPartner::with(['chats', 'user', 'service']);

        if ($users_id) {
            $partner->where('users_id', $users_id);
        }
        if ($services_id) {
            $partner->where('services_id', $services_id);
        }

        return ResponseFormatter::success($partner->paginate($limit), 'Data partner berhasil diambil');
    }
}
