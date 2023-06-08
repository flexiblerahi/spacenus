<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->validate([
            'lat' => ['required'],
            'long' => ['required'],
        ]);
        $client = new Client([ 'base_uri' => 'https://maps.googleapis.com/maps/api/place/' ]);
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $response = $client->get('nearbysearch/json', [
            'query' => [
                'location' => $input['lat'] . ',' . $input['long'],
                'radius' => 5000,
                'key' => $apiKey,
            ],
        ]);
        $data = json_decode($response->getBody(), true);
        if ($data['status'] === 'OK') return response()->json([$data['results']]);
        return response()->json(['message' => 'Something Went Wrong. Please Try again!!'], 500);
    }
}
