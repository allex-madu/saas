<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        //$this->authorize('viewAny', City::class); 

        $search = $request->get('search');

        $cities = \App\Models\City::getCity()
            ->when($search, fn ($query) =>
                $query->where('cities.title', 'like', '%' . $search . '%')
            )
            ->orderBy('cities.title')
            ->limit(20) // Limite para autocomplete
            ->get();
            

        return response()->json(['data' => $cities]);
    }

}
