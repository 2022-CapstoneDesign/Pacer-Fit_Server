<?php
 $conn = mysqli_connect("localhost", "root", "1234","pacerfitdb");
if(!$conn)
{
echo "MySQL 접속 에러 : ";
echo mysqli_connect_error();
exit();
}

mysqli_set_charset($conn,"utf8");

$sql = "SELECT COUNT(*) as cnt FROM kmRecord";


$result=mysqli_query($conn, $sql );
$data = array();

if($result){
    while($row=mysqli_fetch_array($result)){
        array_push($data,
            array('members' => $row[0]*1
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
