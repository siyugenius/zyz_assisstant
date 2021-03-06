define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/users/index',
                    add_url: 'user/users/add',
                    // edit_url: 'user/users/edit',
                    // del_url: 'user/users/del',
                    multi_url: 'user/users/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortOrder: 'asc',
                sortName: 'id',
                exportTypes: ['excel'],
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'name', title: __('Name'), operate: false},
                        {field: 'tel', title: __('Tel'), operate: false},
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
                        {field: 'hotel_no', title: __('酒店编号'), operate: false},
                        {field: 'hotel_name', title: __('Hotel_name'), operate: 'LIKE'},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', operate: false},
                        // {field: 'unionid', title: __('Unionid')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons:[
                                {
                                    name: 'view',
                                    icon: 'fa fa-list',
                                    title: __('view'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    url: 'user/users/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    visible: function (row) {
                                        // 返回true时按钮显示,返回false隐藏
                                        return true;
                                    }
                                },
                            ],

                        },

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