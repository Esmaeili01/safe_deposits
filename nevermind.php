<?php

use LDAP\Result;

    $hostname = "localhost";
    $username = "root" ;
    $password = "";
    $databsename = "deposit";
    $tag = ""; $first_name = ""; $last_name = ""; $student_id = "";
    date_default_timezone_set("Asia/Tehran");
    $nowTime = date("H:i") ;

    $hourString = date("H");
    $minString = date("i");
    $hourInt = (int)$hourString;
    $minInt = (int)$minString;
    $expire = 3;

    $con = mysqli_connect($hostname , $username , $password , $databsename);
    if($con->connect_error)
        die("Connection Failed:".$con->connect_error);
    

    if(1) {
        $tag = "55 11 22 AA";
        $sql = "SELECT * FROM boxes WHERE (`rfid_card` = '$tag')";
        $result = $con->query($sql);
        if($result->num_rows > 0){
            $rows = $result->fetch_assoc();
            $box_num = $rows['id'];
            echo $box_num ; 
        }
        else{
            $sql = "SELECT * FROM data_info WHERE (`rfid_card` = '$tag')";
            $result = $con->query($sql);
            $rows = $result->fetch_assoc();
            $first_name = $rows["first_name"];
            $last_name = $rows["last_name"];
            $student_id = $rows["student_id"];
            
            $hourInt = $hourInt + $expire;

            $sql = "SELECT * FROM boxes WHERE isEmpty = 1 LIMIT 1";
            $result = $con->query($sql);
            if($result->num_rows > 0){

                $rows = $result->fetch_assoc();
                $box_num = $rows['id'];
                $sql = "UPDATE boxes SET isEmpty = 0 , student_id = '$student_id' WHERE (`id` = '$box_num' )"; // set  rest 
                $con->query($sql);
                echo $box_num;
            }
            else{
                echo "-1";
            }

          
        }
    }
    $con->close();

    
/*
    if($result->num_rows > 0){
    
        $rows = $result->fetch_assoc();
        $first_name = $rows["first_name"];
        $last_name = $rows["last_name"];
        $student_id = $rows["student_id"];
        $sql = "INSERT INTO log (first_name, last_name, student_id , log_date, log_time, class_number ,authorised) VALUES 
        ('$first_name', '$last_name', '$student_id', '$nowDate', '$nowTime','$room' ,1)";
        $result = $con->query($sql);
        echo "1";
    }else{
        $sql = "SELECT * FROM class WHERE `uid` = '$tag'";
        $result = $con->query($sql);
        if($result->num_rows > 0){
            $rows = $result->fetch_assoc();
            $first_name = $rows["first_name"];
            $last_name = $rows["last_name"];
            $student_id = $rows["student_id"];
        }else{
            $first_name = "NULL";
            $last_name = "NULL";
            $student_id = 0;
        }
        $sql = "INSERT INTO log (`student_id`, `first_name`, `last_name` , `log_date`, `log_time`, `class_number` ,`authorised`) VALUES ('$student_id','$first_name', '$last_name',  '$nowDate', '$nowTime', '$room', 0)";
        $con->query($sql);
        echo "0";
    }
*/

?>
