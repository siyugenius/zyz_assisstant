<?php

namespace app\admin\controller\hotel;

use app\common\controller\Backend;

/**
 * 集团管理
 *
 * @icon fa fa-circle-o
 */
class Group extends Backend
{
    
    /**
     * HotelGroup模型对象
     * @var \app\admin\model\HotelGroup
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\HotelGroup;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function selectpage()
    {
        return parent::selectpage(); // TODO: Change the autogenerated stub
    }


}