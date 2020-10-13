<?php

$AccountNumber = 'CSLLCCO001';
$UserName = 'CSLLCCO001Api2';
$Password = 'DfYzuUIUdXu';
$email = $_POST['email'];
//$confirmation = $_POST['confirmation'];

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
$paramsSearch['CvSearchObject']['Filter'][] = array('Field' => 'EmailAddress', 'Operator' => 'Equals', 'Value' => $email);
$paramsSearch['CvSearchObject']['Filter'][] = array('Field' => 'EventId', 'Operator' => 'Equals', 'Value' => 'A21A10B7-ABCF-48DC-86ED-57D095B947DC');

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

$CvObject = $arr_responseRetrieve[0];

//$tamano = count($responseRetrieve->RetrieveResult->CvObject);
$tamano = 1;

$producNameArray = array("General Conference Pass", "Premium Conference Pass","Premium Plus+ Conference Pass", "Executive Track", "Prospective Customer Track", "Cherwell Staff");

if($tamano != 1) {

    echo "A";
    for($i=0; $i<$tamano; $i++){

            $idUser = $responseRetrieve->RetrieveResult->CvObject[$i]->Id; // ID
            $FirstName = $responseRetrieve->RetrieveResult->CvObject[$i]->FirstName; // firstname
            $LastName = $responseRetrieve->RetrieveResult->CvObject[$i]->LastName; // lastname
            $EmailAddress = $responseRetrieve->RetrieveResult->CvObject[$i]->EmailAddress; //email
            $EventId = $responseRetrieve->RetrieveResult->CvObject[$i]->EventId; // eventId
            $ConfirmationNumber = $responseRetrieve->RetrieveResult->CvObject[$i]->ConfirmationNumber; // confirm

            // iterar OrderDetail
            //$order_detail = [];

            $valor = [];

            foreach($responseRetrieve->RetrieveResult->CvObject[$i]->OrderDetail as $order) {

                    if (in_array($order->ProductName,$producNameArray)){

                            if ($order->ProductName == 'General Conference Pass') {
                                   array_push($valor, 1);
                            } elseif ($order->ProductName == 'Premium Conference Pass') {
                                    array_push($valor, 2);
                            } elseif ($order->ProductName == 'Prospective Customer Track') {
                                    array_push($valor, 3);
                            } elseif ($order->ProductName == 'Premium Plus+ Conference Pass') {
                                    array_push($valor, 4);
                            } elseif ($order->ProductName == 'Executive Track') {
                                    array_push($valor, 5);
                            } elseif ($order->ProductName == 'Cherwell Staff') {
                                    array_push($valor, 6);
                            }
                    }
            }
            $maxValor = max($valor);

    }

    // agregamos la sentencia insert y concatenamos los valoresdefinidos

    echo 'idUser: ' . $idUser . "<br/>";
    echo 'FirstName: ' . $FirstName . "<br/>";
    echo 'LastName: ' . $LastName . "<br/>";
    echo 'EmailAddress: ' . $EmailAddress . "<br/>"; 
    echo 'EventId: ' . $EventId . "<br/>";
    echo 'maxValor: ' . $maxValor . "<br/>";

}
else
{
    echo "B";
    for($i=0; $i<$tamano; $i++){

        $idUser = $responseRetrieve->RetrieveResult->CvObject->Id; // ID
        $FirstName = $responseRetrieve->RetrieveResult->CvObject->FirstName; // firstname
        $LastName = $responseRetrieve->RetrieveResult->CvObject->LastName; // lastname
        $EmailAddress = $responseRetrieve->RetrieveResult->CvObject->EmailAddress; //email
        $EventId = $responseRetrieve->RetrieveResult->CvObject->EventId; // eventId
        $ConfirmationNumber = $responseRetrieve->RetrieveResult->CvObject->ConfirmationNumber; // confirm

        $valor = [];

        foreach($responseRetrieve->RetrieveResult->CvObject->OrderDetail as $order) {

                if (in_array($order->ProductName,$producNameArray)){

                        if ($order->ProductName == 'General Conference Pass') {
                               array_push($valor, 1);
                        } elseif ($order->ProductName == 'Premium Conference Pass') {
                                array_push($valor, 2);
                        } elseif ($order->ProductName == 'Prospective Customer Track') {
                                array_push($valor, 3);
                        } elseif ($order->ProductName == 'Premium Plus+ Conference Pass') {
                                array_push($valor, 4);
                        } elseif ($order->ProductName == 'Executive Track') {
                                array_push($valor, 5);
                        } elseif ($order->ProductName == 'Cherwell Staff') {
                                array_push($valor, 6);
                        }
                }
        }
        $maxValor = max($valor);

}
    echo 'idUser: ' . $idUser . "<br/>";
    echo 'FirstName: ' . $FirstName . "<br/>";
    echo 'LastName: ' . $LastName . "<br/>";
    echo 'EmailAddress: ' . $EmailAddress . "<br/>"; 
    echo 'EventId: ' . $EventId . "<br/>";
    echo 'maxValor: ' . $maxValor . "<br/>";
}


?>
