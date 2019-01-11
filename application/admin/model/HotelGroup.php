<?php

namespace app\admin\model;

use think\Model;

class HotelGroup extends Model
{
    // 表名
    protected $name = 'hotel_group';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'grp_status_text'
    ];
    

    
    public function getGrpStatusList()
    {
        return ['1' => __('Grp_status 1'),'2' => __('Grp_status 2')];
    }     


    public function getGrpStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['grp_status']) ? $data['grp_status'] : '');
        $list = $this->getGrpStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
