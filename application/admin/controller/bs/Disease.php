<?php
namespace app\admin\controller\bs;
//引入base控制器
use app\admin\controller\Base;
use think\Db;
/**
 * 咨询方式控制器
 * 
 */
class Disease extends  Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->lis=$this->model->allListTreeFormat(HID);
        return view();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $this->nodes=$this->model->allListTreeFormat(HID);
        return view();
    }
    /**
     * 批量创建
     * @return 
     */
    public function batch_create()
    {
        if($this->request->isPost()){
            $this->model->data($this->request->post());
            $result=$this->model->batchAddResult();
            return json($result);
        }
        $this->nodes=$this->model->allListTreeFormat(HID);
        return view();
    }
     public function add()
    {
        // $this->nodes=$this->model->allListTreeFormat(HID);
        return view();
    }

    public function adds()
    {
        $data = $this->request->post();
        // var_dump($data);die;
        if (empty($data['name'])) {
           return $this->error('数据为空');
        }
        // var_dump($data);die;
        $ids = Db::name('bs_disease')->where('hospital_id',17)->where('pid',0)->field('id')->select();

        $count = count($ids);
        for ($i=0; $i < $count; $i++) { 
            $datalist['pid'] = $ids[$i]['id'];
            $datalist['name'] = $data['name'];
            $datalist['hospital_id'] = $data['hospital_id'];
            $datalist['create_time'] = time();
            $datalist['status'] = 1;
            $datalist['weight'] = 99;
           $res =  Db::name('bs_disease')->insert($datalist);
        }
        
        if ($res) {
            return $this->fetch('add');
        }else{
            echo "出现错误,请检查";
        }
        
    }
    /**
     * 显示编辑表单页.
     * 
     * @return \think\Response
     */
    public function edit($id)
    {
        $this->nodes=$this->model->allListTreeFormatNotCurrent(HID,$id);
        $this->info=$this->model->get($id);
        return view();
    }
    /**
     * 禁用
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function disable($id)
    {
        if(IS_POST){
            $r=$this->model->disable($id);
            return json($r);
        }
    }
    /**
     * 恢复
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function recovery($id)
    {
        if(IS_POST){
            $r=$this->model->recovery($id);
            return json($r);
        }
    }
}
