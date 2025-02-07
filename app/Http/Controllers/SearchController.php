<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    // URL sumber eksternal
    protected $externalUrl = 'https://bit.ly/48ejMhW';

    protected function parseData($dataString)
    {
        $records = [];
        // memisahkan data berdasarkan baris
        $lines = explode("\n", $dataString);
        if(count($lines) < 2){
            return $records;
        }

        $header = explode('|', array_shift($lines));
        $header = array_map('trim', $header);
        foreach ($lines as $line) {
            if(trim($line) === ''){
                continue;
            }
            $values = explode('|', $line);
            $values = array_map('trim', $values);
            if(count($values) === count($header)){
                $records[] = array_combine($header, $values);
            }
        }
        return $records;
    }

    public function searchByName(Request $request)
    {
        try {
            $response = Http::get($this->externalUrl);
            if(!$response->successful()){
                return $this->jsonResponse('error', 'Gagal mengambil data', null, 500);
            }

            // ambil data mentah dari response dan parsing
            $responseData = $response->json();
            $dataString = $responseData['DATA'] ?? '';
            if(empty($dataString)){
                return $this->jsonResponse('error', 'Data tidak tersedia', null, 500);
            }
            $records = $this->parseData($dataString);

            // filter data berdasarkan nama
            $filtered = array_filter($records, function($item){
                return isset($item['NAMA']) && strtolower($item['NAMA']) === strtolower('Turner Mia');
            });

            // hanya mengambil field nama
            $names = array_map(function ($item){
                return $item['NAMA'];
            }, array_values($filtered));

            return $this->jsonResponse('success', 'Berhasil mengambil data', $names);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }

    public function searchByNim(Request $request)
    {
        try {
            $response = Http::get($this->externalUrl);
            if(!$response->successful()){
                return $this->jsonResponse('error', 'Gagal mengambil data', null, 500);
            }

            // ambil data mentah dari response dan parsing
            $responseData = $response->json();
            $dataString = $responseData['DATA'] ?? '';
            if(empty($dataString)){
                return $this->jsonResponse('error', 'Data tidak tersedia', null, 500);
            }
            $records = $this->parseData($dataString);

            // filter data berdasarkan nim
            $filtered = array_filter($records, function($item){
                return isset($item['NIM']) && strtolower($item['NIM']) === strtolower('9352078461');
            });

            // hanya mengambil field nim
            $nims = array_map(function ($item){
                return $item['NIM'];
            }, array_values($filtered));

            return $this->jsonResponse('success', 'Berhasil mengambil data', $nims);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }

    public function searchByYmd(Request $request)
    {
        try {
            $response = Http::get($this->externalUrl);
            if(!$response->successful()){
                return $this->jsonResponse('error', 'Gagal mengambil data', null, 500);
            }

            // ambil data mentah dari response dan parsing
            $responseData = $response->json();
            $dataString = $responseData['DATA'] ?? '';
            if(empty($dataString)){
                return $this->jsonResponse('error', 'Data tidak tersedia', null, 500);
            }
            $records = $this->parseData($dataString);

            // filter data berdasarkan ymd
            $filtered = array_filter($records, function($item){
                return isset($item['YMD']) && strtolower($item['YMD']) === strtolower('20230405');
            });

            // hanya mengambil field ymd
            $ymds = array_map(function ($item){
                return $item['YMD'];
            }, array_values($filtered));

            return $this->jsonResponse('success', 'Berhasil mengambil data', $ymds);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }
}
