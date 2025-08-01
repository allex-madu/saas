<?php

namespace App\Http\Controllers\Api\Auth\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BakerySwitcherController extends Controller
{
    public function myBakeries(Request $request)
    {
        $bakeries = $request->user()->bakeries()
            ->select('bakeries.id', 'bakeries.name', 'bakeries.slug')
            ->get();

        return response()->json([
            'data' => $bakeries,
        ]);
    }






    public function setActiveBakery(Request $request)
    {
        $bakeryId = $request->input('bakery_id');

        $user = $request->user();

        if (!$user->bakeries()->where('bakeries.id', $bakeryId)->exists()) {
            return response()->json(['message' => 'A padaria não pertence ao usuário.'], 403);
        }

        $request->session()->put('bakery_id', $bakeryId);

        return response()->json(['message' => 'Padaria selecionada com sucesso.']);
    }

}
