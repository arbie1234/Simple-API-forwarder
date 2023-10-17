<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class APIController extends Controller
{
    public function handleRequest(Request $request){
        $method = $request->method();
        $endpoint = env('API_BASE_URL').str_replace(env('APP_URL').'/api/v1/','/',$request->fullUrl());
        $headers = [];

        if($request->header('authorization')){
            $headers = ['Authorization'=>$request->header('authorization')];
        }

        switch($method){
            case 'GET':
                $response = Http::withHeaders($headers)->get($endpoint);
                break;
            case 'POST':
                $response = Http::withHeaders($headers)->post($endpoint,$request->all());
                break;
            case 'PUT':
                $response = Http::withHeaders($headers)->put($endpoint,$request->all());
                break;
            case 'PATCH':
                $response = Http::withHeaders($headers)->patch($endpoint,$request->all());
                break;
            default;
        }
        
        $body = $response->json() ?? null;
        $status = $response->status() ?? 404;


        \Log::info('Request Started: '.$endpoint);
        \Log::info('Request Method: '. JSON_ENCODE($method));
        \Log::info('Request Body: '. JSON_ENCODE($request->all()));
        \Log::info('Request Response: '. JSON_ENCODE($response->json()));
        \Log::info('Request Status: '.$status);
        \Log::info('====== END REQUEST ======');
        

        return response()->json($body,$status);
    }
}
