<?php
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');
    $response = array();

    $userID = mysqli_real_escape_string($con, $_POST["userID"]);
    $userPassword = mysqli_real_escape_string($con, $_POST["userPassword"]);
    $userName = mysqli_real_escape_string($con, $_POST["userName"]);
    //return을 써서 종료하게 해야함. (echo $response["success"]=false 를 줘야하고, 이유까지 주기.)

    $sql = "SELECT userID, userName FROM member WHERE userID not in('$userID')";
    $result = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($result)){
        if($row['userName']==$userName){
            $response["success"]=false;
            $response["answer"]="name";
            echo json_encode($response);
            return;
        }
    }
    $statement = mysqli_prepare($con, "UPDATE member SET userPassword = ?, userName = ? WHERE userID = '$userID'");
    mysqli_stmt_bind_param($statement, "ss", $userPassword, $userName);
    mysqli_stmt_execute($statement);
    $response["success"] = true;

    echo json_encode($response);

?>
