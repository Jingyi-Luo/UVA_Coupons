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

        $sql = "INSERT INTO local_coupon(title,l_type,l_discount_desc,l_effective_date,l_expiration_date,store_name,address,l_photo_link) value('$title','$type','$discount','$e_date','$ex_date','$store_name','$address','$photo_link')";
        $result = mysqli_query($conn, $sql);
        $sql = "select l_id from local_coupon order by l_id DESC limit 1";
        $result = mysqli_query($conn, $sql);
        $res = [];
        if ($result !== false && $result !== true && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($res,$row);
            }
        }
        $l_id = $res[0]['l_id'];
        $sql = "insert into post value($u_id,$l_id,'$date')";
        $result = mysqli_query($conn, $sql);
        if($result === false){
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