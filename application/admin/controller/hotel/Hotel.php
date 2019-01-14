<?php

namespace app\admin\controller\hotel;

use app\admin\model\HotelGroup;
use app\common\controller\Backend;

/**
 * 酒店管理
 *
 * @icon fa fa-hotel
 */
class Hotel extends Backend
{
    
    /**
     * Hotel模型对象
     * @var \app\admin\model\Hotel
     */
    protected $model = null;
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Hotel;
        $groupList = build_select('row[group_id]', \app\admin\model\HotelGroup::column('id,grp_name'), ['class' => 'form-control selectpicker']);
        $this->view->assign("groupList", $groupList);
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with('group')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with('group')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    public function edit($ids = NULL)
    {

        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $groupList = build_select('row[group_id]', \app\admin\model\HotelGroup::column('id,grp_name'), $row['group_id'], ['class' => 'form-control selectpicker']);
        $this->view->assign("groupList", $groupList);
        $this->view->assign("row", $row);
        return $this->view->fetch();

    }

}
