<?php

 $conn = mysqli_connect("localhost", "root", "1234","pacerfitdb");
if(!$conn)
{
echo "MySQL 접속 에러 : ";
echo mysqli_connect_error();
exit();
}

mysqli_set_charset($conn,"utf8");





$sql = "SELECT * FROM seongbukgu_rate WHERE 1";

$result=mysqli_query($conn, $sql );
$data = array();
$data1 = array();

if($result){
    while($row=mysqli_fetch_array($result)){
        array_push($data,
            array('userID' => $row[0],
            'T_CRS_MNG0000000886' => $row[1]*1,
	'T_CRS_MNG0000001243' => $row[2]*1,
	'T_CRS_MNG0000002921' => $row[3]*1,
	'T_CRS_MNG0000003001' => $row[4]*1,
	'T_CRS_MNG0000003002' => $row[5]*1,
	'T_CRS_MNG0000003003' => $row[6]*1,
	'T_CRS_MNG0000005602' => $row[7]*1,
	'T_CRS_MNG0000005603' => $row[8]*1,
	'T_CRS_MNG0000005601' => $row[9]*1,
	'T_CRS_MNG0000005600' => $row[10]*1,
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
