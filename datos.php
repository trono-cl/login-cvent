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

//Se realiza llamado al metodo get update
$paramsGetUpdated = array();
$paramsGetUpdated['ObjectType'] = "Registration";
$paramsGetUpdated['StartDate'] = $StartDate;
$paramsGetUpdated['EndDate'] = $EndDate;

$responseGetUpdated = $client->GetUpdated($paramsGetUpdated);

$arr_responseGetUpdated = array();
$arr_responseGetUpdated[] = $responseGetUpdated->GetUpdatedResult->Id;

$id = $arr_responseGetUpdated[0];

//LLamado a retrieve
$paramsRetrieve = array();
$paramsRetrieve['ObjectType']   = "Registration";
$paramsRetrieve['Ids']          = $id;

$responseRetrieve  = $client->Retrieve($paramsRetrieve);

$arr_responseRetrieve = array();
$arr_responseRetrieve[] = $responseRetrieve->RetrieveResult;
echo '<br/>';

$CvObject = $arr_responseRetrieve[0];

/*echo '<br/>';
echo "Retrieve IDs: ";
echo '<br/>';
*/

//print_r($responseRetrieve->RetrieveResult->CvObject[0]);
//print_r($responseRetrieve->RetrieveResult->CvObject[0]->OrderDetail->OrderDetailId);

$tamano = count($responseRetrieve->RetrieveResult->CvObject);
$i=0;
$z=1;

$producNameArray = array("General Conference Pass", "Premium Conference Pass","Premium Plus+ Conference Pass", "Executive Track", "Prospective Customer Track", "Cherwell Staff");
$temp = 0;

if($tamano > 0) {

        //coneccion a base de datos
        include "connect.php";

        // // eliminamos los registros de la tabla para insertar los nuevos
        $delete = "delete from apicvent";
        $conn->query($delete);

        // cargar query con los nuevo registros
        $data = "";

        for($i=0; $i<$tamano; $i++){

                $data .= "('".($responseRetrieve->RetrieveResult->CvObject[$i]->Id ?? '')."',"; // ID
                $data .= "'".($responseRetrieve->RetrieveResult->CvObject[$i]->FirstName ?? '')."',"; // firstname
                $data .= "'".($responseRetrieve->RetrieveResult->CvObject[$i]->LastName ?? '')."',"; // lastname
                $data .= "'".($responseRetrieve->RetrieveResult->CvObject[$i]->EmailAddress ?? '')."',"; //email
                $data .= "'".($responseRetrieve->RetrieveResult->CvObject[$i]->EventId ?? '')."',"; // eventId
                $data .= "'".($responseRetrieve->RetrieveResult->CvObject[$i]->ConfirmationNumber ?? '')."',"; // confirm

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

                               

                                        /*for($x=1; $x=6; $x++){
                                                if($temp > $valor){
                                                        $temp = $valor;
                                                        $valor = $temp;
                                                }       
                                        }*/

                                //$data .= "'".$valor."')";

                                /*$order_detail[] = [
                                        'ProductName' => htmlspecialchars($order->ProductName, ENT_QUOTES)
                                ];*/
                        }
                }
                $maxValor = max($valor);

                $data .= "'".$maxValor."')";

                // convertimos el orderDetail a json para guardar en base de datos
                //$data .= "'".json_encode($order_detail, JSON_HEX_APOS)."')";

                $data .= ($i < $tamano-1) ? "," : "";
        }

        // agregamos la sentencia insert y concatenamos los valoresdefinidos
        $insert = "INSERT INTO apicvent (Id, FirstName, LastName, EmailAddress, EventId, ConfirmationNumber, PassType) VALUES ".$data;

        echo $insert;
        // ejecutamos la query
        $conn->exec($insert);

        echo 'Registrado exitosamente';
}
