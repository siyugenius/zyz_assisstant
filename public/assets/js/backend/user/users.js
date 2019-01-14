define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/users/index',
                    add_url: 'user/users/add',
                    edit_url: 'user/users/edit',
                    del_url: 'user/users/del',
                    multi_url: 'user/users/multi',
                    table: 'user',
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
                        {field: 'name', title: __('Name')},
                        {field: 'tel', title: __('Tel')},
                        // {field: 'id_number', title: __('Id_number')},
                        // {field: 'native_plac', title: __('Native_plac')},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2'),"3":__('Type 3')}, formatter: Table.api.formatter.normal},
                        // {field: 'pwd', title: __('Pwd')},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        // {field: 'balance', title: __('Balance'), operate:'BETWEEN'},
                        // {field: 'register_code', title: __('Register_code')},
                        // {field: 'withdraw_pwd', title: __('Withdraw_pwd')},
                        // {field: 'openid', title: __('Openid')},
                        // {field: 'reg_type', title: __('Reg_type'), searchList: {"1":__('Reg_type 1'),"2":__('Reg_type 2')}, formatter: Table.api.formatter.normal},
                        // {field: 'hotel_id', title: __('Hotel_id')},
                        {field: 'hotel_name', title: __('Hotel_name')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        // {field: 'unionid', title: __('Unionid')},
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