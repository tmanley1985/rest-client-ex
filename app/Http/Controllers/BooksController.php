<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;

class BooksController extends Controller
{
    /** @type string api_key */
    const API_KEY = '78040aeded8ceac44f6bd7d50d009127';
    
    /** @type string secret */
    const SECRET = '3f0c6648372a1a0ef037998033c7a263';

    /**
     * Returns the hashed blob of data and the secret.
     * 
     * @param  array $data Array of data to be sent to the server.
     * @return string
     */
    public function hash($data = [])
    {

        return hash_hmac('sha256', json_encode($data, true), self::SECRET);

    }

    /**
     * Returns the constructed array to send.
     * 
     * @param  array $data Array of data to be sent to the server.
     * @return string
     */
    public function formatData($data = [])
    {

        $hashed = $this->hash($data);

        if (count($data) == 0) {
            $data = '';
        } else {
            $data = json_encode($data);
        }

        return [
            'api_key' => self::API_KEY,
            'data' => $data,
            'hashed' => $hashed

        ];

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $formatted= $this->formatData([]);

        extract($formatted);

        $client = new Client();

        $result = $client->get('http://mybrary.local/api/books', [
            'query' => ['api_key' => $api_key, 'data' => $data, 'hashed' => $hashed]
        ]);

        dd($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input('data');

        $formatted= $this->formatData($data);

        extract($formatted);

        $client = new Client();

        $result = $client->post('http://mybrary.local/api/books', [
            ['api_key' => $api_key, 'data' => $data, 'hashed' => $hashed]
        ]);

        dd($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $formatted= $this->formatData([]);

        extract($formatted);

        $client = new Client();

        $result = $client->get('http://mybrary.local/api/books/{$id}', [
            'query' => ['api_key' => $api_key, 'data' => $data, 'hashed' => $hashed]
        ]);

        dd($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->input('data');

        $formatted= $this->formatData($data);

        extract($formatted);

        $client = new Client();

        $result = $client->put('http://mybrary.local/api/books', [
            ['api_key' => $api_key, 'data' => $data, 'hashed' => $hashed]
        ]);

        dd($result);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
