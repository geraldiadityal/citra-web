<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CitraClient;
use Exception;
use Illuminate\Http\Request;

class CitraClientController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');

        $users_id = $request->input('user_id');
        $services_id = $request->input('service_id');

        if ($id) {
            $client = CitraClient::with(['user', 'service'])->find($id);

            if ($client) {
                return ResponseFormatter::success($client, 'Data client berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data client tidak ada', 404);
            }
        }

        $client = CitraClient::with(['user', 'service']);

        if ($users_id) {
            $client->where('users_id', $users_id);
        }
        if ($services_id) {
            $client->where('services_id', $services_id);
        }

        return ResponseFormatter::success($client->paginate($limit), 'Data client berhasil diambil');
    }
    public function create(Request $request)
    {
        try {
            $request->validate(
                [
                    'users_id' => 'required|exists:users,id',
                    'services_id' => 'required|exists:services,id',
                    'description' => 'required'
                ]
            );

            $client = CitraClient::create([
                'users_id' => $request->users_id,
                'services_id' => $request->services_id,
                'description' => $request->description,
            ]);
            return ResponseFormatter::success($client, 'Citra Client berhasil dibuat');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Citra Client gagal dibuat');
        }
    }
}
