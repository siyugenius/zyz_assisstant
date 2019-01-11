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
                        {field: 'hotel_id', title: __('Hotel_id')},
                        {field: 'name', title: __('Name')},
                        {field: 'group_id', title: __('Group_id')},
                        {field: 'province_id', title: __('Province_id')},
                        {field: 'city_id', title: __('City_id')},
                        {field: 'area_id', title: __('Area_id')},
                        {field: 'longitude', title: __('Longitude'), operate:'BETWEEN'},
                        {field: 'latitude', title: __('Latitude'), operate:'BETWEEN'},
                        {field: 'administrator', title: __('Administrator')},
                        {field: 'tel', title: __('Tel')},
                        {field: 'other_tel', title: __('Other_tel')},
                        {field: 'email', title: __('Email')},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'group.id', title: __('Group.id')},
                        {field: 'group.grp_id', title: __('Group.grp_id')},
                        {field: 'group.grp_name', title: __('Group.grp_name')},
                        {field: 'group.grp_agent', title: __('Group.grp_agent')},
                        {field: 'group.grp_mobile', title: __('Group.grp_mobile')},
                        {field: 'group.other_mobile', title: __('Group.other_mobile')},
                        {field: 'group.grp_status', title: __('Group.grp_status'), formatter: Table.api.formatter.status},
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