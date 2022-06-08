<?php
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
  mysqli_query($con,'SET NAMES utf8');

  //날짜 함수
  function dateGap($sdate,$edate){
      $sdate = str_replace("-","",$sdate);
      $edate = str_replace("-","",$edate);
      for($i=$sdate;$i<=$edate;$i++){
          $year = substr($i,0,4);

          if(substr($i,4,1)==0)
            $month = substr($i,5,1);
          else
            $month = substr($i,4,2);

          if(substr($i,6,1)==0)
            $day = substr($i,7,1);
          else
            $day = substr($i,6,2);

          if(checkdate($month,$day,$year)){
            $date[$month."m".$day."d"] = $month."m".$day."d";
          }
      }
          return $date;
  }

  $keys = array_keys(dateGap("2022-01-01", "2022-12-31")); //keys배열에 날짜들 모두 저장

  $today_date = date("md");
  if(substr($today_date,0,1)==0){
    $month = substr($today_date,1,1);
  }
  else{
    $month = substr($today_date,0,2);
  }
  if(substr($today_date,2,1)==0){
    $day = substr($today_date,3,1);
  }
  else{
    $day = substr($today_date,2,2);
  }
  $today_date_concat = strval($month."m".$day."d");

  //Response
  $userID = $_POST["userID"];
  $date = $_POST["$today_date_concat"];

  $response["success"] = true;

  //한달 전(30일)부터 오늘까지 Start
  $timestamp = strtotime("-30 days");
  $month_keys = array_keys(dateGap(date('Y-m-d',$timestamp),date('Y-m-d'))); // ex) 4월22일에서 30일전까지의 배열 받기

  //만보기 걸음수 기록 한달 데이터 가져오기
  $sql = "SELECT * FROM stepsRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  $pedo_max_month = $row["$month_keys[0]"];
  for($i=0; $i<count($month_keys); $i++){
    $response["$month_keys[$i]"] = $row["$month_keys[$i]"];
    if($pedo_max_month < $row["$month_keys[$i]"]){
      $pedo_max_month = $row["$month_keys[$i]"];
    }
  }
  $response["pedo_max_recent"] = strval($pedo_max_month);
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($month_keys); $i++){
  //         $response["$month_keys[$i]"] = $row["$month_keys[$i]"];
  //       }
  //     }
  // }

  //한달 전(30일)부터 오늘까지 Start
  $timestamp = strtotime("-30 days");
  $month_keys = array_keys(dateGap(date('Y-m-d',$timestamp),date('Y-m-d'))); // ex) 4월22일에서 30일전까지의 배열 받기

  //만보기 걸음수 기록 한달 데이터 가져오기
  $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  $km_max_month = $row["$month_keys[0]"];
  for($i=0; $i<count($month_keys); $i++){
    $response["$month_keys[$i].km"] = $row["$month_keys[$i]"];
    if($km_max_month < $row["$month_keys[$i]"]){
      $km_max_month = $row["$month_keys[$i]"];
    }
  }
  $response["km_max_recent"] = strval($km_max_month);
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($month_keys); $i++){
  //         $response["$month_keys[$i].km"] = $row["$month_keys[$i]"];
  //       }
  //     }
  // }

  echo json_encode($response);
?>
