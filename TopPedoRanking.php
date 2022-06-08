<?php

 $conn = mysqli_connect("localhost", "root", "1234","pacerfitdb");
if(!$conn)
{
echo "MySQL 접속 에러 : ";
echo mysqli_connect_error();
exit();
}

mysqli_set_charset($conn,"utf8");

 //날짜 함수
  function todateTrans($tdate){
          if(substr($tdate,0,1)==0)
            $month = substr($tdate,1,1);
          else
            $month = substr($tdate,0,2);

          if(substr($tdate,2,1)==0)
            $day = substr($tdate,3,1);
          else
            $day = substr($tdate,2,2);

            $date = $month."m".$day."d";

          return $date;
  }


$today = todateTrans(date("md"));


$sql = "SELECT member.userName, stepsRecord.userID, 1m1d+1m2d+1m3d+1m4d+1m5d+1m6d+1m7d+1m8d+1m9d+1m10d+1m11d+1m12d+1m13d+1m14d+1m15d+1m16d+1m17d+1m18d+1m19d+1m20d+1m21d+1m22d+1m23d+1m24d+1m25d+1m26d+1m27d+1m28d+1m29d+1m30d+1m31d+2m1d+2m2d+2m3d+2m4d+2m5d+2m6d+2m7d+2m8d+2m9d+2m10d+2m11d+2m12d+2m13d+2m14d+2m15d+2m16d+2m17d+2m18d+2m19d+2m20d+2m21d+2m22d+2m23d+2m24d+2m25d+2m26d+2m27d+2m28d+3m1d+3m2d+3m3d+3m4d+3m5d+3m6d+3m7d+3m8d+3m9d+3m10d+3m11d+3m12d+3m13d+3m14d+3m15d+3m16d+3m17d+3m18d+3m19d+3m20d+3m21d+3m22d+3m23d+3m24d+3m25d+3m26d+3m27d+3m28d+3m29d+3m30d+3m31d+4m1d+4m2d+4m3d+4m4d+4m5d+4m6d+4m7d+4m8d+4m9d+4m10d+4m11d+4m12d+4m13d+4m14d+4m15d+4m16d+4m17d+4m18d+4m19d+4m20d+4m21d+4m22d+4m23d+4m24d+4m25d+4m26d+4m27d+4m28d+4m29d+4m30d+5m1d+5m2d+5m3d+5m4d+5m5d+5m6d+5m7d+5m8d+5m9d+5m10d+5m11d+5m12d+5m13d+5m14d+5m15d+5m16d+5m17d+5m18d+5m19d+5m20d+5m21d+5m22d+5m23d+5m24d+5m25d+5m26d+5m27d+5m28d+5m29d+5m30d+5m31d+6m1d+6m2d+6m3d+6m4d+6m5d+6m6d+6m7d+6m8d+6m9d+6m10d+6m11d+6m12d+6m13d+6m14d+6m15d+6m16d+6m17d+6m18d+6m19d+6m20d+6m21d+6m22d+6m23d+6m24d+6m25d+6m26d+6m27d+6m28d+6m29d+6m30d+7m1d+7m2d+7m3d+7m4d+7m5d+7m6d+7m7d+7m8d+7m9d+7m10d+7m11d+7m12d+7m13d+7m14d+7m15d+7m16d+7m17d+7m18d+7m19d+7m20d+7m21d+7m22d+7m23d+7m24d+7m25d+7m26d+7m27d+7m28d+7m29d+7m30d+7m31d+8m1d+8m2d+8m3d+8m4d+8m5d+8m6d+8m7d+8m8d+8m9d+8m10d+8m11d+8m12d+8m13d+8m14d+8m15d+8m16d+8m17d+8m18d+8m19d+8m20d+8m21d+8m22d+8m23d+8m24d+8m25d+8m26d+8m27d+8m28d+8m29d+8m30d+8m31d+9m1d+9m2d+9m3d+9m4d+9m5d+9m6d+9m7d+9m8d+9m9d+9m10d+9m11d+9m12d+9m13d+9m14d+9m15d+9m16d+9m17d+9m18d+9m19d+9m20d+9m21d+9m22d+9m23d+9m24d+9m25d+9m26d+9m27d+9m28d+9m29d+9m30d+10m1d+10m2d+10m3d+10m4d+10m5d+10m6d+10m7d+10m8d+10m9d+10m10d+10m11d+10m12d+10m13d+10m14d+10m15d+10m16d+10m17d+10m18d+10m19d+10m20d+10m21d+10m22d+10m23d+10m24d+10m25d+10m26d+10m27d+10m28d+10m29d+10m30d+10m31d+11m1d+11m2d+11m3d+11m4d+11m5d+11m6d+11m7d+11m8d+11m9d+11m10d+11m11d+11m12d+11m13d+11m14d+11m15d+11m16d+11m17d+11m18d+11m19d+11m20d+11m21d+11m22d+11m23d+11m24d+11m25d+11m26d+11m27d+11m28d+11m29d+11m30d+12m1d+12m2d+12m3d+12m4d+12m5d+12m6d+12m7d+12m8d+12m9d+12m10d+12m11d+12m12d+12m13d+12m14d+12m15d+12m16d+12m17d+12m18d+12m19d+12m20d+12m21d+12m22d+12m23d+12m24d+12m25d+12m26d+12m27d+12m28d+12m29d+12m30d+12m31d - $today as \"top_sum\", member.userProfileNum FROM stepsRecord JOIN member ON stepsRecord.userID = member.userID ORDER BY top_sum DESC";

$result=mysqli_query($conn, $sql );
$data = array();

if($result){
    while($row=mysqli_fetch_array($result)){
        array_push($data,
            array('userName' => $row[0],
            'userID' => $row[1],
	'top_sum' => $row[2]*1,
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
