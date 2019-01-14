define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'hotel/hotel/index',
                    add_url: 'hotel/hotel/add',
                    edit_url: 'hotel/hotel/edit',
                    //del_url: 'hotel/hotel/del',
                    multi_url: 'hotel/hotel/multi',
                    table: 'hotel',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        //{field: 'hotel_no', title: __('Hotel_no')},
                        {field: 'name', title: __('Name')},
                        {field: 'address', title: __('Address')},
                        {field: 'group.grp_name', title: __('Group_id')},
                        /*{field: 'province_id', title: __('Province_id')},
                        {field: 'city_id', title: __('City_id')},
                        {field: 'area_id', title: __('Area_id')},*/
                      /*  {field: 'longitude', title: __('Longitude'), operate:'BETWEEN'},
                        {field: 'latitude', title: __('Latitude'), operate:'BETWEEN'},*/
                        {field: 'administrator', title: __('Administrator')},
                        {field: 'tel', title: __('Tel')},
                        /*{field: 'other_tel', title: __('Other_tel')},
                        {field: 'email', title: __('Email')},*/
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons:[
                                {
                                    name: 'view',
                                    icon: 'fa fa-list',
                                    title: __('view'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    url: 'hotel/hotel/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        return true;
                                    }
                                },
                            ],
                            formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});