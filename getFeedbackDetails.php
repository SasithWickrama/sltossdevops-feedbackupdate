<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header("Content-Type:application/json");
if (isset($_GET['req_id']) && $_GET['req_id'] != "") {





    function connect()
    {

        $connstring = '(DESCRIPTION =
    (ADDRESS_LIST =
    (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty))
)';
        $user = 'ossrpt';
        $pass = 'ossrpt123';
        $conn = new PDO("oci:dbname=" . $connstring, $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }





    $req_id = $_GET['req_id'];
    
    $sql = "SELECT SERV_CUSR_ABBREVIATION , SERV_AREA_CODE "  .
    "FROM PROBLEM_LINKS ,SERVICES " .
    "WHERE PROL_PROM_NUMBER = :faultid " .
    "AND SERV_ID = PROL_FOREIGNID " .
    "AND PROL_FOREIGNTYPE = 'SERVICES'";


    $con = connect();
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':faultid', $req_id);

    if ($stmt->execute()) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $con = null;

        $response_data['ERROR'] = false;
        $response_data['DATA'] = $results;
        $response_data['MESSAGE'] = "";
    } else {
        $err =  oci_error($stmt);
        $response_data['ERROR'] = true;
        $response_data['DATA'] = "";
        $response_data['MESSAGE'] = "DB Error". $err['MESSAGE'];
    }

    
} else {
    $err =  oci_error($stmt);
    $response_data['ERROR'] = true;
    $response_data['DATA'] = "";
    $response_data['MESSAGE'] = "Invalid Parameter";
}

$json_response = json_encode($response_data);
	echo $json_response;

