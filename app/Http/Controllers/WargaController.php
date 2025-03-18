<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\WargaService;
use App\Models\Warga;

class WargaController extends Controller
{
    protected $wargaService;

    public function __construct(WargaService $wargaService)
    {
        $this->wargaService = $wargaService;
    }

    /**
     * Mendapatkan daftar warga berdasarkan parameter pencarian.
     */
    public function daftar(Request $request)
    {
        $user = Auth::user();
        $params = $request->all();

        try {
            $wargas = $this->wargaService->listWarga($params, $user)->get(); // Query + Ambil Data
            return response()->json($wargas, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Validasi data warga berdasarkan ID dan instansi.
     */
    public function validasiWarga($id, Request $request)
    {
        $instansi_id = $request->input('instansi_id');

        try {
            $warga = $this->wargaService->validasiWarga($id, $instansi_id);
            return response()->json($warga, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Validasi warga berdasarkan email.
     */
    public function validasiWargaByEmail(Request $request)
    {
        $email = $request->input('email');
        $instansi_id = $request->input('instansi_id');

        try {
            $warga = $this->wargaService->validasiWargaByEmail($email, $instansi_id);
            return response()->json($warga, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Validasi warga berdasarkan user ID.
     */
    public function validasiWargaByMe()
    {
        $user = Auth::user();

        try {
            $warga = $this->wargaService->validasiWargaByMe($user->id);
            return response()->json($warga, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Simpan data warga baru atau update warga yang sudah ada.
     */
    public function save(Request $request)
    {
        $data = $request->all();
        $warga = new Warga();

        try {
            $savedWarga = $this->wargaService->save($warga, $data);
            return response()->json($savedWarga, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
