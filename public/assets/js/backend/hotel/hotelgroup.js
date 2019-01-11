define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'hotel/hotelgroup/index',
                    add_url: 'hotel/hotelgroup/add',
                    edit_url: 'hotel/hotelgroup/edit',
                    del_url: 'hotel/hotelgroup/del',
                    multi_url: 'hotel/hotelgroup/multi',
                    table: 'hotel_group',
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
                        {field: 'grp_id', title: __('Grp_id')},
                        {field: 'grp_name', title: __('Grp_name')},
                        {field: 'grp_agent', title: __('Grp_agent')},
                        {field: 'grp_mobile', title: __('Grp_mobile')},
                        {field: 'other_mobile', title: __('Other_mobile')},
                        {field: 'grp_status', title: __('Grp_status'), searchList: {"1":__('Grp_status 1'),"2":__('Grp_status 2')}, formatter: Table.api.formatter.status},
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