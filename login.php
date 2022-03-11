<?php
    $con = mysqli_connect("localhost", "root", "1234", "pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');

    $userID = $_POST["userID"];
    $userPassword = $_POST["userPassword"];

    $statement = mysqli_prepare($con, "SELECT * FROM member WHERE userID = ? AND userPassword = ?");
    mysqli_stmt_bind_param($statement, "ss", $userID, $userPassword);
    mysqli_stmt_execute($statement);


    mysqli_stmt_store_result($statement);
    mysqli_stmt_bind_result($statement, $userID, $userPassword, $userName,$userGender, $userAge, $userHeight,$userWeight);

    $response = array();
    $response["success"] = false;

    while(mysqli_stmt_fetch($statement)) {
        $response["success"] = true;
        $response["userID"] = $userID;
        $response["userPassword"] = $userPassword;
        $response["userName"] = $userName;
        $response["userGender"] = $userGender;
        $response["userAge"] = $userAge;
        $response["userHeight"] = $userHeight;
        $response["userWeight"] = $userWeight;
    }

    echo json_encode($response);
?>
