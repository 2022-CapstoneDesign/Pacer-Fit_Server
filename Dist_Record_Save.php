<?php

$con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
mysqli_query($con,'SET NAMES utf8');

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

$userID = $_POST["userID"];
$userKm = $_POST["km"];
$userKmTime = $_POST["kmTime"];
$userKmCal = $_POST["kmCal"];
$date = $_POST["$today_date_concat"];

$response["success"] = true;

//거리 걸음수 당일 기록 뽑아오기
$sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$today_km = $row["$date"];
// $response["$today_date_concat.step"] = $row["$today_date_concat"];

//거리 운동시간 당일 기록 뽑아오기
$sql = "SELECT * FROM kmTimeRecord WHERE userID='$userID'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$today_km_time = $row["$date"];
// $response["$today_date_concat.time"] = $row["$today_date_concat"];


//거리 칼로리소모 당일 기록 뽑아오기
$sql = "SELECT * FROM kmCalorieRecord WHERE userID='$userID'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$today_km_cal = $row["$date"];
// $response["$today_date_concat.cal"] = $row["$today_date_concat"];

$f_today_km = floatval($today_km);
$f_today_km_time = floatval($today_km_time);
$f_today_km_cal = floatval($today_km_cal);

$f_today_km += floatval($userKm);
$f_today_km_time += floatval($userKmTime);
$f_today_km_cal += floatval($userKmCal);

$today_km=strval($f_today_km);
$today_km_time=strval($f_today_km_time);
$today_km_cal=strval($f_today_km_cal);

// floatval($today_km) += floatval($userKm);
// floatval($today_km_time) += floatval($userKmTime);
// floatval($today_km_cal) += floatval($userKmCal);

$sql_update = "UPDATE kmRecord
                SET
                  $date = '$today_km'
                WHERE
                  userID = '$userID'";
mysqli_query($con,$sql_update);

$sql_update = "UPDATE kmTimeRecord
                SET
                  $date = '$today_km_time'
                WHERE
                  userID = '$userID'";
mysqli_query($con,$sql_update);

$sql_update = "UPDATE kmCalorieRecord
                SET
                  $date = '$today_km_cal'
                WHERE
                  userID = '$userID'";
mysqli_query($con,$sql_update);

//개인 최고기록 비교, 갱신
$sql = "SELECT * FROM bestRecord WHERE userID='$userID'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$bestKm = $row["bestKm"];
$bestTime_Km = $row['bestTime(km)'];
$bestCalorie_Km = $row['bestCalorie(km)'];

if(floatval($bestKm) < floatval($userKm)){
    $sql_update = "UPDATE bestRecord
                    SET
                      bestKm = '$userKm'
                    WHERE
                      userID = '$userID'";
    mysqli_query($con, $sql_update);
}
if(floatval($bestTime_Km) < floatval($userKmTime)){
    $sql_update = "UPDATE bestRecord
                    SET
                      `bestTime(km)` = '$userKmTime'
                    WHERE
                      userID = '$userID'";
    mysqli_query($con, $sql_update);
}
if(floatval($bestCalorie_Km) < floatval($userKmCal)){
    $sql_update = "UPDATE bestRecord
                    SET
                      `bestCalorie(km)` = '$userKmCal'
                    WHERE
                      userID = '$userID'";
    mysqli_query($con, $sql_update);
}

echo json_encode($response);

?>
