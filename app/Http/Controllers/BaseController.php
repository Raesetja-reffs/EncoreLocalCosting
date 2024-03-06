<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\Return_;

class BaseController extends Controller
{
    // views
    public function dashboard(){
        if(!Session::has('UserId')) {
            return redirect('/login');
        }else{
            return view('dashboard');
        }
    }

    public function login(){
        return view('login');
    }

    // Requests
    public function getlogin(Request $request){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');
        
        $username = $request->get('username');
        $password = $request->get('password');
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."getLogin",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "username": "'.$username.'",
                "password": "'.$password.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
    
        // dd($response);

        if ($response) {
            $response = json_decode($response, true);
            
            $response = intval($response["Column1"]);
    
            // Check if the response is a numeric value (user ID)
            if (is_numeric($response)) {
                $this->setSessionInfo($response);
                // If it's a numeric value (user ID), send success response
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'user_id' => $response,
                ]);
            } else {
                // If it's not a numeric value, send error response with the API response message
                return response()->json([
                    'success' => false,
                    'message' => $response,
                ]);
            }
        } else {
            // If $response is empty, there was an issue with the API request
            return response()->json([
                'success' => false,
                'message' => 'Failed to communicate with the API.',
            ]);
        }
    }
    
    function setSessionInfo($ID){
        
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $IP."getUserInfo",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "userId": "'.$ID.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if($response) {
            $response = json_decode($response, true);
        }

        Session::put('UserId', $ID);
        Session::put('UserName', $response['strUserName']);
        Session::put('UserEmail', $response['strEmail']);
        Session::put('UserLevel', $response['intUserLevel']);
        Session::put('UserDisplayName', $response['strDisplayName']);

        return ('Success');
    }

    public function endsession(){
        Session::flush();
        return response("Success");
    }
}
