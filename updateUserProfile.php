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
$profileNum= $_POST["profileNum"];


//$userID= "test5";
//$profileNum= 9;


//$sql = "SELECT gpxPath, sigun FROM courseinfo WHERE sigun = ='".$location."'";
//$sql1 = "UPDATE member SET userProfileNum=$profileNum WHERE userID= ='".$userID."'";

$sql1 = "UPDATE member SET userProfileNum='$profileNum' WHERE userID= ".$userID ;



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
