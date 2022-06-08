<?php
 $conn = mysqli_connect("localhost", "root", "1234","pacerfitdb");
if(!$conn)
{
echo "MySQL 접속 에러 : ";
echo mysqli_connect_error();
exit();
}

mysqli_set_charset($conn,"utf8");


$userID= "'".$_POST["userID"]."'";
$selectedDifficulty= $_POST["selectedDiffculty"];
$park= $_POST["park"];
$mountain= $_POST["mountain"];
$forest= $_POST["forest"];
$sea= $_POST["sea"];
$beach= $_POST["beach"];
$trekking= $_POST["trekking"];
$nature= $_POST["nature"];
$sights= $_POST["sights"];
$town= $_POST["town"];
$scenery= $_POST["scenery"];
$history= $_POST["history"];



//$userID= "test5";
//$profileNum= 9;


//$sql = "SELECT gpxPath, sigun FROM courseinfo WHERE sigun = ='".$location."'";
//$sql1 = "UPDATE member SET userProfileNum=$profileNum WHERE userID= ='".$userID."'";
//$sql1 = "UPDATE member SET tag_park='$park' WHERE userID= ".$userID ;

$sql1 = "UPDATE member SET level_like= $selectedDifficulty, tag_park = $park, tag_mountain= $mountain, tag_forest= $forest, tag_sea=$sea, tag_beach=$beach, tag_trekking=$trekking, tag_nature=$nature, tag_sights=$sights, tag_town=$town, tag_scenery=$scenery, tag_history=$history WHERE userID= ".$userID;




$result1=mysqli_query($conn, $sql1);

$data = array();

if($result1){
   echo "good";
}
else{
echo "SQL문 처리중 에러 발생 : ";
echo mysqli_error($conn);
}


mysqli_close($conn)
?>
