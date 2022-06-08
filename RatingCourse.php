<?php
    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
    mysqli_query($con,'SET NAMES utf8');
    $response = array();

    $userID= $_POST["userID"];
    $CourseName= $_POST["CourseName"];
    $rate = floatval($_POST["rate"]);
    $response["success"] = true;

    if($CourseName=="서울도보관광코스 성북동" || $CourseName=="정릉숲 산책길" || $CourseName=="북악스카이웨이길" || $CourseName=="북악하늘길 1산책로" ||
     $CourseName=="북악하늘길 2산책로" || $CourseName=="북악하늘길 3산책로" || $CourseName=="성북 역사 탐방 길" || $CourseName=="성북천 길" ||
      $CourseName=="낙산 성곽 길" || $CourseName=="낙산공원 길"){
            $sql ="SELECT crsIdx FROM courseinfo WHERE crsKorNm='$CourseName'";
            // $sql = "SELECT crsIdx FROM courseinfo WHERE crsKorNm='서울도보관광코스 성북동'";
            $result = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($result);
            $crsIdx = $row["crsIdx"];

            $sql = "SELECT `$crsIdx` FROM seongbukgu_rate WHERE userID='$userID'";
            $result = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($result);
            $total = floatval($row["$crsIdx"]);

            if($total == 0){
              $sql = "UPDATE seongbukgu_rate SET `$crsIdx`=$rate WHERE userID='$userID'";
              mysqli_query($con,$sql);
            }
            else{
              $total = ($total+$rate)/2;
              $sql = "UPDATE seongbukgu_rate SET `$crsIdx`=$total WHERE userID='$userID'";
              mysqli_query($con,$sql);
            }
      }
    else{
      echo json_encode($response);
      return;
    }

    echo json_encode($response);

?>
