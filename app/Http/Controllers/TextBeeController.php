<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TextBeeController extends Controller
{
    public function receiveSMS(Request $request)
    {
        if(isset($request->row))
        {
            $record_per_page = $request->row;
        }
        else
        {
            $record_per_page = 10;
        }

        $deviceID = config('services.textbee.deviceID');
        $textbeeAPI = config('services.textbee.api');

        // Define the URL and headers
        $url = 'https://api.textbee.dev/api/v1/gateway/devices/'.$deviceID.'/getReceivedSMS'; 

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . $textbeeAPI,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $decodedArray = json_decode($response, true);
        $data = $decodedArray["data"];

        // Close cURL session
        curl_close($ch);

        // Current page number from request
        $page = $request->input('page', 1);

        // Items per page
        $perPage = $record_per_page;

        // Slice the data for the current page
        $itemsForCurrentPage = array_slice($data, ($page - 1) * $perPage, $perPage);

        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $itemsForCurrentPage, // Items for the current page
            count($data),         // Total items
            $perPage,             // Items per page
            $page,                // Current page number
            ['path' => $request->url()] // URL path for pagination links
        );

        $combined_data = [
            "record_per_page"=>$record_per_page,
            "sms"=>$paginator->withQueryString(),   // Append the paginator links
        ];

        // return $paginator;
        return view('sms.receive', compact("combined_data"));
    }
}
