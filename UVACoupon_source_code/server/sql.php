<?php
require "dbconnection.php";

$sql = '';
$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST["sql"])){
    $sql = $_POST["sql"];
}else{
    http_response_code(404);
    die('Empty Query');
}
try{
    $result = mysqli_query($conn, $sql);
    $res = array();
    if ($result !== false && $result !== true && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($res,$row);
        }
    }else if($result === false){
        http_response_code(400);
        echo $result;
        die(mysqli_error($conn));
    }
    echo json_encode($res);
}catch(Exception $e){
    http_response_code(403);
    die($e->getMessage());
}

?>