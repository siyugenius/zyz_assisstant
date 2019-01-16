<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use app\admin\model\Admin;
use app\admin\model\Auth;
use app\admin\model\Hotel;
use think\Model;

/**
 * 智游助手用户管理
 *
 * @icon fa fa-user
 */
class Users extends Backend
{
    
    /**
     * Users模型对象
     * @var \app\admin\model\Users
     */
    protected $model = null;
    protected $AdminModel = null;
    protected $HotelModel = null;
    protected $searchFields = 'name';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Users;
        $this->AdminModel = new Admin();
        $this->HotelModel = new Hotel();

        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("regTypeList", $this->model->getRegTypeList());
    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax()){
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $hotel_id = $this->AdminModel->where('id',$this->auth->id)->value('hotel_id');
            if (!empty($hotel_id)) {
                $hotel_no = $this->HotelModel->where('id',$hotel_id)->value('hotel_no');
                $row = $this->model
                    ->where($where)
                    ->where('hotel_id','=',$hotel_id)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
                $total = $this->model
                    ->where($where)
                    ->where('hotel_id','=',$hotel_id)
                    ->order($sort, $order)
                    ->count();
                array_walk($row,function($v) use($hotel_no) {
                    $v->hotel_no = $hotel_no;
                });
            } else {
                $row = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

                $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

                array_walk($row,function($v){
                    $hotel_no = $this->HotelModel->where('id',$v['hotel_id'])->value('hotel_no');
                    $v->hotel_no = $hotel_no;
                });
            }
            // $total = count($row);
            $result = array("total" => $total, "rows" => $row);
            return json($result);
        }
        return $this->view->fetch();
    }


    // 查看详情
    public function detail($ids = NULL)
    {
        $row = $this->model->where('id',$ids)->find();
        $row['hotel_no'] = $this->HotelModel->where('id',$row['hotel_id'])->value('hotel_no');
        $row['id_number'] = substr_replace($row['id_number'],'*************',2,14);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }



}
