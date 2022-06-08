<?php
  $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
  mysqli_query($con,'SET NAMES utf8');

  $userID = $_POST["userID"];
  $dif = $_POST["dif"];
  $park = $_POST["park"];
  $mountain = $_POST["mountain"];
  $forest = $_POST["forest"];
  $sea = $_POST["sea"];
  $beach = $_POST["beach"];
  $trekking = $_POST["trekking"];
  $nature = $_POST["nature"];
  $sights = $_POST["sights"];
  $town = $_POST["town"];
  $scenery = $_POST["scenery"];
  $history = $_POST["history"];
  $response["success"] = true;

  $statement = mysqli_prepare($con, "UPDATE member SET level_like = ?, tag_park = ?, tag_mountain =?, tag_forest =?, tag_sea =?, tag_beach =?,
     tag_trekking =?, tag_nature =?, tag_sights =?, tag_town =?, tag_scenery =?, tag_history =? WHERE userID = '$userID'");
  mysqli_stmt_bind_param($statement, "ssssssssssss", $dif, $park, $mountain, $forest, $sea, $beach, $trekking, $nature, $sights, $town, $scenery, $history);
  mysqli_stmt_execute($statement);
  $response["success"] = true;

  echo json_encode($response);
?>
