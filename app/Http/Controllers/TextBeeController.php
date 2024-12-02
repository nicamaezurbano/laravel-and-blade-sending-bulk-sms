<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Contact;

class TextBeeController extends Controller
{
    private $deviceID;
    private $textbeeAPI;

    function __construct() 
    {
        $this->deviceID = config('services.textbee.deviceID');
        $this->textbeeAPI = config('services.textbee.api');
    }

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

        // $deviceID = config('services.textbee.deviceID');
        // $textbeeAPI = config('services.textbee.api');
        $deviceID = $this->deviceID;
        $textbeeAPI = $this->textbeeAPI;

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

        // return $combined_data;
        return view('sms.receive', compact("combined_data"));
    }


    public function sendSMS_index(Request $request)
    {
        $user = $request->user();
        $data = Contact::where('user_id', $user->id)->get();
        return view('sms.send', compact("data"));
    }

    public function sendSMS(Request $request): RedirectResponse
    {
        $request->validateWithBag('sendingSMS', [
            'sms_message' => ['required'],
            'selectedRecipients' => ['required'],
        ]);

        $recipientsArray = explode(",", $request->selectedRecipients);
        
        $recipientNumbersArray = [];
        for($a = 0 ; $a < count($recipientsArray); $a++)
        {
            $data = Contact::find($recipientsArray[$a]);
            $recipientNumbersArray[$a] = $data->number;
        }
        
        $deviceID = $this->deviceID;
        $textbeeAPI = $this->textbeeAPI;

        // Define the URL and headers
        $url = 'https://api.textbee.dev/api/v1/gateway/devices/'.$deviceID.'/sendSMS'; 
        
        // Define the payload
        $data = [
            "recipients" => $recipientNumbersArray, // Replace with the actual recipient's phone number
            "message" => $request->sms_message // Your message
        ];

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . $textbeeAPI,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $decodedArray = json_decode($response, true);

        // Close cURL session
        curl_close($ch);

        return Redirect::route('sms.send.index')
            ->with('response', $decodedArray["data"]["responses"])
            ->with('recipients', $recipientNumbersArray);
    }
}
