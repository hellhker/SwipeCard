<?php
namespace Bedu\CourseBundle\Controllers;


use PhpOffice\PhpSpreadsheet\IOFactory;
use Bedu\ApiServiceBundle\Controllers\ApiBaseController;
use Bedu\ResourceBundle\Controllers\ResourceApiController;


use Bcom\Common\ArrayToolkit;
use Bcom\Common\FileToolkit;
use Bcom\Common\Paginator;
use Bcom\Common\StringToolkit;
use Bcom\Common\RoleToolkit;

class TimeTableDetailController extends ApiBaseController
{




    /**
     * 导入excel，按规则解析，验证后，批量导入数据库
     * 1、根据前端传入的路径，寻找excel，通过PhpSpreadsheet读取excel档，返回 $sheetData
     * 2、验证表格中的教室，教师，课程，如果缺失，return false，如果正常，继续
     * 3、通过传入的作息表id，学期表id,获取对应的作息表和学期表信息
     * 4、通过相应的规则，将表格原始数据通过教室，周数，节次，解析成与服务器数据库相对应的格式
     * 5、将相关数据插入状态表（课表id，课表名称，创建时间，解析后数据条数）
     * 6、将解析后的数据与服务器中的import_lesson表比对
     *  1>如果有重复数据，跳过
     *  2>如果只有部分栏位相同，进行更新
     *  3>如果没有此条数据，跳过
     * 7、插入数据库过程中若出现错误，应记录错误栏位信息，return false,如正常，修改状态表状态，调取 嘉先 serviceImpl。
     */
    public function createAction()//TODO 完善异常处理
    {
        //0、获取必需参数并判断 并上传文件
        $schoolId = $this->getAdminSchoolId();
        $nowDate = $this->getParam($this->request, "nowDate");
        $file = $this->request->getSmfRequest()->files->get('timetable');
        $termId = $this->getParam($this->request, "termId");
        $timeTableName = $this->getParam($this->request, "name");

        //var_dump($timeTableName);
        //var_dump($termId);
        //var_dump($file);
        if (empty($file) || empty($termId) || empty($timeTableName)) {
            return $this->createJsonResponse(array("code" => -4, "msg" => "必输参数为空"));
        }
        $fileDir = 'timetable' . $this->getAdminSchoolId() . "_" . time();
        $filename = $fileDir . '.' . $file->getClientOriginalExtension();
        $directory = "{$this->config->parameters->get('bwxt.upload.public_directory', true)}/timetable/" . $fileDir;
//        if (!is_dir($directory)) {
//            @mkdir($directory, 0755, true);
//        }
        $file = $file->move($directory, $filename);
        if(!$file){
            return $this->createJsonResponse(array("code" => 1, "msg" => "文件上传失败"));
        }

        $fullPath = $directory."/".$filename;
        //$fullPath = __DIR__ . '/testupload/test1.xlsx';

        //1、获取教师，教师，课程，和$sheetData比对
        $spreadsheet = IOFactory::load($fullPath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        //$termDetail = $this->getTermService()->getTermDetail($termId);
        //$restDetail = $this->getRestService()->getRestDetail($termId);
        //var_dump($restDetail);


        $tempArrayOne = array();
        $tempArrayTwo = array();
//        $teacherID[] = $val['F']; TODO
//        $roomId[] = $val['L'];
//        $courseId[] = $val['B'];



        $restArray = array(
            '1'=>'08:00-08:50',
            '2'=>'09:00-09:50',
            '3'=>'10:00-10:50',
            '4'=>'11:00-11:50',
            '5'=>'13:00-13:50',
            '6'=>'14:00-14:50',
            '7'=>'15:00-15:50',
            '8'=>'16:00-16:50',
            '9'=>'17:00-17:50',
            '10'=>'18:00-18:50',
        );
//-------------- 班级分段 周数分段
        $countI = 0;
        $j = 0;
        $tempWeek = 0;
        foreach($sheetData as $key => $val){
            if($val['A']=='序号')
                continue;
            $class_list = explode(',',$val['G']);
            foreach($class_list as $key1 => $class){
                $process_before_week = $val['H'];
                $process_step1_week  = strstr($process_before_week, '周', TRUE);
                if(strpos($process_step1_week,',')!==false){
                    $process_step2_week = explode(",", $process_step1_week);
                    foreach($process_step2_week as $key2 => $weeks){
                        if(strpos($weeks,'-')!==false){//1-2,4-5
                            $ba = explode("-",$weeks);
                            $before = $ba[0];
                            $after = $ba[1];
                            $diff = $after - $before;
                            for($i = 0; $i <= $diff; $i++){
                                $tempWeek = $before+$i;
                                $j++;
                                $tempArrayOne[] = array(
                                    'class'=>$class,
                                    'week'=>$tempWeek,
                                    'key'=>$key
                                );
                            }
                        }else{//1,5
                            $tempWeek = $weeks;
                            $tempArrayOne[] = array(
                                'class'=>$class,
                                'week'=>$tempWeek,
                                'key'=>$key
                            );
                        }
                    }
                }else{
                    if(strpos($process_step1_week,'-')!==false){//2-4
                        $ba = explode("-",$process_step1_week);
                        $before = $ba[0];
                        $after = $ba[1];
                        $diff = $after - $before;
                        for($i = 0; $i <= $diff; $i++){
                            $tempWeek = $before+$i;
                            $countI++;

                            $tempArrayOne[] = array(
                                'class'=>$class,
                                'week'=>$tempWeek,
                                'key'=>$key
                            );
                        }
                    }else{//4
                        $tempArrayOne[] = array(
                            'class'=>$class,
                            'week'=>$process_step1_week,
                            'key'=>$key
                        );

                    }
                }
            }

        }

        //节次分段
        $startTermDate = '2019-05-06';
        $endTermDate = '2019-06-30';
        function getNowDate($startTermDate,$week,$weekday){
            $mweek = $week - 1;
            $mweekday = $weekday - 1;
            $workDate = date("Y-m-d",strtotime('+'.$mweek * 7 + $mweekday.' days',strtotime($startTermDate)));
            return $workDate;
        }

        foreach($tempArrayOne as $key => $val){
            $startSection = $sheetData[$val['key']]['J'];
            $endSection = $startSection + $sheetData[$val['key']]['K'] - 1;
            $tmpI = 0;
            for($tmpI = $startSection;$tmpI<=$endSection;$tmpI++){
                $timeSection = $restArray[$tmpI];
                $sectionTimes = explode('-',$timeSection);
                $sTime = $sectionTimes[0];
                $eTime = $sectionTimes[1];
                $nowDate = getNowDate($startTermDate,$sheetData[$val['key']]['I'],$sheetData[$val['key']]['J']);

                $startTime = date("$nowDate $sTime");
                $endTime = date("$nowDate $eTime");

                $tempArrayTwo[] = array(
                    'class'=>$val['class'],
                    'week'=>$val['week'],
                    'key'=>$val['key'],
                    'date'=>$nowDate,
                    'node'=>$tmpI,
                    'startTime'=>$startTime,
                    'endTime'=>$endTime,
                );
            }
        }
        $tempArrayOne = null;//清空临时数组
        //echo $countI."<BR>";
        $countSheet = 0;
        //$cSheet = count($tempArrayTwo);
        $sTime=microtime(true);//获取程序开始执行的时间
        //echo $sTime."<BR>";
        foreach($tempArrayTwo as $key => $val){
            if($sheetData[$val['key']]=='序号')
                continue;
            $roomId = 'testRoom';
            $teacherId = '1';
            $teacherName = 'testTName';
            $termName = '测试学期';
            $tableId = '1';
            $startTime = strtotime($val['startTime']);
            $endTime = strtotime($val['endTime']);
            $date = strtotime($val['date']);
            strtotime($val['startTime']);
            $data[$countSheet] =   array (
                'schoolId' => $schoolId,
                'restId' =>1,
                'termId' => $termId,
                'termName' => $termName,
                'startTime'=>$startTime,
                'endTime'=>$endTime,
                'date'=>$date,
                'roomId'=>$roomId,
                'classRoomName'=>$sheetData[$val['key']]['L'],
                'node'=>$val['node'],
                'mobileNo' => $sheetData[$val['key']]['E'],
                'speakerId' => $teacherId,
                'teacherName' => $teacherName,
                'courseName' => $sheetData[$val['key']]['D'],
                'className' => $val['class'],
                'courseLessonName' => $sheetData[$val['key']]['D'],
                'tableId' => $tableId,

            );

            if($countSheet>=10)
                break;
            $countSheet++;
        }

        $eTime=microtime(true);//获取程序开始执行的时间
        //echo "EndTime".$eTime."<BR>";
        //var_dump($data);


//        return $data;
//        return;
        $createTime = strtotime(date('Y-m-d H:i:s'));
        $countSum = count($data);
        $param = array(
            'tableName'=>$timeTableName,
            'createTime'=>$createTime,
            'schoolId'=>$schoolId,
            'countSum'=>$countSum,
            'requestSum'=>0
        );

        $timeDetail = "123";
        if ($timeDetail)


        //$timeDetail = $this->getTimeTableService()->create($sql);
        $timeDetail = "123";
        if ($timeDetail) {
            $summaryInsert = $this->getTimeTableSummaryService()->createSummary($param);
            //var_dump(($summaryInsert));
            //$summary1 = $this->getTimeTableSummaryService()->addItem($countSheet);
            $tableId = $summaryInsert['id'];
            foreach ($data as $key => $val){
                $data[$key]['tableId'] = $tableId;
            }
            //var_dump($data);
            foreach($data as $key => $val){

                $timeTable = $this->getTimeTableDetailService()->create($val);
            }
            $paramUpdate = array(
                'status' => 1
            );
            $summaryUpdate = $this->getTimeTableSummaryService()->edit($tableId,$paramUpdate);

            //$summary2 = $this->getTimeTableSummaryService()->edit($countSheet);

            if ($summaryUpdate) {
                return $this->createJsonResponse(array('code' => 0, 'id'=>$tableId,'msg' => '课表解析成功！'));
            } else {
                return $this->createJsonResponse(array('code' => 1, 'msg' => '课表解析失败！'));
            }
        } else {
            return $this->createJsonResponse(array('code' => 1, 'msg' => '请检查该作息表是否存在！'));
        }
    }


    /**
     * 导入excel，按规则解析，验证后，批量导入数据库
     * 1、根据前端传入的路径，寻找excel，通过PhpSpreadsheet读取excel档，返回 $sheetData
     * 2、验证表格中的教室，教师，课程，如果缺失，return false，如果正常，继续
     * 3、通过传入的作息表id，学期表id,获取对应的作息表和学期表信息
     * 4、通过相应的规则，将表格原始数据通过教室，周数，节次，解析成与服务器数据库相对应的格式
     * 5、将相关数据插入状态表（课表id，课表名称，创建时间，解析后数据条数）
     * 6、将解析后的数据与服务器中的import_lesson表比对
     *  1>如果有重复数据，跳过
     *  2>如果只有部分栏位相同，进行更新
     *  3>如果没有此条数据，跳过
     * 7、插入数据库过程中若出现错误，应记录错误栏位信息，return false,如正常，修改状态表状态，调取 嘉先 serviceImpl。
     */
    public function uploadAction(){
        //0、获取必需参数并判断 并上传文件
        $schoolId = $this->getAdminSchoolId();

        $file = $this->request->getSmfRequest()->files->get('timetable');
        $termId = $this->getParam($this->request, "termId");
        $timeTableName = $this->getParam($this->request, "name");

        //var_dump($timeTableName);
        //var_dump($termId);
        //var_dump($file);
        if (empty($file) || empty($termId) || empty($timeTableName)) {
            return $this->createJsonResponse(array("code" => -4, "msg" => "必输参数为空"));
        }
        $fileDir = 'timetable' . $this->getAdminSchoolId() . "_" . time();
        $filename = $fileDir . '.' . $file->getClientOriginalExtension();
        $directory = "{$this->config->parameters->get('bwxt.upload.public_directory', true)}/timetable/" . $fileDir;

        $file = $file->move($directory, $filename);
        if(!$file){
            return $this->createJsonResponse(array("code" => 1, "msg" => "文件上传失败"));
        }

        $fullPath = $directory."/".$filename;

        //1、获取教师，教师，课程，和$sheetData比对
        $spreadsheet = IOFactory::load($fullPath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        //2、验证表格中的教室，教师，班级，如果缺失，return false，如果正常，继续

        $classNeed = array(
          'location'
        );

        $classRoomList = $this->getClassroomService()->lists($classNeed, 1, 1000);//
        if(!$classRoomList){
            return $this->createJsonResponse();
        }
        foreach($classRoomList['data']['classrooms'] as $key => $val){
            $classrooms[$val['location']] = $val['location'];
        }

        //var_dump($classrooms);
        $teacherList = $this->getUserService()->getOnlyTeacherList();//user
        if(!$teacherList){
            return $this->createJsonResponse();
        }
        foreach($teacherList as $key => $val){
            $teachers[$val['studentNo']] = array(
                'id' => $val['id'],
                'studentNo' => $val['studentNo'],
                'showName' => $val['showName']
            );
        }

        //$greadList = $this->getCourseService()->ge();
        $conditions =array();
        $gradeList = $this->getGradeService()->searchGrades('*');
        if(!$gradeList){
           // return $this->createJsonResponse();
        }
        foreach($gradeList as $key => $val){
            $grades[$val['title']] = $val['title'];
        }

        //将$sheetData数组去重，用更少的数据做对比
        foreach($sheetData as $key => $val){
            if($val['A']=='序号')
                continue;
            if(empty($val['L']) || empty($val['F']) || empty($val['F']))
                continue;
            $classroomCompare[$val['L']] = $val['L'];
            $teacherCompare[$val['F']] = $val['F'];
            $gradePreCompare[$val['G']] = $val['G'];
        }

        foreach ($gradePreCompare as $key => $val){
            $tempList = explode(',',$val);
            $gradeCompare[$tempList[0]] = $tempList[0];
            $gradeCompare[$tempList[1]] = $tempList[1];
        }

        var_dump($classroomCompare);
        var_dump($teacherCompare);
        var_dump($gradeCompare);
        //return;

        if(empty($classrooms) || empty($classroomCompare)){
            return;
        }
        if(empty($teachers) || empty($teacherCompare)){
            return;
        }
        if(empty($grades) || empty($gradeCompare)){
           // return;
        }

        foreach($classroomCompare as $key => $val){
            if(!in_array($val, $classrooms)){
                //return $this->createJsonResponse(array("code" => -4, "缺少教室，请先创建教室！"));
            }
        }

        foreach($teacherCompare as $key => $val){
            if(!in_array($val, $teachers)){
                //return $this->createJsonResponse(array("code" => -4, "老师不存在，请先录入老师信息！"));
            }
        }
        foreach($gradeCompare as $key => $val){
            if(!in_array($val, $grades)){
                //return $this->createJsonResponse(array("code" => -4, "缺少班级，请先录入班级信息！"));
            }
        }

        //* 3、通过传入的作息表id，学期表id,获取对应的作息表和学期表信息

        $termDetail = $this->getTermService()->getTermDetail($termId);
        $startTermDate = date('Y-m-d',$termDetail[0]['termStartDate']);
        $endTermDate = date('Y-m-d',$termDetail[0]['termEndDate']);
        var_dump($startTermDate);
        var_dump($endTermDate);
        $restId = $termDetail[0]['restID'];
        $restId = 1;
        $restDetail = $this->getRestService()->getRestDetail($restId);
       // var_dump($restDetail);
     //   var_dump($restDetail[0]['time']);
        $timestep1 = $restDetail[0]['time'];//获取{ } 括号中内容
        $strPattern = "/(?<={)[^}]+/";
        $arrMatches = [];
        preg_match_all($strPattern, $timestep1, $arrMatches);
        //var_dump($arrMatches);

        foreach ($arrMatches[0] as $key => $val){
            $timestep2[] = explode(',',$val);
        }
        foreach ($timestep2 as $key => $val){
            $restArray[$key]['start_time'] =  trim(str_replace("start_time:","",$val[0]));
            $restArray[$key]['end_time']  =  trim(str_replace("end_time:","",$val[1]));
        }

        if(count($restArray)<1)//判断格式是否正确或者有具体数值
            return $this->createJsonResponse(array('code'=>0,'msg'=>'选择的作息表有误，请重新确认！'));
        //* 4、通过相应的规则，将表格原始数据通过教室，周数，节次，解析成与服务器数据库相对应的格式s
        $tempArrayOne = array();
        $tempArrayTwo = array();

//-------------- 班级分段 周数分段
        $countI = 0;
        $j = 0;
        $tempWeek = 0;
        var_dump($sheetData[1]);
        foreach($sheetData as $key => $val){
            if($val['A']=='序号')
                continue;
            $class_list = explode(',',$val['G']);
            foreach($class_list as $key1 => $class){
                $process_before_week = $val['H'];
                $process_step1_week  = strstr($process_before_week, '周', TRUE);
                if(strpos($process_step1_week,',')!==false){
                    $process_step2_week = explode(",", $process_step1_week);
                    foreach($process_step2_week as $key2 => $weeks){
                        if(strpos($weeks,'-')!==false){//1-2,4-5
                            $ba = explode("-",$weeks);
                            $before = $ba[0];
                            $after = $ba[1];
                            $diff = $after - $before;
                            for($i = 0; $i <= $diff; $i++){
                                $tempWeek = $before+$i;
                                $j++;
                                $tempArrayOne[] = array(
                                    'class'=>$class,
                                    'week'=>$tempWeek,
                                    'key'=>$key
                                );
                            }
                        }else{//1,5
                            $tempWeek = $weeks;
                            $tempArrayOne[] = array(
                                'class'=>$class,
                                'week'=>$tempWeek,
                                'key'=>$key
                            );
                        }
                    }
                }else{
                    if(strpos($process_step1_week,'-')!==false){//2-4
                        $ba = explode("-",$process_step1_week);
                        $before = $ba[0];
                        $after = $ba[1];
                        $diff = $after - $before;
                        for($i = 0; $i <= $diff; $i++){
                            $tempWeek = $before+$i;
                            $countI++;

                            $tempArrayOne[] = array(
                                'class'=>$class,
                                'week'=>$tempWeek,
                                'key'=>$key
                            );
                        }
                    }else{//4
                        $tempArrayOne[] = array(
                            'class'=>$class,
                            'week'=>$process_step1_week,
                            'key'=>$key
                        );

                    }
                }
            }
        }
        //节次分段
        function getNowDate($startTermDate,$week,$weekday){
            $mweek = $week - 1;
            $mweekday = $weekday - 1;
            $workDate = date("Y-m-d",strtotime('+'.$mweek * 7 + $mweekday.' days',strtotime($startTermDate)));
            return $workDate;
        }

        foreach($tempArrayOne as $key => $val){
            $startSection = $sheetData[$val['key']]['J'];
            $endSection = $startSection + $sheetData[$val['key']]['K'] - 1;
            $tmpI = 0;
            for($tmpI = $startSection;$tmpI<=$endSection;$tmpI++){
                //$timeSection = $restArray[$tmpI];
                //$sectionTimes = explode('-',$timeSection);
                $sTime = $restArray[$tmpI]['start_time'];
                $eTime = $restArray[$tmpI]['ent_time'];
                $nowDate = getNowDate($startTermDate,$sheetData[$val['key']]['I'],$sheetData[$val['key']]['J']);

                $startTime = date("$nowDate $sTime");
                $endTime = date("$nowDate $eTime");

                $tempArrayTwo[] = array(
                    'class'=>$val['class'],
                    'week'=>$val['week'],
                    'key'=>$val['key'],
                    'date'=>$nowDate,
                    'node'=>$tmpI,
                    'startTime'=>$startTime,
                    'endTime'=>$endTime,
                );
            }
        }
        $tempArrayOne = null;//清空临时数组
        $countSheet = 0;

        $cSheet = count($tempArrayTwo)-1;
        $sTime=microtime(true);//获取程序开始执行的时间
        //echo $sTime."<BR>";
        $tableId = '2';
        foreach($tempArrayTwo as $key => $val){
            if($sheetData[$val['key']]=='序号')
                continue;
            $roomId = 'testRoom';
            $teacherId = '1';
            $teacherName = 'testTName';
            $termName = '测试学期';
            $startTime = strtotime($val['startTime']);
            $endTime = strtotime($val['endTime']);
            $date = strtotime($val['date']);
            strtotime($val['startTime']);
            $data[$countSheet] =   array (
                'schoolId' => $schoolId,
                'restId' =>1,
                'termId' => $termId,
                'termName' => $termName,
                'startTime'=>$startTime,
                'endTime'=>$endTime,
                'date'=>$date,
                'roomId'=>$roomId,
                'classRoomName'=>$sheetData[$val['key']]['L'],
                'node'=>$val['node'],
                'mobileNo' => $sheetData[$val['key']]['E'],
                'speakerId' => $teacherId,
                'teacherName' => $teacherName,
                'courseName' => $sheetData[$val['key']]['D'],
                'className' => $val['class'],
                'courseLessonName' => $sheetData[$val['key']]['D'],
                'tableId' => $tableId,

            );

            if($countSheet>=10)
                break;
            $countSheet++;
        }

        /* 5、将相关数据插入状态表（课表id，课表名称，创建时间，解析后数据条数）
        *  6、将解析后的数据与服务器中的import_lesson表比对
        *  1>如果有重复数据，跳过
        *  2>如果只有部分栏位相同，进行更新
        *  3>如果没有此条数据，跳过
        */
        //$data $sqldata
        $sqlData = $this->getTimeTableDetailService()->listByTermStartToEnd(strtotime($startTermDate),strtotime($endTermDate));
        //var_dump($sqlData);
        //获取差值


        $eTime=microtime(true);//获取程序开始执行的时间
        //echo "EndTime".$eTime."<BR>";s
        //var_dump($data);

        /* ---获取 3，4 的差集 key
        1、1&2做对比，获取第三个data
        2、2&3对比 获取 4 insert
        1，3&4 key 获取需update的key （1，3） array_diff_key */




        return;

//        return $data;
//        return;

        $creatTime = strtotime(date('Y-m-d H:i:s'));
        $countSum = count($data);
        $param = array(
            'tableName'=>$timeTableName,
            'createTime'=>$creatTime,
            'schoolId'=>$schoolId,
            'countSum'=>$countSum,
            'requestSum'=>0
        );

        $timeDetail = "123";
        if ($timeDetail) {
            $summaryInsert = $this->getTimeTableSummaryService()->createSummary($param);
            foreach($data as $key => $val){
                $timeTable = $this->getTimeTableDetailService()->create($val);
            }
//            $this->logger->info('updateTimeTableSummaryService-start', array("测试课表导入状态表插入", $summaryInsert));
            $paramUpdate = array(
                'status' => 1
            );
            $tableId = $summaryInsert['id'];
            $summaryUpdate = $this->getTimeTableSummaryService()->edit($tableId,$paramUpdate);
//            $this->logger->info('updateTimeTableSummaryService-end', array("测试课表导入状态表插入", $summaryUpdate));
            if ($summaryUpdate) {
                $creatTaskTime = strtotime(date('Y-m-d H:i:s'));
                $cbUrl = $this->url->generate("man_couse_import_request", array("tableId" => $tableId),true);
                $pushData = array(
                    "taskType" => "httpCall",
                    "_method" => "GET",
                    "startupTime" => (int) $creatTaskTime + 10,
                    "_token" => "livetimer-{$schoolId}-{$tableId}",
                    "_expiration" => "0",
                    "schoolId" => $schoolId,
                    "eTag" => "{$schoolId}-{$tableId}",
                    "mtype" => "live",
                    "dnUrl" => "",
                    "cbUrl" => $cbUrl,
                );

                return;
                $client = $this->createCloudClient("task");
                $result = $client->cdnPush($pushData);

                if($result){
                    return $this->createJsonResponse(array('code' => 0, 'id'=>$tableId, 'msg' => '课表解析成功！正在进行导入'));
                }else{
                    return $this->createJsonResponse(array('code' => 0, 'msg' => '课表解析成功，导入失败！'));
                }

            } else {
                return $this->createJsonResponse(array('code' => 1, 'msg' => '课表解析失败！'));
            }
        } else {
            return $this->createJsonResponse(array('code' => 1, 'msg' => '请检查该作息表是否存在！'));
        }

    }


    public function deleteAction()
    {

    }

    public function updateAction()
    {

    }

    public function listAction()
    {

    }
    //------------------ Service


    private function getTermService()
    {
        return $this->createService("Course.Term.TermService");
    }

    private function getRestService()
    {
        return $this->createService("Course.Resule.RestService");
    }

    private function getUserService()
    {
        return $this->createService("User.User.UserService");
    }

    private function getClassroomService() {
        return $this->createService("Resource.Dcc.ClassroomService");
    }

    private function getCourseService(){
        return $this->createService("Course.Course.CourseService");
    }

    private function getGradeService() {
        return $this->createService('Course.Course.GradeService');
    }

    private function getTimeTableDetailService(){
        return $this->createService("Course.TimeTable.TimeTableDetailService");
    }

    private function getTimeTableSummaryService(){
        return $this->createService("Course.TimeTable.TimeTableSummaryService");
    }




}
