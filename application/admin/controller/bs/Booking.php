<?php
namespace app\admin\controller\bs;
//引入base控制器
use app\admin\controller\Base;
use think\helper\Time;
use think\Paginator;
use lib\Page;
use think\DB;
/**
 * 预约信息表控制器
 * 
 */
class Booking extends  Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
      


      $this->zx_dateStart=input('zx_dateStart');
      $this->zx_dateEnd=input('zx_dateEnd');

      $this->yy_dateStart=input('yy_dateStart');
      $this->yy_dateEnd=input('yy_dateEnd');

      $this->dz_dateStart=input('dz_dateStart');
      $this->dz_dateEnd=input('dz_dateEnd');



        $ft=input('ft');//时间类型
        if($ft){
            //今天
          if($ft=='today')
          {
            list($start, $end) = Time::today();
          }
          else if($ft=='yday')
          {
                //昨天
            list($start, $end) = Time::yesterday();
          }
          else if($ft=='week')
          {
                //本周
            list($start, $end) = Time::week();
          }

          else if($ft=='lweek')
          {
                //上周
            list($start, $end) = Time::lastWeek();
          }
          else if($ft=='month')
          {
                //本月
            list($start, $end) = Time::month();
          }
          else if($ft=='lmonth')
          {
            //上月
            list($start, $end) = Time::lastMonth();
          }

          $start=date('Y-m-d',$start);
          $end=date('Y-m-d',$end);
          $ty=input('sltDateType');//到院 ，登记
            if($ty=="2"){
              $this->yy_dateStart=$start;
              $this->yy_dateEnd=$end;
            }else if($ty=='1'){
              //咨询
              $this->zx_dateStart=$start;
              $this->zx_dateEnd=$end;
            }else{ 
              //到院
              $this->dz_dateStart=$start;
              $this->dz_dateEnd=$end;
            }
            $this->sltDateType=$ty;  
          }else{
           $this->sltDateType=0;
         }
      

         return view();
       }
       public function daoru()
    {
  
      $data = Db::name('bs_aaa')->select();


      $this->assign('data',$data);
      return $this->fetch('daoru');
    }
    public function dao(){
   
        // memry_limit 

        vendor("PHPExcel.PHPExcel"); //方法一  
        $objPHPExcel = new \PHPExcel();  
  
        //获取表单上传文件  
        $file = request()->file('excel');  
        if (empty($file)) {
          $this->success('请上传文件', 'bs.booking/daoru');
        }
        $info = $file->validate(['ext'=>'xlsx,xls,csv'])->move(ROOT_PATH . 'public' . DS . 'excel');

      // var_dump($info);die; 
        if($info){  
            $exclePath = $info->getSaveName();  //获取文件名  
   
            $file_name = ROOT_PATH . 'public' . DS . 'excel' . DS . $exclePath;   //上传文件的地址  
// var_dump($file);die;
            // $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
             $suffix = $info->getExtension();
             // var_dump($suffix);die;
          if($suffix =='xlsx' ){
              $objReader = \PHPExcel_IOFactory::createReader('Excel2007'); 
          }else{
              $objReader = \PHPExcel_IOFactory::createReader('Excel5'); 
            }

            $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8  
            
             $sheet = $obj_PHPExcel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        //获取总列数
        $col_num = $sheet->getHighestColumn();
         
// var_dump($row_num);die;

            // echo "<pre>";  
            // $excel_array=$obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式  
// var_dump($excel_array);die;
            // array_shift($excel_array);  //删除第一个数组(标题);  
          $data = []; //数组形式获取表格数据
        for ($i = 2; $i <= $row_num; $i ++) {
            $data[$i]['name']  = $sheet->getCell("A".$i)->getValue();
            $data[$i]['names']  = $sheet->getCell("B".$i)->getValue();
            $data[$i]['a']  = $sheet->getCell("C".$i)->getValue();
            $data[$i]['b']  = $sheet->getCell("D".$i)->getValue();
            $data[$i]['c']  = $sheet->getCell("E".$i)->getValue();
            $data[$i]['d']  = $sheet->getCell("F".$i)->getValue();
            $data[$i]['e']  = $sheet->getCell("G".$i)->getValue();
            $data[$i]['f']  = $sheet->getCell("H".$i)->getValue();
            $data[$i]['g']  = $sheet->getCell("I".$i)->getValue();
            $data[$i]['h']  = $sheet->getCell("J".$i)->getValue();
            $data[$i]['i']  = $sheet->getCell("K".$i)->getValue();
            $data[$i]['y']  = $sheet->getCell("L".$i)->getValue();
            $data[$i]['k']  = $sheet->getCell("M".$i)->getValue();
            $data[$i]['l']  = $sheet->getCell("N".$i)->getValue();
            $data[$i]['m']  = $sheet->getCell("O".$i)->getValue();
            $data[$i]['n']  = $sheet->getCell("P".$i)->getValue();
            $data[$i]['w']  = $sheet->getCell("Q".$i)->getValue();
            // $data[$i]['last_code']  = substr($sheet->getCell("A".$i)->getValue(),-6);
            // $time = date('Y-m-d H:i',\PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell("B".$i)->getValue()));//将excel时间改成可读时间
            // $data[$i]['time'] = strtotime($time);
            //将数据保存到数据库
        }
        $res = Db::name('bs_aaa')->insertAll($data);
        //    //$i=  
            $error=$i-$res;  
            echo "总{$i}条，成功{$res}条，失败{$error}条。";

            $this->success('', 'bs.booking/daoru');
        //    // Db::name('t_station')->insertAll($city); //批量插入数据  
        // }else{  
        //     // 上传失败获取错误信息  
        //     echo $file->getError();  
        // }  
  
    }else{

      $this->error('文件格式有误');
    }




    }
       public function form()
       {



        $op=input('get.op','0');
        if($op=='0'){
          /*$this->pageInfo=$this->model->search(input('get.'));
          return view();*/
       /*
         参数1  对多显示多少条数据
         参数2  每页显示多少条数据
       */

          $data_page = new Page(2000,15);
         
   
          $limit_start = ($data_page->page)*15-15;
          $limit_end = 15;

         $data=$this->model->search(input('get.'),$limit_start,$limit_end);
    
      
  $info=session('AdminInfo');

      $res = Db::name('common_admin_group')->where('admin_id',$info['id'])->field('group_id')->find();

      if ($res['group_id']==17) {
            $ww = $data['data'];
$bbs =1;
          $this->assign([
          'pageInfo'=>$ww,
          'pages'=>$data_page->render,
          'page'=>$data['page'],
          'user'=>$bbs,
        ]);

        return view();
      }else{

$bbs =2;
        $this->assign([
          'pageInfo'=>$data['data'],
          'pages'=>$data_page->render,
          'page'=>$data['page'],
          'user'=>$bbs,
      
        ]);
        return view('form');
      }

        //          $this->assign([
        //   'pageInfo'=>$data['data'],
        //   // 'ar'=>$ar,
        //   'pages'=>$data_page->render,
        //   'page'=>$data['page'],
        // ]);
        // // var_dump($data);die;
        //    return view();

        }else{
          //导出

         
          parent::checkAuth('bs.booking/export');
          $lis=$this->model->search(input('get.'),0,3500);
          
          
        
          $this->_export($lis['data'],'预约数据');
          
        }
      }
        /**
         * @return [type] [description]
         */
        public function self_booking()
        {
          $this->pageInfo=$this->model->selfBookingSearch(input('get.'));
          return view();
        }
        
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {

    
      return view();
    }
    /**
     * 更新提交方法  
     * @param  int  $id
     * @return \think\Response
     */
  public function update($id)
  {
    if(IS_POST){
      $this->model->data($this->request->post());
      $this->model->id=$id;
      $zjtime=input('post.jz_time');
      $this->model->jz_time=strtotime($zjtime);
      $result=$this->model->updateResult();
      return json($result);
    }
  }

    /**
     * 显示编辑表单页.
     * 
     * @return \think\Response
     */
    public function edit($id)
    {
      $this->info=$this->model->simple($id);
      return view();
    }
    public function sms($id,$tel)
    {
      $this->tel=$tel;
      $this->id=$tel;
      return view();
    }
    public function visit()
    {
      $data =  $this->request->post();
      
      //var_dump($data);die;
      $time = $data['next_time'];

      

      $catime = strtotime($time);

      // if (empty($catime)) {
      //  return $this->error('请选择时间');
      // }
      $arr['next_time']=$catime;
      $arr['next_times']=time();
      $arr['userid']=ADMIN_ID;

      // var_dump($arr);die;

      Db::name('bs_booking')->where('id',$data['booking_id'])->update($arr);

      if(IS_POST){
        $model=model('bs.visit');
        $model->data($this->request->post());
        $model->user_id=ADMIN_ID;
        $result= $model->addResult();
        return json($result);
      }
    }
    /**
     * 检测手机号是否存在
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function mcheck()
    {
      if(IS_POST)
      {
        $maa=input('mobile');
        $map['mobile']=$maa;
        $map['is_del']=1;
        $model=model('bs.customer')->where($map)->select();
        // file_put_contents('1.text',$model);
        if(!$model=='')
        {
          return json($model);
        }
      }
    }
    /**
     * 到诊
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function arriving($id)
    {
      if(IS_POST){
        $r=$this->model->arriving($id);
        return json($r);
      }
    }
    public function receive($id)
    {
      if(IS_POST){
        $r=$this->model->receive($id);
        return json($r);
      }
    }
   private function _export($lis,$title){
      \think\Loader::import('ivier.PHPExcel');
      $resultPHPExcel =new \PHPExcel();
        //设置参数
        //设值
      $sheet=$resultPHPExcel->getActiveSheet();
    
        //标题
      $sheet->setTitle($title);
      //abcdefghijklmnopqrstuvwxyz
      $sheet->setCellValue('A1', '预约号/(老预约号)');
      $sheet->setCellValue('B1', '姓名');
      $sheet->setCellValue('C1', '咨询人性别');
      $sheet->setCellValue('D1', '使用时间');
      $sheet->setCellValue('E1', '电话');
      $sheet->setCellValue('F1', '品牌');
      $sheet->setCellValue('G1', '故障类别');
      // $sheet->setCellValue('H1', '区域');
      $sheet->setCellValue('H1', '所属省份');
      $sheet->setCellValue('I1', '所在市区');
      $sheet->setCellValue('J1', '来源媒体');
      $sheet->setCellValue('K1', '预约方式');
      $sheet->setCellValue('L1', '客服');
      $sheet->setCellValue('M1', '登记时间');
      $sheet->setCellValue('N1', '预约到店时间');
      $sheet->setCellValue('O1', '到店时间'); 
      $sheet->setCellValue('P1', '状态'); 
      $sheet->setCellValue('Q1', '会员');
      $sheet->setCellValue('R1', '门店');

      $i = 2;

      foreach($lis as $item){

        $sheet->setCellValue('A' . $i, $item['id'].'('.$item['old_id'].')');
        $sheet->setCellValue('B' . $i, $item['name'].'('.$item['zname'].')');
        if($item['gender']=='1'){
          $sheet->setCellValue('C' . $i, '男');
        }else if($item['gender']=='2'){
          $sheet->setCellValue('C' . $i, '女');
        }else{
          $sheet->setCellValue('C' . $i, '保密');
        }
        $sheet->setCellValue('D' . $i, def($item['age'],'未知'));
        $sheet->setCellValue('E' . $i, def($item['mobile'],'未知'));

        $sheet->setCellValue('F' . $i, def($item['department_name'],'无'));
        $sheet->setCellValue('G' . $i, def($item['disease_name'],'无'));
        // $sheet->setCellValue('H' . $i, def($item['yy_doctor_name'],'无'));
        $sheet->setCellValue('H' . $i, def($item['province_name'],'无'));
        $sheet->setCellValue('I' . $i, def($item['city_name'],'无'));
        $sheet->setCellValue('J' . $i, $item['way_name']);
        $sheet->setCellValue('K' . $i, $item['way_name'].def($item['way_namea'],'无'));
        $sheet->setCellValue('L' . $i,def($item['custom_service_name'],'无'));
        $sheet->setCellValue('M' . $i, date('Y-m-d H:i',$item['create_time']));
        $sheet->setCellValue('N' . $i, $item['yy_time']);
        if($item['status']==3){
          $sheet->setCellValue('O' . $i, date('Y-m-d H:i',$item['jz_time']));
          $sheet->setCellValue('P' . $i, '已到');

        }else if($item['status']==2){
          $sheet->setCellValue('P' . $i, '预约');
          $sheet->setCellValue('O' . $i, '未到');
        }else{
          $sheet->setCellValue('P' . $i, '登记');
          $sheet->setCellValue('O' . $i, '未到');
        }
        $sheet->setCellValue('Q' . $i, def('','无').'('.def('','无').')');
        if($item['shop_id']==1){
          $sheet->setCellValue('R' . $i, '中环店');
        }else if($item['shop_id']==2){
          $sheet->setCellValue('R' . $i, '东方店');
        }else if($item['shop_id']==3){
          $sheet->setCellValue('R' . $i, '新世界店');
        }
        
        $i++;
      }

      //设置导出文件名
      $outFileName =$title.".xls";
      
      \think\Loader::import('ivier.PHPExcel.PHPExcel_Writer_Excel5');
      $xlsWriter = new \PHPExcel_Writer_Excel5($resultPHPExcel);
      ob_end_clean();
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
      header('Content-Disposition:inline;filename="'.$outFileName.'"');
      header("Content-Transfer-Encoding: binary");
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Pragma: no-cache");
      $xlsWriter->save('php://output');
    }
  }
