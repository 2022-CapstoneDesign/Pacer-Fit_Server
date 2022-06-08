<?php

 $conn =mysqli_connect("localhost", "root", "1234","pacerfitdb");
if(!$conn)
{
echo "MySQL 접속 에러 : ";
echo mysqli_connect_error();
exit();
}

mysqli_set_charset($conn,"utf8");

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


$today = time();
$week = date("w");

$week_first = $today-($week*86400);
$week_last = $week_first+(6*86400);

$prev_frdate = date("Y-m-d",$week_first-(86400*6));
$prev_todate = date("Y-m-d",$week_last-(86400*6));


$keys = array_keys(dateGap($prev_frdate, $prev_todate));


$sql = "SELECT member.userName, stepsRecord.userID, $keys[0] + $keys[1] + $keys[2] + $keys[3] + $keys[4] + $keys[5] + $keys[6] as \"week_sum\", member.userProfileNum FROM stepsRecord JOIN member ON stepsRecord.userID = member.userID ORDER BY week_sum DESC";

$result=mysqli_query($conn, $sql );
$data = array();

if($result){
    while($row=mysqli_fetch_array($result)){
        array_push($data,
            array('userName' => $row[0],
            'userID' => $row[1],
	'week_sum' => $row[2]*1,
	'profile_num' => $row[3]*1
            ));
    }

    header('Content-Type: application/json; charset=utf8');
    $json = json_encode(array("pacerfit"=>$data), JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE+JSON_UNESCAPED_SLASHES);
    echo $json;

}
else{
echo "SQL문 처리중 에러 발생 : ";
echo mysqli_error($conn);
}

mysqli_close($conn)



?>
