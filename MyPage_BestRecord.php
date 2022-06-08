<?php
  $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
  mysqli_query($con,'SET NAMES utf8');

  $userID = $_POST["userID"];
  $response["success"] = true;

  $statementBestRecord = mysqli_prepare($con, "SELECT * FROM bestRecord WHERE userID = ?");
  mysqli_stmt_bind_param($statementBestRecord,"s",$userID);
  mysqli_stmt_execute($statementBestRecord);
  mysqli_stmt_store_result($statementBestRecord);
  mysqli_stmt_bind_result($statementBestRecord, $userID, $bestSteps, $bestKm, $bestTime_Km, $bestCalorie_Km, $bestTime_Steps, $bestCalorie_Steps);

  while(mysqli_stmt_fetch($statementBestRecord)){
      $response["bestSteps"] = $bestSteps;
      $response["bestKm"] = $bestKm;
      $response["bestTime(km)"] = $bestTime_Km;
      $response["bestCalorie(km)"] = $bestCalorie_Km;
      $response["bestTime(steps)"] = $bestTime_Steps;
      $response["bestCalorie(steps)"] = $bestCalorie_Steps;
  }
  // $sql = "SELECT * FROM bestRecord WHERE userID=".$userID;
  // $result = mysqli_query($con,$sql);
  // $row = mysqli_fetch_array($result);
  //
  // $response["bestSteps"] = htmlspecialchars($row['bestSteps']);
  // $response["bestKm"] = htmlspecialchars($row['bestKm']);
  // $response["bestTime(km)"] = htmlspecialchars($row['bestTime(km)']);
  // $response["bestCalorie(km)"] = htmlspecialchars($row['bestCalorie(km)']);
  // $response["bestTime(steps)"] = htmlspecialchars($row['bestTime(steps)']);
  // $response["bestCalorie(steps)"] = htmlspecialchars($row['bestCalorie(steps)']);


  echo json_encode($response);
?>
