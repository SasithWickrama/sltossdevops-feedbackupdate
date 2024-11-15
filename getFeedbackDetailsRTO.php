<?php
/** Place this page inside FeedbackAPI in 175 Server and call API as https://serviceportal.slt.lk/FeedbackAPI/getFeedbackDetailsRTO.php?req_id=? on reqFeedbackData.py */

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

    $sql =  "SELECT SERV_CUSR_ABBREVIATION , SERV_AREA_CODE " .
            "FROM SERVICES, SERVICE_ORDERS " .
            "WHERE SERO_ID = :serodid " .
            "AND SERV_ID = SERO_SERV_ID";

    $con = connect();
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':serodid', $req_id);

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

