<?php
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');
    $response = array();

    $userID = mysqli_real_escape_string($con, $_POST["userID"]);
    $userGender = mysqli_real_escape_string($con, $_POST["userGender"]);
    $userAge = mysqli_real_escape_string($con, $_POST["userAge"]);
    $userHeight = mysqli_real_escape_string($con, $_POST["userHeight"]);
    $userWeight = mysqli_real_escape_string($con, $_POST["userWeight"]);

    if($userAge==0){
      $response["success"] = false;
      $response["answer"]="age";
      echo json_encode($response);
      return;
    }
    if($userHeight==0){
      $response["success"] = false;
      $response["answer"]="height";
      echo json_encode($response);
      return;
    }
    if($userWeight==0){
      $response["success"] = false;
      $response["answer"]="weight";
      echo json_encode($response);
      return;
    }

    $statement = mysqli_prepare($con, "UPDATE member SET userGender = ?, userAge = ?, userHeight = ?, userWeight = ? WHERE userID = '$userID'");
    mysqli_stmt_bind_param($statement, "sidd", $userGender, $userAge, $userHeight, $userWeight);
    mysqli_stmt_execute($statement);
    $response["success"] = true;

    echo json_encode($response);

?>
