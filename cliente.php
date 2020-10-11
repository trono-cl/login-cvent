<?php

$client = new SoapClient("https://api.cvent.com/soap/V200611.ASMX?WSDL", array('trace' => true, 'exceptions' => true));

$params = array();
$params['AccountNumber']    = "CVENTCS714";
$params['UserName']    = "CVENTCS714API";
$params['Password']    = "Redalert1.";

$response = $client->Login($params);

$arr_response = array();
$arr_response[] = $response->LoginResult->ServerURL;
$arr_response[] = $response->LoginResult->LoginSuccess;
$arr_response[] = $response->LoginResult->CventSessionHeader;

$ServerURL = $arr_response[0];
$LoginSuccess = $arr_response[1];
$CventSessionHeader = $arr_response[2];

echo "CventClient:" . "<br />";
echo "ServerURL: " . $ServerURL . "<br />";
echo "LogginSuccess: " . $LoginSuccess . "<br />";
echo "CventSessionHeader: " . $CventSessionHeader.'<br/>' ;

//fin de llamado del login
//se setea cabecera con el CventSessionHeader
$client->__setLocation($ServerURL);
$header_body = array('CventSessionValue' => $CventSessionHeader);
$header = new SoapHeader('http://api.cvent.com/2006-11', 'CventSessionHeader', $header_body);
$client->__setSoapHeaders($header);

//Se realiza llamado al metodo get update
$paramsGetUpdated = array();
$paramsGetUpdated['ObjectType'] = "Registration";
$paramsGetUpdated['StartDate'] = "2020-10-01T00:00:00";
$paramsGetUpdated['EndDate'] = "2020-10-10T23:59:00";

$responseGetUpdated = $client->GetUpdated($paramsGetUpdated);

$arr_responseGetUpdated = array();
$arr_responseGetUpdated[] = $responseGetUpdated->GetUpdatedResult->Id;

$id = $arr_responseGetUpdated[0];

echo "getUpdate ID: ";
echo '<br/>';
print_r($id);

//LLamado a retrieve
$paramsRetrieve = array();
$paramsRetrieve['ObjectType']   = "Registration";
$paramsRetrieve['Ids']          = $id;

$responseRetrieve  = $client->Retrieve($paramsRetrieve);

$arr_responseRetrieve = array();
$arr_responseRetrieve[] = $responseRetrieve->RetrieveResult;
echo '<br/>';

$CvObject = $arr_responseRetrieve[0];

echo "RESPONSE:\n" . $client->__getLastResponse() . "\n";

echo '<br/>';
echo "Retrieve ID: ";
echo '<br/>';
print_r($CvObject);


?>