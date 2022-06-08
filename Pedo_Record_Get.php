<?php

$con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
//    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
mysqli_query($con,'SET NAMES utf8');

$userID = $_POST["userID"];
$response["success"] = true;

//test - 로그인시에 오늘 만보기기록 가져오기
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

//만보기 걸음수 당일 기록 뽑아오기
$sql = "SELECT * FROM stepsRecord WHERE userID='$userID'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$response["$today_date_concat.step"] = $row["$today_date_concat"];
//만보기 운동시간 당일 기록 뽑아오기
$sql = "SELECT * FROM stepsTimeRecord WHERE userID='$userID'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$response["$today_date_concat.time"] = $row["$today_date_concat"];


//만보기 칼로리소모 당일 기록 뽑아오기
$sql = "SELECT * FROM stepsCalorieRecord WHERE userID='$userID'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$response["$today_date_concat.cal"] = $row["$today_date_concat"];
// $statementStepsRecord = mysqli_prepare($con, "SELECT * FROM stepsRecord WHERE userID = ?");
// mysqli_stmt_bind_param($statementStepsRecord,"s",$userID);
// mysqli_stmt_execute($statementStepsRecord);
// mysqli_stmt_store_result($statementStepsRecord);
// mysqli_stmt_bind_result($statementStepsRecord,$userID,);

// while(mysqli_stmt_fetch($statementStepsRecord)||$j<365){
//     $response["$keys[$j]"] = $keys[$j];
//     $j++;
// }

echo json_encode($response);

?>
