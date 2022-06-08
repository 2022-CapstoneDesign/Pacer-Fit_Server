<?php
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');

    // $userID = $_POST["userID"];
    // $userPassword = $_POST["userPassword"];
    $userID = mysqli_real_escape_string($con, $_POST["userID"]);
    $userPassword = mysqli_real_escape_string($con, $_POST["userPassword"]);

    $statement = mysqli_prepare($con, "SELECT * FROM member WHERE userID = ? AND userPassword = ?");
    mysqli_stmt_bind_param($statement, "ss", $userID, $userPassword);
    mysqli_stmt_execute($statement);


    mysqli_stmt_store_result($statement);
    mysqli_stmt_bind_result($statement, $userID, $userPassword, $userName, $userGender, $userAge, $userHeight, $userWeight, $userProfileNum
  ,$level_like,$tag_park,$tag_mountain,$tag_forest,$tag_sea,$tag_beach,$tag_trekking,$tag_nature,$tag_sights,$tag_town,$tag_scenery,$tag_history); //모든 DB의 컬럼이 bind되어야함

    $response = array();
    $response["success"] = false;

    while(mysqli_stmt_fetch($statement)) {
        $response["success"] = true;
        $response["userID"] = $userID;
        $response["userPassword"] = $userPassword;
        $response["userName"] = $userName;
        $response["userGender"] = $userGender;
        $response["userAge"] = $userAge;
        $response["userHeight"] = $userHeight;
        $response["userWeight"] = $userWeight;
        $response["userProfileNum"] = $userProfileNum;

    }

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

    //만보기-기록보기(최근한달) -> 중에 최고기록을 가져와서 클라이언트에게 그래프 Y축 최대치 설정

    //한달 전(30일)부터 오늘까지 Start
    $timestamp = strtotime("-30 days");
    $month_keys = array_keys(dateGap(date('Y-m-d',$timestamp),date('Y-m-d'))); // ex) 4월22일에서 30일전까지의 배열 받기
    $pedo_max = 0;
    //만보기 걸음수 기록 한달 데이터 가져오기
    $sql = "SELECT * FROM stepsRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
    $pedo_max = $row["$month_keys[0]"];
    for($i=0; $i<count($month_keys); $i++){
      if($pedo_max < $row["$month_keys[$i]"]){
        $pedo_max = $row["$month_keys[$i]"];
      }
    }
    $response["pedo_max"] = $pedo_max;
    // while($row = mysqli_fetch_array($result)){
    //     if($row['userID']==$userID){
    //       $pedo_max = $row["$month_keys[0]"];
    //       for($i=0; $i<count($month_keys); $i++){
    //         if($pedo_max < $row["$month_keys[$i]"]){
    //           $pedo_max = $row["$month_keys[$i]"];
    //         }
    //       }
    //       $response["pedo_max"] = $pedo_max;
    //     }
    // }

    //한달 전(30일)부터 오늘까지 Start
    $timestamp = strtotime("-30 days");
    $month_keys = array_keys(dateGap(date('Y-m-d',$timestamp),date('Y-m-d'))); // ex) 4월22일에서 30일전까지의 배열 받기
    $pedo_max = 0;
    //한달 전(30일) Km기록 한달 데이터 가져오기
    $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
    $pedo_max = $row["$month_keys[0]"];
    for($i=0; $i<count($month_keys); $i++){
      if($pedo_max < $row["$month_keys[$i]"]){
        $pedo_max = $row["$month_keys[$i]"];
      }
    }
    $response["km_max"] = $pedo_max;
    // while($row = mysqli_fetch_array($result)){
    //     if($row['userID']==$userID){
    //       $pedo_max = $row["$month_keys[0]"];
    //       for($i=0; $i<count($month_keys); $i++){
    //         if($pedo_max < $row["$month_keys[$i]"]){
    //           $pedo_max = $row["$month_keys[$i]"];
    //         }
    //       }
    //       $response["km_max"] = $pedo_max;
    //     }
    // }

    $sql = "SELECT * FROM member WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
    $response["level_like"] = $row["level_like"];

    //해시 코드 추천
    $park= " ";
    $mountain= " ";
    $forest= " ";
    $sea= " ";
    $beach= " ";
    $trekking= " ";
    $nature= " ";
    $sights=" ";
    $town= " ";
    $scenery= " ";
    $history= " ";

    $sql = "SELECT tag_park, tag_mountain, tag_forest, tag_sea, tag_beach, tag_trekking, tag_nature, tag_sights, tag_town, tag_scenery, tag_history FROM member WHERE userID ='$userID'";
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
    $response["myHashtag"] = "$park,$mountain,$forest,$sea,$beach,$trekking,$nature,$sights,$town,$scenery,$history";

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
