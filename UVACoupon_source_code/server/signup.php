<?php
    require "dbconnection.php";

    session_start();
    $sql = '';

    $_POST = json_decode(file_get_contents("php://input"),true);
    if(isset($_POST["name"]) && isset($_POST["email"])){
        $sql = "SELECT * FROM coupon_user WHERE email = '$_POST[email]'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                break;
            }
            http_response_code(403);
            die("DUPLICATE EMAIL !");
            echo json_encode($_SESSION['user']);
        }else{
            $sql = "insert into coupon_user(name,email,point_balance) value('$_POST[name]','$_POST[email]',0)";
            $result = mysqli_query($conn, $sql);
            $sql = "SELECT * FROM coupon_user WHERE email = '$_POST[email]'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    break;
                }
            }
            $_SESSION['user'] = $row;
            http_response_code(200);
            echo json_encode($_SESSION['user']);
        }
    }else{
        http_response_code(404);
        die("PLEASE CKECK YOUR NAME AND EMAIL");
    }

?>