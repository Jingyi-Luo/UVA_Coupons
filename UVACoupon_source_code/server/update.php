<?php
    require "dbconnection.php";
    try{
        $_POST = json_decode(file_get_contents("php://input"),true);
        $u_id = isset($_POST["u_id"]) ? $_POST["u_id"] : "100";
        $title = isset($_POST["title"]) ? $_POST["title"] : "Lorem Title";
        $type = isset($_POST["type"]) ? $_POST["type"] : "other";
        $discount = isset($_POST["discount"]) ? $_POST["discount"] : "100";
        $date = isset($_POST["date"]) ? $_POST["date"] : "2019-04-20T00:00:00Z";
        $e_date = isset($_POST["e_date"]) ? $_POST["e_date"] : "2019-01-01";
        $ex_date = isset($_POST["ex_date"]) ? $_POST["ex_date"] : "2019-12-31";
        $store_name = isset($_POST["store_name"]) ? $_POST["store_name"] : "UVA Store";
        $address = isset($_POST["address"]) ? $_POST["address"] : "UVA Street";
        $photo_link = isset($_POST["photo_link"]) ? $_POST["photo_link"] : "https://i.loli.net/404";
        $l_id = isset($_POST['l_id']) ? $_POST['l_id'] : '1000';
        $sql = "update local_coupon set title='$title',l_type='$type',l_discount_desc='$discount',l_effective_date='$e_date',l_expiration_date='$ex_date',store_name='$store_name',address='$address',l_photo_link='$photo_link' where l_id=$l_id";
        $result = mysqli_query($conn, $sql);
        $res = [];
        if ($result !== false && $result !== true && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($res,$row);
            }
        }else if($result === false){
            http_response_code(500);
            echo $result;
            die(mysqli_error($conn));
        }
        die('POST OK');
    }catch(Exception $e){
        http_response_code(500);
        die($e->getMessage());
    }
?>