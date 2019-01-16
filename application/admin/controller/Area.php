<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 地区管理
 *
 * @icon fa fa-circle-o
 */
class Area extends Backend
{
    
    /**
     * Area模型对象
     * @var \app\admin\model\Area
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Area;

    }

    public function address($id)
    {
        $address = Area::get($id);
        return $address['name'];
    }

    public function getProvince()
    {
        $total = $this->model->where('level','=',1)->count();
        $list = $this->model->where('level','=',1)->select();
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

}
