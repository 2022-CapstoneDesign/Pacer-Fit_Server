<?php

    $con = mysqli_connect("localhost", "root", "1234","pacerfitdb");
  mysqli_query($con,'SET NAMES utf8');

  $userID = $_POST["userID"];
  $response["success"] = true;

  // 문자열 바꿔주는 함수 (ex. 2022-01-01 -> 1m1d)
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

  //일주일 전(7일)부터 오늘까지 Start
  $timestamp = strtotime("-6 days");
  $day_keys = array_keys(dateGap(date('Y-m-d',$timestamp),date('Y-m-d'))); // ex) 4월22일에서 30일전까지의 배열 받기

  //만보기 걸음수 기록 일주일 데이터 가져오기
  $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  $km_max_day = $row["$day_keys[0]"];
  for($i=0; $i<count($day_keys); $i++){
    $response["$i.day_km"] = $row["$day_keys[$i]"]; //day_km, month_km, 6month_km, year_km
    if($km_max_day < $row["$day_keys[$i]"]){
      $km_max_day = $row["$day_keys[$i]"];
    }
  }
  $response["km_max_day"] = $km_max_day;
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       $km_max_day = $row["$day_keys[0]"];
  //       for($i=0; $i<count($day_keys); $i++){
  //         $response["$i.day_km"] = $row["$day_keys[$i]"]; //day_km, month_km, 6month_km, year_km
  //         if($km_max_day < $row["$day_keys[$i]"]){
  //           $km_max_day = $row["$day_keys[$i]"];
  //         }
  //       }
  //       $response["km_max_day"] = $km_max_day;
  //     }
  // }

  //만보기 시간 기록 한달 데이터 가져오기
  $sql = "SELECT * FROM kmTimeRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  for($i=0; $i<count($day_keys); $i++){
    $response["$i.day_time"] = $row["$day_keys[$i]"];
  }
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($day_keys); $i++){
  //         $response["$i.day_time"] = $row["$day_keys[$i]"];
  //       }
  //     }
  // }

  //한달 전(30일)부터 오늘까지 Start
  $timestamp = strtotime("-30 days");
  $month_keys = array_keys(dateGap(date('Y-m-d',$timestamp),date('Y-m-d'))); // ex) 4월22일에서 30일전까지의 배열 받기

  //만보기 걸음수 기록 한달 데이터 가져오기
  $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  $km_max_month = $row["$month_keys[0]"];
  for($i=0; $i<count($month_keys); $i++){
    $response["$i.month_km"] = $row["$month_keys[$i]"]; //day_km, month_km, 6month_km, year_km
    if($km_max_month < $row["$month_keys[$i]"]){
      $km_max_month = $row["$month_keys[$i]"];
    }
  }
  $response["km_max_month"] = $km_max_month;
  // $response["km_max_month"] = $km_max_month;
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       $km_max_month = $row["$month_keys[0]"];
  //       for($i=0; $i<count($month_keys); $i++){
  //         $response["$i.month_km"] = $row["$month_keys[$i]"]; //day_km, month_km, 6month_km, year_km
  //         if($km_max_month < $row["$month_keys[$i]"]){
  //           $km_max_month = $row["$month_keys[$i]"];
  //         }
  //       }
  //       $response["km_max_month"] = $km_max_month;
  //     }
  // }

  //만보기 시간 기록 한달 데이터 가져오기
  $sql = "SELECT * FROM kmTimeRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  for($i=0; $i<count($month_keys); $i++){
    $response["$i.month_time"] = $row["$month_keys[$i]"];
  }
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($month_keys); $i++){
  //         $response["$i.month_time"] = $row["$month_keys[$i]"];
  //       }
  //     }
  // }
  //6개월 데이터와 1년데이터는 server에서 계산해서 response배열값을 클라이언트에게 보내주기..
  //ex) 1.week : 4000, 2.week: 6000 ... 24.week : 10000
  //ex) 1.year: 30000, 2.year : 40000 ... 12.year : 50000

  //6개월 데이터
  $exceptionWeek = 0;
  $exceptionDay = 0;
  $week_data = array(); //여기에 week 데이터(만보기 기록) 누적하고 최종 데이터 response에 줌
  for($i=0; $i<25; $i++){
    $week_data[$i] = 0;
  }
  $week_data_time = array(); //여기에 week_time 데이터(만보기 시간 기록) 누적하고 최종데이터 response에 줌
  for($i=0; $i<25; $i++){
    $week_data_time[$i] = 0;
  }

  for($week=0;$week<25;$week++){ //6개월 정보들(주차별로 더한값 반환해주기)을 나타내줌
    $firstDate = date('w');
    $ts = time() - (86400 * (date('w') + 7*$week)); // 저저번주 일요일 timestamp
    $startDate = date('Y-m-d', $ts + 86400); // 저저번주 일요일 +1 일. (월요일)
    $endDate = date('Y-m-d', $ts + 86400 * 7); // 저저번주 일요일  +7 일. (일요일)
    $last_week = array_keys(dateGap($startDate,$endDate));
    // print_r("지난주 월~일 <br>");
    for($i=0;$i<count($last_week);$i++){ //0~6 월~일
      // print_r("response['$last_week[$i]']"."<br>");
      //만보기 기록 주마다 데이터 가져오기
      $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
      $result = mysqli_query($con,$sql);
      $row = mysqli_fetch_array($result);
      $week_data[$week] += $row["$last_week[$i]"];
      // while($row = mysqli_fetch_array($result)){
      //     if($row['userID']==$userID){
      //         $week_data[$week] += $row["$last_week[$i]"];
      //     }
      // }
      //만보기 시간 기록 주마다 데이터 가져오기
      $sql = "SELECT * FROM kmTimeRecord WHERE userID='$userID'";
      $result = mysqli_query($con,$sql);
      $row = mysqli_fetch_array($result);
      $week_data_time[$week] += $row["$last_week[$i]"];
      // while($row = mysqli_fetch_array($result)){
      //     if($row['userID']==$userID){
      //         $week_data_time[$week] += $row["$last_week[$i]"];
      //     }
      // }
      if(strcmp(substr($last_week[$i],0,3),"12m")==false){ //12m~같을경우
        if(strcmp(substr($last_week[$i+1],0,2),"1m")==false){ //1m1d 이후에 12m31이 나온경우
          $exceptionWeek = $week;
          $exceptionDay = $i;
          break;
        }
      }
    }
    // if(strcmp(substr($last_week[0],0,3),"12m")==false){ //12m~같을경우
    //   if(strcmp(substr($last_week[1],0,3),"12m")==false
    //     && strcmp(substr($last_week[2],0,3),"12m")==false
    //     && strcmp(substr($last_week[3],0,3),"12m")==false
    //     && strcmp(substr($last_week[4],0,3),"12m")==false
    //     && strcmp(substr($last_week[5],0,3),"12m")==false
    //     && strcmp(substr($last_week[6],0,3),"12m")==false);
    //     else{
    //       for($week=$exceptionWeek; $week<25; $week++){ //12월이 포함된 주 ~ 11월까지 week는 모두 0으로 넣어줌 (현재 4월)
    //         $firstDate = date('w');
    //         $ts = time() - (86400 * (date('w') + 7*$week)); // 저저번주 일요일 timestamp
    //         $startDate = date('Y-m-d', $ts + 86400); // 저저번주 일요일 +1 일. (월요일)
    //         $endDate = date('Y-m-d', $ts + 86400 * 7); // 저저번주 일요일  +7 일. (일요일)
    //         $last_week = array_keys(dateGap($startDate,$endDate));
    //         for($qq=count($last_week)-1;$qq>=0;$qq--){
    //           // print_r("response['$last_week[$qq]']"."<br>");
    //         }
    //         // print_r($week."번째 주 response[".$week."week] = 0<br>");
    //         //만보기 기록 주마다 데이터 가져오기
    //         $sql = "SELECT * FROM kmRecord";
    //         $result = mysqli_query($con,$sql);
    //         while($row = mysqli_fetch_array($result)){
    //             if($row['userID']==$userID){
    //               for($i=$week; $i<count($last_week); $i++){
    //                 $week_data[$week] += $row["$last_week[$i]"];
    //               }
    //             }
    //         }
    //         //만보기 시간 기록 주마다 데이터 가져오기
    //         $sql = "SELECT * FROM kmTimeRecord";
    //         $result = mysqli_query($con,$sql);
    //         while($row = mysqli_fetch_array($result)){
    //             if($row['userID']==$userID){
    //               for($i=$week; $i<count($last_week); $i++){
    //                 $week_data_time[$week] += $row["$last_week[$i]"];
    //               }
    //             }
    //         }
    //         // print_r($week."번째 주 response['$week.week'] = 0<br>"); //response 예외처리
    //       }
    //       break;
    //   }
    // }

    // print_r($week."번째 주 response['$week.week'] = 값<br>"); //여기서 response
    // print_r("지난".$week."주끝<br>");
  }
  //만보기 기록 주마다 데이터 가져오기
  $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  for($i=0; $i<count($week_data); $i++){
     $response["$i.week_km"] = strval($week_data[$i]);
  }
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($week_data); $i++){
  //          $response["$i.week_km"] = strval($week_data[$i]);
  //       }
  //     }
  // }
  //만보기 시간 기록 주마다 데이터 가져오기
  $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  for($i=0; $i<count($week_data_time); $i++){
     $response["$i.week_time"] = strval($week_data_time[$i]);
  }
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($week_data_time); $i++){
  //          $response["$i.week_time"] = strval($week_data_time[$i]);
  //       }
  //     }
  // }
  //만보기 주차별 최고기록 뽑기 response.
  $km_max_180 = $week_data[0];
  for($i=0; $i<count($week_data); $i++){
    if($km_max_180 < $week_data[$i])
      $km_max_180 = $week_data[$i];
  }
  $response["km_max_180"] = strval($km_max_180);

  //1년 만보기 데이터
  $start_time = strtotime(date('Y-m-01'));
  $year_keys = array();
  $km_record_year_km = array(); //1년 만보기 데이터 합 배열
  $km_record_year_time = array(); //1년 만보기 시간 데이터 합 배열
  for($i=0; $i<12; $i++){
    $year_keys[$i] = 0;
    $km_record_year_km[$i] = 0;
    $km_record_year_time[$i] = 0;
  }
  for($i=0; $i<12; $i++){
    $year_keys[$i] = array_keys(dateGap(date('Y-m-d',strtotime("-$i month", $start_time)),date('Y-m-t',strtotime("-$i month", $start_time))));
  }

  $sql = "SELECT * FROM kmRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  for($i=0; $i<count($year_keys); $i++){
    for($j=0; $j<count($year_keys[$i]); $j++){
        //만보기 기록 달마다 데이터 가져오기
        $km_record_year_km[$i] += $row["{$year_keys[$i][$j]}"];
    }
  }
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($year_keys); $i++){
  //         for($j=0; $j<count($year_keys[$i]); $j++){
  //             //만보기 기록 달마다 데이터 가져오기
  //             $km_record_year_km[$i] += $row["{$year_keys[$i][$j]}"];
  //         }
  //       }
  //     }
  // }
  $sql = "SELECT * FROM kmTimeRecord WHERE userID='$userID'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($result);
  for($i=0; $i<count($year_keys); $i++){
    for($j=0; $j<count($year_keys[$i]); $j++){
        //만보기 기록 달마다 데이터 가져오기
        $km_record_year_time[$i] += $row["{$year_keys[$i][$j]}"];
    }
  }
  // while($row = mysqli_fetch_array($result)){
  //     if($row['userID']==$userID){
  //       for($i=0; $i<count($year_keys); $i++){
  //         for($j=0; $j<count($year_keys[$i]); $j++){
  //             //만보기 기록 달마다 데이터 가져오기
  //             $km_record_year_time[$i] += $row["{$year_keys[$i][$j]}"];
  //         }
  //       }
  //     }
  // }
  //만보기 월별 최고기록 뽑기 response.
  $km_max_year = $km_record_year_km[0];
  for($i=0; $i<count($km_record_year_km); $i++){
    $response["$i.year_km"] = strval($km_record_year_km[$i]);
    $response["$i.year_time"] = strval($km_record_year_time[$i]);
    if($km_max_year < $km_record_year_km[$i])
      $km_max_year = $km_record_year_km[$i];
  }
  $response["km_max_year"] = strval($km_max_year);
  //1년 만보기 데이터
  echo json_encode($response);
?>
