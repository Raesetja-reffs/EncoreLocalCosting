<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response as FacadeResponse;

class CostingController extends Controller
{
    public function costing($ID, $edit = null){
        if (!Session::has('UserId')) {
            return redirect('/login');
        } else {
            $header = collect($this->getUserCostingSheetHeaderById($ID))->first();
            $lines = $this->getUserCostingSheetLinesByHeaderId($ID);
            $tradeTerms = $this->getTradeTerms();
            $DCs = $this->getDistributionCenters();
            $dealTypes = $this->getDealTypes(); 
    
            // Check if "edit mode" is enabled based on the $edit parameter
            $editMode = $edit === 'edit';
    
            return view('costing')
                ->with('header', $header)
                ->with('lines', $lines)
                ->with('tradeTerms', $tradeTerms)
                ->with('DCs', $DCs)
                ->with('dealTypes', $dealTypes)
                ->with('editMode', $editMode); // Pass the edit mode status to the view
        }
    }

    public function getUserCostingSheetsHeaders(){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');
        $ID = Session::get('UserId');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."getUserCostingSheetsHeaders",
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
            $response = json_decode($response);
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return response()->json($response);
    }

    public function getUserCostingSheetHeaderById($ID){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."getUserCostingSheetHeaderById",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "intAutoId": "'.$ID.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if($response) {
            $response = json_decode($response, false);
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return $response;
    }

    public function getUserCostingSheetLinesByHeaderId($ID){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."getUserCostingSheetLinesByHeaderId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "intHeaderId": "'.$ID.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if($response) {
            $response = json_decode($response, false);
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return $response;
    }

    public function getProducts(Request $request){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');
        $productDesc = $request->get('productDesc');
        $intUserID = Session::get('UserId');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."V2/getProducts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "productDesc": "'.$productDesc.'",
                "intUserID": "'.$intUserID.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if($response) {
            $response = json_decode($response);
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return response()->json($response);
    }

    public function getTradeTerms(){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."getTradeTerms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if($response) {
            $response = json_decode($response);
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return $response;
    }

    public function getDistributionCenters(){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."getDistributionCenters",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if($response) {
            $response = json_decode($response);
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return $response;
    }

    public function getDealTypes(){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."getDealTypes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Key=$Key",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if($response) {
            $response = json_decode($response);
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return $response;
    }

    public function createCostingSheet(Request $request){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');
        
        $description = $request->get("description");
        $products = $request->get("products");
        $ID = Session::get('UserId');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."postCreateCostingSheet",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "description": "'.$description.'",
                "products": "'.$products.'",
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
            if (!is_array($response) || !isset($response[0])) {
                $response = [$response]; // Wrap the response in an array
            }
        }

        return response()->json($response);
    }

    public function postUpdateCostingSheet(Request $request){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $headerId = $request->get("headerId");
        $headerData = $request->get("headerData");
        $lineData = $request->get("lineData");

        $intDC = $request->get("intDC");
        $intDealType = $request->get("intDealType");
        $dteValidFrom = $request->get("dteValidFrom");
        $dteValidTo = $request->get("dteValidTo");

        $xmlHeader = $this->xmlConvert($headerData);
        $xmlLines = $this->xmlConvert($lineData);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."postUpdateCostingSheet",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "headerId": "'.$headerId.'",
                "header": "'.$xmlHeader.'",
                "lines": "'.$xmlLines.'",
                "intDC": "'.$intDC.'",
                "intDealType": "'.$intDealType.'",
                "dteValidFrom": "'.$dteValidFrom.'",
                "dteValidTo": "'.$dteValidTo.'"
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

        return response()->json($response);
    }

    public function postReviseCostingSheet(Request $request){
        $IP = env('API_IP');
        $Key = env('API_KEY');
        $GUID = env('COMPANY_GUID');

        $headerId = $request->get("headerId");
        $headerData = $request->get("headerData");
        $lineData = $request->get("lineData");
        $userId = Session::get('UserId');

        $intDC = $request->get("intDC");
        $intDealType = $request->get("intDealType");
        $dteValidFrom = $request->get("dteValidFrom");
        $dteValidTo = $request->get("dteValidTo");

        $xmlHeader = $this->xmlConvert($headerData);
        $xmlLines = $this->xmlConvert($lineData);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $IP."postReviseCostingSheet",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "companyid": "'.$GUID.'",
                "headerId": "'.$headerId.'",
                "header": "'.$xmlHeader.'",
                "lines": "'.$xmlLines.'",
                "userId": "'.$userId.'",
                "intDC": "'.$intDC.'",
                "intDealType": "'.$intDealType.'",
                "dteValidFrom": "'.$dteValidFrom.'",
                "dteValidTo": "'.$dteValidTo.'"
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

        // dd($response);

        return response()->json($response);
    }

    function xmlConvert($array) {
        $xml = new \SimpleXMLElement('<data/>');
        
        foreach ($array as $productData) {
            $line = $xml->addChild('line');
            foreach ($productData as $key => $value) {
                $line->addChild($key, $value);
            }
        }

        $xml = $xml->asXML();
        $xml = str_replace("<?xml version=\"1.0\"?>\n", '', $xml);
    
        return $xml;
    }
}