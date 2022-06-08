<?
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');
    $response = array();
    $userID= $_POST["userID"];
    $location= $_POST["location"];
    $response["success"] = true;

    //난이도 추천
    $sql = "SELECT * FROM member WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
    $level_like = $row["level_like"];
    $level_like_course = array();
    // $sql = "SELECT crsIdx,crsLevel FROM courseinfo WHERE crsLevel='$level_like' AND sigun='$location'";
    $sql = "SELECT crsIdx,crsLevel FROM courseinfo WHERE crsLevel='2' AND sigun='서울 성북구'";
    $result = mysqli_query($con,$sql);
    $i=0;
    print_r("해당 난이도 코스========<br>");
    while($row = mysqli_fetch_array($result)){
        print_r($row["crsIdx"]."<br>");
        $level_like_course[$i++] = $row["crsIdx"];
    }
    print_r("랜덤 난이도 1개========<br>");
    print_r($level_like_course[rand(0,count($level_like_course)-1)]."<br>");
    $response["level_like_course"] = $level_like_course[rand(0,count($level_like_course)-1)];

    //해시 코드 추천
    $park= "        ";
    $mountain= "       ";
    $forest= "       ";
    $sea= "       ";
    $beach= "       ";
    $trekking= "       ";
    $nature= "       ";
    $sights="       ";
    $town= "       ";
    $scenery= "       ";
    $history= "       ";

    $sql = "SELECT tag_park, tag_mountain, tag_forest, tag_sea, tag_beach, tag_trekking, tag_nature, tag_sights, tag_town, tag_scenery, tag_history FROM member WHERE userID = 'servey'";
    $result = mysqli_query($con,$sql);
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

    $sql ="SELECT crsIdx,tag FROM courseinfo WHERE REGEXP_LIKE(tag, '$park|$mountain|$forest|$sea|$beach|$trekking|$nature|$sights|$town|$scenery|$history') AND sigun='서울 성북구'";
    $result = mysqli_query($con,$sql);
    $i=0;
    print_r("해당 해시태그 코스=========<br>");
    while($row = mysqli_fetch_array($result)){
        print_r($row["crsIdx"]."<br>");
        $hashtag_like_course[$i++] = $row["crsIdx"];
    }
    print_r("랜덤 해시태그 1개========<br>");
    if(($hashtag_like_course)!=null){
      print_r($hashtag_like_course[rand(0,count($hashtag_like_course)-1)]."<br>");
      $response["hashtag_like_course"] = $hashtag_like_course[rand(0,count($hashtag_like_course)-1)];
    }
    else{
      $response["hashtag_like_course"] = "";
    }

    //$response["level_like_course"] 레벨 추천
    //$response["hashtag_like_course"] 해시태그 추천
    echo json_encode($response);
?>
