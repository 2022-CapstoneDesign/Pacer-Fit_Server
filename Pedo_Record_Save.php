<?php
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    //    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');

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

    $userID = $_POST["userID"];
    $userPedoStep = $_POST["steps"];
    $userPedoTime = $_POST["pedoTime"];
    $userPedoCal = $_POST["pedoCal"];
    $date = $_POST["$today_date_concat"];

    $response["success"] = true;

    $sql_update = "UPDATE stepsRecord
                    SET
                      $date = '$userPedoStep'
                    WHERE
                      userID = '$userID'";
    mysqli_query($con,$sql_update);

    $sql_update = "UPDATE stepsTimeRecord
                    SET
                      $date = '$userPedoTime'
                    WHERE
                      userID = '$userID'";
    mysqli_query($con,$sql_update);

    $sql_update = "UPDATE stepsCalorieRecord
                    SET
                      $date = '$userPedoCal'
                    WHERE
                      userID = '$userID'";
    mysqli_query($con,$sql_update);

    //개인 최고기록 비교, 갱신
    $sql = "SELECT * FROM bestRecord WHERE userID='$userID'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
    $bestStep = $row["bestSteps"];
    $bestTime_Steps = $row['bestTime(steps)'];
    $bestCalorie_Steps = $row['bestCalorie(steps)'];

    if(intval($bestStep) < intval($userPedoStep)){
        $sql_update = "UPDATE bestRecord
                        SET
                          bestSteps = '$userPedoStep'
                        WHERE
                          userID = '$userID'";
        mysqli_query($con, $sql_update);
    }
    if(intval($bestTime_Steps) < intval($userPedoTime)){
        $sql_update = "UPDATE bestRecord
                        SET
                          `bestTime(steps)` = '$userPedoTime'
                        WHERE
                          userID = '$userID'";
        mysqli_query($con, $sql_update);
    }
    if(floatval($bestCalorie_Steps) < floatval($userPedoCal)){
        $sql_update = "UPDATE bestRecord
                        SET
                          `bestCalorie(steps)` = '$userPedoCal'
                        WHERE
                          userID = '$userID'";
        mysqli_query($con, $sql_update);
    }

    echo json_encode($response);
?>
