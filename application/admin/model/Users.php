<?php

namespace app\admin\model;

use think\Model;

class Users extends Model
{
    // 表名
    protected $name = 'user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'type_text',
        'status_text',
        'reg_type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2'),'3' => __('Type 3')];
    }     

    public function getStatusList()
    {
        return ['1' => __('Status 1'),'2' => __('Status 2')];
    }     

    public function getRegTypeList()
    {
        return ['1' => __('Reg_type 1'),'2' => __('Reg_type 2')];
    }     


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getRegTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['reg_type']) ? $data['reg_type'] : '');
        $list = $this->getRegTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
