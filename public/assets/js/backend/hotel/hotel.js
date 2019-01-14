define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'hotel/hotel/index',
                    add_url: 'hotel/hotel/add',
                    edit_url: 'hotel/hotel/edit',
                    del_url: 'hotel/hotel/del',
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
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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