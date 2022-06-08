<?
require 'vendor/autoload.php';
use PHPJuice\Slopeone\Algorithm;

// Create an instance
$slopeone = new Algorithm();

$conn = mysqli_connect("localhost", "root", "1234","pacerfitdb");
if(!$conn)
{
echo "MySQL 접속 에러 : ";
echo mysqli_connect_error();
exit();
}

mysqli_set_charset($conn,"utf8");

$couseID = array('T_CRS_MNG0000000886',
	'T_CRS_MNG0000001243',
	'T_CRS_MNG0000002921',
	'T_CRS_MNG0000003001',
	'T_CRS_MNG0000003002',
	'T_CRS_MNG0000003003',
	'T_CRS_MNG0000005602',
	'T_CRS_MNG0000005603',
	'T_CRS_MNG0000005601',
	'T_CRS_MNG0000005600');


$userID= "'".$_POST['userID']."'";
$response = array();
$location= "'".$_POST["location"]."'";
$response["success"] = true;

    //난이도 추천
    $sql = "SELECT * FROM member WHERE userID=".$userID;
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
    $level_like = $row["level_like"];
    $level_like_course = array();
    $sql = "SELECT crsIdx,crsLevel FROM courseinfo WHERE crsLevel='$level_like' AND sigun=".$location;
    //$sql = "SELECT crsIdx,crsLevel FROM courseinfo WHERE crsLevel='2' AND sigun='서울 성북구'";
    $result = mysqli_query($conn,$sql);
    $i=0;
    //print_r("해당 난이도 코스========<br>");
    while($row = mysqli_fetch_array($result)){
        //print_r($row["crsIdx"]."<br>");
        $level_like_course[$i++] = $row["crsIdx"];
    }
    //print_r("랜덤 난이도 1개========<br>");
    //print_r($level_like_course[rand(0,count($level_like_course)-1)]."<br>");

     if(($level_like_course)!=null){
      //print_r($hashtag_like_course[rand(0,count($level_like_course)-1)]."<br>");
     $response["level_like_course"] = $level_like_course[rand(0,count($level_like_course)-1)];
    }
    else{
      $response["level_like_course"] = "";
    }

    //해시 코드 추천
    $park= "        ";
    $mountain= "       ";
    $forest= "       ";
    $sea= "       ";
    $beach= "       ";
    $trekking= "      ";
    $nature= "       ";
    $sights="       ";
    $town= "        ";
    $scenery= "        ";
    $history= "        ";

    $sql = "SELECT tag_park, tag_mountain, tag_forest, tag_sea, tag_beach, tag_trekking, tag_nature, tag_sights, tag_town, tag_scenery, tag_history FROM member WHERE userID =".$userID;
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);

    if($row[0] ==1)
    $park="공원";
    if($row[1] ==1)
    $mountain="산";
    if($row[2] ==1)
    $forest="숲";
    if($row[3] ==1)
    $sea="바다";
    if($row[4] ==1)
    $beach="해변";
    if($row[5] ==1)
    $trekking="트레킹";
    if($row[6] ==1)
    $nature="자연";
    if($row[7] ==1)
    $sights="명소";
    if($row[8] ==1)
    $town="동네";
    if($row[9] ==1)
    $scenery="풍경";
    if($row[10] ==1)
    $history="역사";

    $sql ="SELECT crsIdx,tag FROM courseinfo WHERE REGEXP_LIKE(tag, '$park|$mountain|$forest|$sea|$beach|$trekking|$nature|$sights|$town|$scenery|$history') AND sigun=".$location;
    $result = mysqli_query($conn,$sql);
    $i=0;
    //print_r("해당 해시태그 코스=========<br>");
    while($row = mysqli_fetch_array($result)){
        //print_r($row["crsIdx"]."<br>");
        $hashtag_like_course[$i++] = $row["crsIdx"];
    }
    //print_r("랜덤 해시태그 1개========<br>");
    if(($hashtag_like_course)!=null){
      //print_r($hashtag_like_course[rand(0,count($hashtag_like_course)-1)]."<br>");
      $response["hashtag_like_course"] = $hashtag_like_course[rand(0,count($hashtag_like_course)-1)];
    }
    else{
      $response["hashtag_like_course"] = "";
    }


// 슬로프원 추천
$sql = "SELECT T_CRS_MNG0000000886,T_CRS_MNG0000001243,T_CRS_MNG0000002921,T_CRS_MNG0000003001,T_CRS_MNG0000003002,
T_CRS_MNG0000003003,T_CRS_MNG0000005602,T_CRS_MNG0000005603,T_CRS_MNG0000005601,T_CRS_MNG0000005600 FROM seongbukgu_rate WHERE userID = ".$userID;

$result=mysqli_query($conn,$sql);

$column_data=mysqli_fetch_array($result);
$c_data= array();

for($i=0;$i<10;$i++)
{
$c_data[$i]=floatval($column_data[$i]);
}

$count = 9; // 코스수(0부터세서)
for($i=0;$i<=$count;$i++){
	if($count==0) break;
	if($c_data[$i] == 0){
		array_splice($couseID, $i, 1);
		array_splice($c_data, $i, 1);
		$i--;
		$count--;
	}
}

//couseID, column_data 전처리완료


$sql = "SELECT * FROM seongbukgu_rate WHERE 1";

$result=mysqli_query($conn, $sql );
$data = array();

if($result){
   while($row=mysqli_fetch_array($result)){
       array_push($data,
           array(
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
}
else{
echo "SQL문 처리중 에러 발생 : ";
echo mysqli_error($conn);
}

$dataC = array();
for($i=0;$i<=$count;$i++){
$dataC[$couseID[$i]] = $c_data[$i];
}

if($count!=0){

$slopeone->update($data);
$results = $slopeone->predict($dataC);

arsort($results);

$results_index = array_values($results);

$str = array_search ( $results_index[0], $results);

$data_json = array();
array_push($data_json, array('slope_crs' => $str, 'level_crs' => $response["level_like_course"], 'hash_crs' => $response["hashtag_like_course"]));


header('Content-Type: application/json; charset=utf8');
$json = json_encode(array("pacerfit"=>$data_json), JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE+JSON_UNESCAPED_SLASHES);
echo $json;

}else if($count ==0){
$random = rand(0,9);
$results_index = array_values($data[0]);
$str = array_search ( $results_index[$random], $data[0]);

$data_json = array();
array_push($data_json, array('slope_crs' => $str, 'level_crs' => $response["level_like_course"], 'hash_crs' => $response["hashtag_like_course"]));

header('Content-Type: application/json; charset=utf8');
$json = json_encode(array("pacerfit"=>$data_json), JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE+JSON_UNESCAPED_SLASHES);
echo $json;
}


mysqli_close($conn);
?>
