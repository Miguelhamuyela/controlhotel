<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CountriesController extends Controller
{
    public function index()
    {
        try {
            // Se já tiver cache, retorna imediatamente
            if (Cache::has('countries.all')) {
                return response()->json(Cache::get('countries.all'));
            }

            // Chama a API v2, pedindo só os campos necessários
            $response = Http::withOptions([
                    'connect_timeout' => 10, // tempo para conectar
                    'timeout'         => 30, // tempo para resposta
                ])
                ->retry(3, 100) // 3 tentativas, 100ms entre cada
                ->get('https://restcountries.com/v2/all', [
                    'fields' => 'name,alpha2Code,callingCodes',
                ]);

            // Se der erro na resposta, loga e retorna o cache (ou array vazio)
            if (! $response->successful()) {
                Log::error('Countries API returned status '.$response->status());
                return response()->json(Cache::get('countries.all', []));
            }

            // 4) Formata os dados
            $countries = $response->json();
            $formatted = collect($countries)
                ->map(function ($c) {
                    return [
                        'name'  => $c['name'] ?? null,
                        'code'  => $c['alpha2Code'] ?? null,
                        'phone' => (isset($c['callingCodes'][0]) && $c['callingCodes'][0] !== '')
                            ? $c['callingCodes'][0]
                            : null,
                    ];
                })
                ->sortBy('name')
                ->values()
                ->all();

            // 5) Armazena no cache por 60 minutos
            Cache::put('countries.all', $formatted, 60);

            return response()->json($formatted);

        } catch (\Exception $e) {
            // Em caso de qualquer exceção, loga e retorna o que estiver em cache (ou vazio)
            Log::error('Erro na chamada da API de países: '.$e->getMessage());
            return response()->json(Cache::get('countries.all', []));
        }
    }
}
