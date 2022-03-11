<?php
    $con = mysqli_connect("localhost", "root", "1234", "pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');

    $userID = $_POST["userID"];
    $userPassword = $_POST["userPassword"];
    $userName = $_POST["userName"];
    $userGender = $_POST["userGender"];
    $userAge = $_POST["userAge"];
    $userHeight = $_POST["userHeight"];
    $userWeight = $_POST["userWeight"];

    $statement = mysqli_prepare($con, "INSERT INTO member VALUES (?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($statement, "ssssiii", $userID, $userPassword, $userName,$userGender, $userAge, $userHeight,$userWeight);
    mysqli_stmt_execute($statement);


    $response = array();
    $response["success"] = true;


    echo json_encode($response);

?>
