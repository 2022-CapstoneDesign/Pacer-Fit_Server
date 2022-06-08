<?php
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');
    $response = array();

    $userID = mysqli_real_escape_string($con, $_POST["userID"]);

    //기록
    $sql = "DELETE FROM bestRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //기록
    $sql = "DELETE FROM kmCalorieRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //기록
    $sql = "DELETE FROM kmRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //기록
    $sql = "DELETE FROM kmTimeRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //기록
    $sql = "DELETE FROM stepsRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //기록
    $sql = "DELETE FROM stepsCalorieRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //기록
    $sql = "DELETE FROM stepsTimeRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //별점
    $sql = "DELETE FROM seongbukgu_rate WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    //회원정보
    $sql = "DELETE FROM member WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);

    $response["success"] = true;

    echo json_encode($response);

?>
