<?php

namespace app\admin\controller\hotel;

use app\admin\model\AuthGroupAccess;
use fast\Tree;
use app\admin\model\AuthGroup;
use app\common\controller\Backend;
use app\admin\model\Admin;
use app\common\model\Area;
use fast\Random;
use app\admin\model\HotelGroup;
use think\Model;

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
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];
    protected $searchFields = 'name';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Hotel;
        $groupList = build_select('row[group_id]', HotelGroup::column('id,grp_name'), ['class' => 'form-control selectpicker']);
        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);
        $adminGroupList = collection(AuthGroup::where('id', 'in', $this->childrenGroupIds)->select())->toArray();
        Tree::instance()->init($adminGroupList);
        $groupdata = [];
        if ($this->auth->isSuperAdmin())
        {
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
            foreach ($result as $k => $v)
            {
                $groupdata[$v['id']] = $v['name'];
            }
        }
        else
        {
            $result = [];
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n)
            {
                $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']));
                $temp = [];
                foreach ($childlist as $k => $v)
                {
                    $temp[$v['id']] = $v['name'];
                }
                $result[__($n['name'])] = $temp;
            }
            $groupdata = $result;
        }

        $this->view->assign('groupdata',$groupdata);
        $this->view->assign("groupList", $groupList);
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    /**
     *
     * 地址查询
     * @param $id
     * @return mixed
     * @throws \think\exception\DbException
     *
     */
    public function address($id)
    {
        $address = Area::get($id);
        return $address['name'];
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
            foreach ($list as $k=>$v){
                $province = $this->address($v['province_id']);
                $city = $this->address($v['city_id']);
                $area = $this->address($v['area_id']);
                $list[$k]['address'] = $province.$city.$area.$v['addr'];
               // $list['group'] = $groupdata;
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $last_data = \app\admin\model\Hotel::max('hotel_no');
                $hotel['hotel_no'] = $last_data + 1;
                $hotel['name'] = $params['name'];
                $hotel['group_id'] = $params['group_id'];
                $hotel['province_id'] = $params['province_id'];
                $hotel['city_id'] = $params['city_id'];
                $hotel['area_id'] = $params['area_id'];
                $hotel['addr'] = $params['addr'];
                $hotel['longitude'] = $params['longitude'];
                $hotel['latitude'] = $params['latitude'];
                $hotel['administrator'] = $params['administrator'];
                $hotel['tel'] = $params['tel'];
                $hotel['other_tel'] = $params['other_tel'];
                $hotel['email'] = $params['email'];

                // 这里需要针对name做唯一验证
//                $hotelValidate = \think\Loader::validate('Hotel');
//                $hotelValidate->rule([
//                    'name' => 'require|max:50|unique:hotel,name,' . $row->id,
//                ]);
                $result = $this->model->validate('Hotel.add')->save($hotel);
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                $admin['username'] = $params['nickname'];
                $admin['nickname'] = $params['nickname'];
                $admin['salt'] = Random::alnum();
                $admin['password'] = md5(md5($params['password']) . $admin['salt']);
                $admin['avatar'] = '/assets/img/avatar.png';
                $admin['email'] = $params['email'];
                $admin['hotel_id'] = $this->model->id;
                $Admin_model = new Admin();

                // 这里需要针对username和email做唯一验证
                $adminValidate = \think\Loader::validate('Admin');
                $adminValidate->rule([
                    'username' => 'require|max:50|unique:admin,username,' . $row->id,
                    // 'email'    => 'require|email|unique:admin,email,' . $row->id
                ]);
                $admin_result = $Admin_model->validate('Admin.add')->save($admin);
                if ($admin_result === false)
                {
                    $this->error($Admin_model->getError());
                }
                $group = $this->request->post("group/a");

                //过滤不允许的组别,避免越权
                $group = array_intersect($this->childrenGroupIds, $group);
                $dataset = [];
                foreach ($group as $value)
                {
                    $dataset[] = ['uid' => $Admin_model->id, 'group_id' => $value];
                }
                model('AuthGroupAccess')->saveAll($dataset);
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }


    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        $hoteladmin = Admin::field('nickname,id,status')->where('hotel_id',$ids)->find();
        $group = AuthGroupAccess::where('uid',$hoteladmin['id'])->value('group_id');

        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");            // 酒店信息
            $hotel_admin  = $this->request->post("admin/a");    // 酒店后台账户信息
            $group = $this->request->post("group/a");           // 后台账户权限

            if ($params && $hotel_admin && $group) {
                try {
                    // 是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    // 这里需要针对name做唯一验证
//                    $hotelValidate = \think\Loader::validate('Hotel');
//                    $hotelValidate->rule([
//                        'name' => 'require|max:50|unique:hotel,name,' . $row->id,
//                    ]);
//                    $result = $this->model->validate('Hotel.add')->save($hotel);
                    $result = $row->allowField(true)->save($params);
                    $admin_result = Model('app\admin\model\Admin')->where('id',$hotel_admin['id'])->update(['status'=>$hotel_admin['status']]);

                    if ($result !== false && $admin_result !== false)
                    {
                        // 先移除所有权限
                        model('AuthGroupAccess')->where('uid', $hotel_admin['id'])->delete();
                        // 过滤不允许的组别,避免越权
                        $group = array_intersect($this->childrenGroupIds, $group);
                        $dataset = [];
                        foreach ($group as $value)
                        {
                            $dataset[] = ['uid' => $hotel_admin['id'], 'group_id' => $value];
                        }
                        model('AuthGroupAccess')->saveAll($dataset);
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
        $groupList = build_select('row[group_id]', HotelGroup::column('id,grp_name'), $row['group_id'], ['class' => 'form-control selectpicker']);
        $admingrouplist = $this->auth->getGroups($group);
        $groupids = [];
        foreach ($admingrouplist as $k => $v)
        {
            $groupids[] = $v['id'];
        }

        $this->view->assign("groupList", $groupList);   // 酒店集团
        $this->view->assign("groupids", $groupids);     // 后台用户权限组
        $this->view->assign('admin', $hoteladmin);      // 后台用户信息
        $this->view->assign("row", $row);               // 酒店信息
        return $this->view->fetch();
    }


    // 查看酒店详情
    public function detail($ids = NULL)
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
        // 地址
        $province = $this->address($row['province_id']);
        $city = $this->address($row['city_id']);
        $area = $this->address($row['area_id']);
        $row['address'] = $province.$city.$area.$row['addr'];
        $group= HotelGroup::field('grp_name')->where('id',$row['group_id'])->find();
        // 集团名称
        $row['group'] = $group['grp_name'];
        // 后台账户信息
        $admin = Admin::field('nickname,id')->where('hotel_id',$row['id'])->find();
        $row['nickname'] = $admin['nickname'];
        $admingroupid = AuthGroupAccess::where('uid',$admin['id'])->find();
        $admingroup = AuthGroup::field('name')->where('id',$admingroupid['group_id'])->find();
        $row['admingroup'] = $admingroup['name'];

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

}
