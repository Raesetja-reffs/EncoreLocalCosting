<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function admin()
    {
        $adminAccessList = env('ADMIN_USER_IDS');
        $adminAccessList = explode(",", $adminAccessList);

        $ID = Session::get('UserId');

        if (!Session::has('UserId')){
            return redirect('/login');
        } else {
            if (in_array($ID, $adminAccessList)) {
                return view('admin');
            } else {
                abort(401, 'Unauthorized');
            }
            
        }
    }

    public function postTradeTermsCrud(Request $request){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $intAutoId = $request->get('intAutoId');
        $strTerm = $request->get('strTerm');
        $mnyTerm = $request->get('mnyTerm');
        $command = $request->get('command');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."postTradeTermsCrud",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "intAutoId": "'.$intAutoId.'",
                "strTerm": "'.$strTerm.'",
                "mnyTerm": "'.$mnyTerm.'",
                "command": "'.$command.'"
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
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return $response;
    }

    public function postWarehouseCostsCrud(Request $request){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $intAutoId = $request->get('intAutoId');
        $intInbound = $request->get('intInbound');
        $intOutbound = $request->get('intOutbound');
        $intWarehousing = $request->get('intWarehousing');
        $mnyRetailPercentage = $request->get('mnyRetailPercentage');
        $mnyDCPercentage = $request->get('mnyDCPercentage');
        $intCreatedBy = Session::get('UserId');
        $command = $request->get('command');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."postWarehouseCostsCrud",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "intAutoId": "'.$intAutoId.'",
                "intInbound": "'.$intInbound.'",
                "intOutbound": "'.$intOutbound.'",
                "intWarehousing": "'.$intWarehousing.'",
                "mnyRetailPercentage": "'.$mnyRetailPercentage.'",
                "mnyDCPercentage": "'.$mnyDCPercentage.'",
                "intCreatedBy": "'.$intCreatedBy.'",
                "command": "'.$command.'"
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
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return $response;
    }
}
