<?php
 $conn = mysqli_connect("localhost", "root", "1234","pacerfitdb");
if(!$conn)
{
echo "MySQL 접속 에러 : ";
echo mysqli_connect_error();
exit();
}

mysqli_set_charset($conn,"utf8");

$location= "'".$_POST['location']."'";

$sql = "SELECT gpxPath,sigun,crsKorNm,crsSummary,crsTotlRqrmHour,crsLevel,crsDstnc,tag,crsIdx FROM courseinfo WHERE sigun = ".$location;


$result=mysqli_query($conn, $sql);
$data = array();

if($result){
    while($row=mysqli_fetch_array($result)){
        array_push($data,
            array('gpxPath' => $row[0],
            'sigun' => $row[1],
            'crsKorNm' => $row[2], // 코스 제목
            'crsSummary' => $row[3], //코스 요약 설명
            'crsTotlRqrmHour' => $row[4], //시간(분)
            'crsLevel' => $row[5], //코스 난이도(1~3)
            'crsDstnc' => $row[6], //코스 거리(Km)
          	'tag' => $row[7], //코스 해시태그
          	'crsIdx' => $row[8] //코스 id
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
