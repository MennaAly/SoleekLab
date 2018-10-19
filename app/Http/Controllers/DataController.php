<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DataController extends Controller
{
        public function area()
        {
            $client = new Client();
            $res = $client->request('GET', 'https://api.printful.com/countries');
            return $res->getBody();

        }

}
