<?php

$AccountNumber = $_POST['AccountNumber'];
$UserName = $_POST['UserName'];
$Password = $_POST['Password'];
$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];

$client = new SoapClient("https://api.cvent.com/soap/V200611.ASMX?WSDL", array('trace' => true, 'exceptions' => true));

$params = array();
$params['AccountNumber']    = $AccountNumber;
$params['UserName']         = $UserName;
$params['Password']         = $Password;

$response = $client->Login($params);

$arr_response = array();
$arr_response[] = $response->LoginResult->ServerURL;
$arr_response[] = $response->LoginResult->LoginSuccess;
$arr_response[] = $response->LoginResult->CventSessionHeader;

$ServerURL = $arr_response[0];
$LoginSuccess = $arr_response[1];
$CventSessionHeader = $arr_response[2];


//fin de llamado del login
//se setea cabecera con el CventSessionHeader
$client->__setLocation($ServerURL);
$header_body = array('CventSessionValue' => $CventSessionHeader);
$header = new SoapHeader('http://api.cvent.com/2006-11', 'CventSessionHeader', $header_body);
$client->__setSoapHeaders($header);

/*/Se realiza llamado al metodo get update
$paramsGetUpdated = array();
$paramsGetUpdated['ObjectType'] = "Registration";
$paramsGetUpdated['StartDate'] = $StartDate;
$paramsGetUpdated['EndDate'] = $EndDate;

$responseGetUpdated = $client->GetUpdated($paramsGetUpdated);

$arr_responseGetUpdated = array();
$arr_responseGetUpdated[] = $responseGetUpdated->GetUpdatedResult->Id;

$id = $arr_responseGetUpdated[0];*/


//Se realiza llamado al metodo get Searh
$paramsSearch = array();
$paramsSearch['ObjectType'] = "Registration";
$paramsSearch['CvSearchObject']['Filter'][] = array('Field' => 'EmailAddress', 'Operator' => 'Equals', 'Value' => 'ida.pennymon@cherwell.com');
$paramsSearch['CvSearchObject']['Filter'][] = array('Field' => 'EventId', 'Operator' => 'Equals', 'Value' => '49f2947a-0475-4a3e-afd5-ba1dab586299');

$responseSearch = $client->Search($paramsSearch);


$arr_responseSearch = array();
$arr_responseSearch[] = $responseSearch->SearchResult->Id;


$id = $arr_responseSearch[0];

//LLamado a retrieve
$paramsRetrieve = array();
$paramsRetrieve['ObjectType']   = "Registration";   
$paramsRetrieve['Ids']['Id']    = $id;

$responseRetrieve  = $client->Retrieve($paramsRetrieve);


$arr_responseRetrieve = array();
$arr_responseRetrieve[] = $responseRetrieve->RetrieveResult->CvObject;
echo '<br/>';

$CvObject = $arr_responseRetrieve[0];

//print_r($responseRetrieve->RetrieveResult->CvObject->Id);

/*echo '<br/>';
echo "Retrieve IDs: ";
echo '<br/>';
*/

//print_r($responseRetrieve->RetrieveResult->CvObject[0]);
//print_r($responseRetrieve->RetrieveResult->CvObject[0]->OrderDetail->OrderDetailId);

$tamano = count($responseRetrieve->RetrieveResult->CvObject);
$i=0;

for($i=0; $i<$tamano; $i++){
    if ($tamano != 1) {
        echo "<br/>";
        echo "CvObject " . $i . "<br/>";
        echo "ID: " . $responseRetrieve->RetrieveResult->CvObject[$i]->Id . "<br/>";
        echo "FirstName: " . $responseRetrieve->RetrieveResult->CvObject[$i]->FirstName . "<br/>";
        echo "LastName: " . $responseRetrieve->RetrieveResult->CvObject[$i]->LastName . "<br/>";
        echo "EmailAddress: " . $responseRetrieve->RetrieveResult->CvObject[$i]->EmailAddress . "<br/>";
        echo "EventId: " . $responseRetrieve->RetrieveResult->CvObject[$i]->EventId . "<br/>";
        echo "ConfirmationNumber: " . $responseRetrieve->RetrieveResult->CvObject[$i]->ConfirmationNumber . "<br/>";
        echo "<br/>";
    }
    else
    { 
        echo "<br/>";
        echo "CvObject " . $i . "<br/>";
        echo "ID: " . $responseRetrieve->RetrieveResult->CvObject->Id . "<br/>";
        echo "FirstName: " . $responseRetrieve->RetrieveResult->CvObject->FirstName . "<br/>";
        echo "LastName: " . $responseRetrieve->RetrieveResult->CvObject->LastName . "<br/>";
        echo "EmailAddress: " . $responseRetrieve->RetrieveResult->CvObject->EmailAddress . "<br/>";
        echo "EventId: " . $responseRetrieve->RetrieveResult->CvObject->EventId . "<br/>";
        echo "ConfirmationNumber: " . $responseRetrieve->RetrieveResult->CvObject->ConfirmationNumber . "<br/>";
        echo "<br/>";
    }
}


?>