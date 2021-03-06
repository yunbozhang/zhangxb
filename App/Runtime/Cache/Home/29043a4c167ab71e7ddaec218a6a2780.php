<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录-借贷管理系统</title>
    <meta name = "keywords" content="<?php echo ($SiteInfo["keywords"]); ?>" >
    <meta name = "description" content="<?php echo ($SiteInfo["description"]); ?>" >
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/Public/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/css/animate.css" rel="stylesheet">
    <link href="/Public/css/style.css" rel="stylesheet">

    <link rel="shortcut icon" href="/Public/img/favicon.ico" />

</head>

<body class="gray-bg">
<div class="row">
    <div class="col-sm-12">
        <div class="middle-box text-center loginscreen  animated fadeInDown">
            <div>
                <div>
                    <h2 >借贷管理系统</h2>
                </div>

                <form class="m-t" role="form" action="/index.php/Home/Login/login_act" method="post">
                    <div class="form-group">
                        <input type="name" class="form-control" placeholder="用户名" name="name" id="login-name">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="密码" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>

                </form>
            </div>
        </div>
 </div>
 </div>
<!-- 调用脚部文件 -->
        <div style="padding:20px;"></div>
        <div class="footer">
            <div class="pull-right">
                One more thing！
            </div>
            <div>
                <strong>Copyright</strong> <a target="_bank" href="http://www.yangzhongchao.com/">羊种草</a> &copy; 2016
            </div>
        </div>

        </div>
        </div>

    <script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/jquery-ui-1.10.4.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>

    <script src="/Public/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/Public/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/Public/js/inspinia.js"></script>
    <script src="/Public/js/plugins/pace/pace.min.js"></script>

    <!-- iCheck -->
    <script src="/Public/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Jvectormap -->
    <script src="/Public/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/Public/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Flot -->
    <script src="/Public/js/plugins/flot/jquery.flot.js"></script>
    <script src="/Public/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/Public/js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- laydate -->
    <script src="/Public/js/plugins/layer/laydate/laydate.js"></script>
    
    <!-- validate -->
    <script src="/Public/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/Public/js/plugins/validate/messages_zh.min.js"></script>

    <!-- morris -->
    <script src="/Public/js/plugins/morris/morris.js"></script>
    <script src="/Public/js/plugins/morris/raphael-2.1.0.min.js"></script>
    
    <!-- morris -->
    <script src="/Public/js/plugins/tableexport/Blob.js"></script>
    <script src="/Public/js/plugins/tableexport/FileSaver.js"></script>
    <script src="/Public/js/plugins/tableexport/tableExport.js"></script>
    
    <!-- Data Tables -->
    <script src="/Public/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/Public/js/plugins/dataTables/dataTables.bootstrap.js"></script>

    <!-- fullcalendar -->
    <script src="/Public/js/jquery-ui.custom.min.js"></script>
    <script src="/Public/js/plugins/fullcalendar/fullcalendar.min.js"></script>

    
    <!-- 时间选择插件 laydate -->
    <script>
        laydate({elem:"#hello",event:"focus"});
    </script>
    <!-- 菜单 -->
    <script>
            var s_url=window.location.pathname;
            var now_url = '';
            for(var i = 0;i<$("#side-menu li").length;i++){
                now_url=$("#side-menu li a").eq(i).attr("href");
                if(now_url == s_url){
                    $("#side-menu a").eq(i).parent().addClass("active");
                    $("#side-menu a").eq(i).parent().parent().parent().addClass("active");
                    $("#side-menu a").eq(i).parent().parent().addClass("in");
                }else{
                    $("#side-menu a").eq(i).parent().removeClass("active");
                }
            }        
    </script>
    <script>
            // 输入验证
            $.validator.setDefaults({
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error").addClass("has-success")
                },
                errorElement: "span",
                errorPlacement: function(e, r) {
                    e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())
                },
                errorClass: "help-block m-b-none",
                validClass: "help-block m-b-none"
            }),
            $().ready(function() {
                var e = "<i class='fa fa-times-circle'></i> ";
                //添加借款人
                $("#add_user").validate({
                    rules: {
                        name: {
                            required: !0,
                            minlength: 2
                        },
                        phone: {
                            required: !0,
                            minlength: 11
                        }

                    },
                    messages: {
                        name: {
                            required: e + "请输入姓名",
                            minlength: e + "姓名必须两个字符以上"
                        },
                        phone: {
                            required: e + "请输入手机号",
                            minlength: e + "手机号必须11个字符以上"
                        }
                    },

                }),
                    // 编辑借款人
                    $("#edit_user").validate({
                        rules: {
                            name: {
                                required: !0,
                                minlength: 2
                            },
                            phone: {
                                required: !0,
                                minlength: 11
                            }
                        },
                    messages: {
                        name: {
                            required: e + "请输入姓名",
                            minlength: e + "姓名必须两个字符以上"
                        },
                        phone: {
                            required: e + "请输入手机号",
                            minlength: e + "手机号必须11个字符以上"
                        }
                    },
                    })

                    // 添加借款
                    $("#add_borrow").validate({
                        rules: {
                            borrow_money: {
                                required: !0,
                                min:1
                            },
                            borrow_interest_rate: {
                                required: !0,
                                min:1
                            },
                            borrow_procedures_rate: {
                                required: !0,
                                min:1
                            },
                            borrow_time: {
                                required: !0,
                            }
                        },
                    messages: {
                        borrow_money: {
                            required: e + "请输入借款金额",
                            min: e + "最少1元！"
                        },
                        borrow_interest_rate: {
                            required: e + "请输入借款利率",
                        },
                        borrow_procedures_rate: {
                            required: e + "请输入借款手续费率",
                        },
                        borrow_time: {
                            required: e + "请选择借款日期",
                        },
                    },
                    })

                    // 续借
                    $("#renew_borrow").validate({
                        rules: {
                            borrow_money: {
                                required: !0,
                                min:1
                            },
                            borrow_interest_rate: {
                                required: !0,
                                min:1
                            },
                            borrow_procedures_rate: {
                                required: !0,
                                min:1
                            },
                            borrow_time: {
                                required: !0,
                            }
                        },
                    messages: {
                        borrow_money: {
                            required: e + "请输入借款金额",
                            min: e + "最少1元！"
                        },
                        borrow_interest_rate: {
                            required: e + "请输入借款利率",
                        },
                        borrow_procedures_rate: {
                            required: e + "请输入借款手续费率",
                        },
                        borrow_time: {
                            required: e + "请选择借款日期",
                        },
                    },
                    })
            });
    </script>
    <!-- 应收实收柱状图 morris-->
    <script>
        $(function() {
            var morris_bar_chart_16  = $("#morris-bar-chart-16");
            if (morris_bar_chart_16[0]) {
                    Morris.Bar({
                        element: "morris-bar-chart-16",
                        data: [<?php echo ($str_2016); ?>],
                        xkey: "y",
                        ykeys: ["a", "b"],
                        labels: ["应收", "实收"],
                        hideHover: "auto",
                        resize: !0,
                        barColors: ["#1ab394", "#ec5464"]
                    });
            };
            var morris_bar_chart_16  = $("#morris-bar-chart-15");
            if (morris_bar_chart_16[0]) {
                    Morris.Bar({
                        element: "morris-bar-chart-15",
                        data: [<?php echo ($str_2015); ?>],
                        xkey: "y",
                        ykeys: ["a", "b"],
                        labels: ["应收", "实收"],
                        hideHover: "auto",
                        resize: !0,
                        barColors: ["#1ab394", "#ec5464"]
                    });
            };
        });
    </script>
    <!-- 导出xls -->
    <script>
    var export_btn  = $("#export-btn");
    if (export_btn) {
            var $exportLink = document.getElementById('export-btn');
            $exportLink.addEventListener('click', function(e){
                e.preventDefault();
                console.log(e.target.getAttribute('data-table'));
                if(e.target.nodeName === "A"){
                    tableExport(e.target.getAttribute('data-table'), '<?php echo ($title); ?>', e.target.getAttribute('data-type'));
                }
            }, false);
    };
    </script>
    <!-- 给模态窗口传值 -->
    <script>
    function change_id(id) {
            $("input[name='id']").val(id);
    }
    </script>
    <!-- datatables -->
    <script>
            function index_table_info ( d ) {
                // `d` is the original data object for the row
                return '<table class="table table-striped">'+
                '<tr>'+
                    '<td>备注:</td>'+
                    '<td>'+d.repayment_remarks+'</td>'+
                '</tr>'+
                '</table>';
            };

            // 还款列表
            function repayment_table_info ( d ) {
                // `d` is the original data object for the row
                return '<table class="table table-striped">'+
                '<tr>'+
                    '<td>备注:</td>'+
                    '<td>'+d.repayment_remarks+'</td>'+
                '</tr>'+
                '</table>';
            };

            // 借款列表
            function borrow_table_info ( d ) {
                // `d` is the original data object for the row
                return '<table class="table table-striped">'+
                    '<tr>'+
                        '<td>合同号:</td>'+
                        '<td>'+d.contract_number+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td>手续费率:</td>'+
                        '<td>'+d.borrow_procedures_rate+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td>借款备注:</td>'+
                        '<td>'+d.borrow_remarks+'</td>'+
                    '</tr>'+                    
                '</table>';
            };

            // 借款人列表
            function user_table_info ( d ) {
                // `d` is the original data object for the row
                return '<table class="table table-striped">'+
                    '<tr>'+
                        '<td>编号:</td>'+
                        '<td>'+d.id+'</td>'+
                    '</tr>'+
                '</table>';
            };

            $(document).ready(function() {

                // 还款列表
                var index_table =  $("#index_table").DataTable({
                      "language": {
                            "decimal":        "",
                            "emptyTable":     "没有数据",
                            "info":           "从 第_START_ 到 _END_条 /共 _TOTAL_ 条数据",
                            "infoEmpty":      "从 0到 0 /共 0条数据",
                            "infoFiltered":   "(从 _MAX_ 条数据中检索)",
                            "infoPostFix":    "",
                            "thousands":      ",",
                            "lengthMenu":     "每页显示 _MENU_ 条记录",
                            "loadingRecords": "加载中...",
                            "processing":     "搜索中...",
                            "search":         "搜索:",
                            "zeroRecords":    "没有检索到数据",
                            "paginate": {
                                "first":      "首页",
                                "last":       "尾页",
                                "next":       "后一页",
                                "previous":   "前一页"
                            },
                      },
                      // 设置相关排列
                      "dom": 't<".col-sm-4"l><".col-sm-4"i><".col-sm-4"p>',
                      "orderMulti": true,
                      "processing": true,
                      "serverSide": true,
                      ajax: {
                            url: '/index.php/Home/Index/ajaxquery',
                            dataSrc: 'data',
                      },
                      "columnDefs": [ {
                      "targets": 8,
                      "orderable": false
                      } ],
                      "columns": [
                                {
                                      "class":          'details-control',
                                      "orderable":      false,
                                      "data":           null,
                                      "defaultContent": ''
                                },
                                { "data": "id" },
                                { "data":"borrow_number"},
                                { "data": "name"},
                                { "data": "repayment_money"},
                                { "data": "repayment_time" },
                                { "data": "real_repayment_time"},
                                { "data": "is_late" },
                                { "data": "action"}
                      ],
                      "order": [ 5, 'asc' ],
                      "lengthMenu": [ [ 50, 100, 200], [ 50, 100, 200] ]

                });

                $('#id').on( 'change', function () {
                        index_table
                        .columns( 0 )
                        .search( this.value )
                        .draw();
                } );
                $('#borrow_number').on( 'change', function () {
                        index_table
                        .columns( 1)
                        .search( this.value )
                        .draw();
                } );
                $('#borrow_uname').on( 'change', function () {
                        index_table
                        .columns( 2 )
                        .search( this.value )
                        .draw();
                });

                $('#index_table tbody').on('click', 'td.details-control', function () {
                        var tr = $(this).closest('tr');
                        var row = index_table.row( tr );
                        if ( row.child.isShown() ) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                        }
                        else {
                            // Open this row
                            row.child( index_table_info (row.data()) ).show();
                            tr.addClass('shown');
                        }
                } );
                
                // 借款列表
                var borrow_table =  $("#borrow_table").DataTable({
                      "language": {
                            "decimal":        "",
                            "emptyTable":     "没有数据",
                            "info":           "从 第_START_ 到 _END_条 /共 _TOTAL_ 条数据",
                            "infoEmpty":      "从 0到 0 /共 0条数据",
                            "infoFiltered":   "(从 _MAX_ 条数据中检索)",
                            "infoPostFix":    "",
                            "thousands":      ",",
                            "lengthMenu":     "每页显示 _MENU_ 条记录",
                            "loadingRecords": "加载中...",
                            "processing":     "搜索中...",
                            "search":         "搜索:",
                            "zeroRecords":    "没有检索到数据",
                            "paginate": {
                                "first":      "首页",
                                "last":       "尾页",
                                "next":       "后一页",
                                "previous":   "前一页"
                            },
                      },
                      // 设置相关排列
                      "dom": 't<".col-sm-4"l><".col-sm-4"i><".col-sm-4"p>',
                      "orderMulti": true,
                      "processing": true,
                      "serverSide": true,
                      ajax: {
                            url: '/index.php/Home/Borrow/ajaxquery',
                            dataSrc: 'data',
                      },
                      "columnDefs": [ {
                        "targets": [7,9,15],
                        "orderable": false
                        }],
                      "columns": [
                                {
                                      "class":          'details-control',
                                      "orderable":      false,
                                      "data":           null,
                                      "defaultContent": ''
                                },
                                { "data": "id" },
                                { "data":"name"},
                                { "data": "contract_number"},
                                { "data": "borrow_number"},
                                { "data": "borrow_money"},
                                { "data": "borrow_time"},
                                { "data": "end_time"},
                                { "data": "borrow_duration" },
                                { "data": "re_borrow_interest" },    //已还利息
                                { "data": "borrow_interest" },          //总利息
                                { "data": "borrow_interest_rate" },
                                { "data": "borrow_procedures" },
                                { "data": "is_procedures" },
                                { "data": "repayment_type" },
                                { "data": "action"}
                      ],
                      "order": [ 1, 'desc' ],
                      "lengthMenu": [ [ 50, 100, 200], [ 50, 100, 200] ]

                });

                $('#id').on( 'change', function () {
                        borrow_table
                        .columns( 0 )
                        .search( this.value )
                        .draw();
                } );
                $('#borrow_number').on( 'change', function () {
                        borrow_table
                        .columns( 1)
                        .search( this.value )
                        .draw();
                } );
                $('#borrow_uname').on( 'change', function () {
                        borrow_table
                        .columns( 2 )
                        .search( this.value )
                        .draw();
                });

                $('#borrow_table tbody').on('click', 'td.details-control', function () {
                        var tr = $(this).closest('tr');
                        var row = borrow_table.row( tr );
                        if ( row.child.isShown() ) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                        }
                        else {
                            // Open this row
                            row.child( borrow_table_info(row.data()) ).show();
                            tr.addClass('shown');
                        }
                } );

            // 还款列表
            var repayment_table =  $("#repayment_table").DataTable({
                  "language": {
                        "decimal":        "",
                        "emptyTable":     "没有数据",
                        "info":           "从 第_START_ 到 _END_条 /共 _TOTAL_ 条数据",
                        "infoEmpty":      "从 0到 0 /共 0条数据",
                        "infoFiltered":   "(从 _MAX_ 条数据中检索)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "每页显示 _MENU_ 条记录",
                        "loadingRecords": "加载中...",
                        "processing":     "搜索中...",
                        "search":         "搜索:",
                        "zeroRecords":    "没有检索到数据",
                        "paginate": {
                            "first":      "首页",
                            "last":       "尾页",
                            "next":       "后一页",
                            "previous":   "前一页"
                        },
                  },
                  // 设置相关排列
                  "dom": 't<".col-sm-4"l><".col-sm-4"i><".col-sm-4"p>',
                  "orderMulti": true,
                  "processing": true,
                  "serverSide": true,
                  ajax: {
                        url: '/index.php/Home/Repayment/ajaxquery',
                        dataSrc: 'data',
                  },
                  "columnDefs": [ {
                  "targets": [9,10],
                  "orderable": false
                  }],
                  "columns": [
                            {
                                  "class":          'details-control',
                                  "orderable":      false,
                                  "data":           null,
                                  "defaultContent": ''
                            },
                            { "data": "id" },
                            { "data":"borrow_number"},
                            { "data": "name"},
                            { "data": "repayment_money"},
                            { "data": "repayment_time" },
                            { "data": "real_repayment_time"},
                            { "data": "is_repayment" },
                            { "data": "is_late" },
                            { "data": "all_late_money" },
                            { "data": "action"}
                  ],

                  "order": [[7,'asc'], [8,'desc']],
                  "lengthMenu": [ [ 50, 100, 200], [ 50, 100, 200] ]

            });

            $('#id').on( 'change', function () {
                    repayment_table
                    .columns( 0 )
                    .search( this.value )
                    .draw();
            } );
            $('#borrow_number').on( 'change', function () {
                    repayment_table
                    .columns( 1)
                    .search( this.value )
                    .draw();
            } );
            $('#borrow_uname').on( 'change', function () {
                    repayment_table
                    .columns( 2 )
                    .search( this.value )
                    .draw();
            });

            $('#repayment_table tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = repayment_table.row( tr );
                    if ( row.child.isShown() ) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        row.child( repayment_table_info (row.data()) ).show();
                        tr.addClass('shown');
                    }
            } );


            // 借款人列表
            var user_table =  $("#user_table").DataTable({
                  "language": {
                        "decimal":        "",
                        "emptyTable":     "没有数据",
                        "info":           "从 第_START_ 到 _END_条 /共 _TOTAL_ 条数据",
                        "infoEmpty":      "从 0到 0 /共 0条数据",
                        "infoFiltered":   "(从 _MAX_ 条数据中检索)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "每页显示 _MENU_ 条记录",
                        "loadingRecords": "加载中...",
                        "processing":     "搜索中...",
                        "search":         "搜索:",
                        "zeroRecords":    "没有检索到数据",
                        "paginate": {
                            "first":      "首页",
                            "last":       "尾页",
                            "next":       "后一页",
                            "previous":   "前一页"
                        },
                  },
                  // 设置相关排列
                  "dom": 't<".col-sm-4"l><".col-sm-4"i><".col-sm-4"p>',
                  "orderMulti": true,
                  "processing": true,
                  "serverSide": true,
                  // "buttons": [
                  //         'copy', 'excel', 'pdf'
                  //   ],
                  ajax: {
                        url: '/index.php/Home/User/ajaxquery',
                        dataSrc: 'data',
                  },
                  "columnDefs": [ {
                  "targets": [4,5,6,7,8,9,10,12],
                  "orderable": false
                  } ],
                  "columns": [
                            {
                                  "class":          'details-control',
                                  "orderable":      false,
                                  "data":           null,
                                  "defaultContent": ''
                            },
                            { "data": "id" },
                            { "data": "name"},
                            { "data": "phone"},
                            { "data": "late_rate"},
                            { "data": "borrow_count"},
                            { "data": "borrow_renew_count"},
                            { "data": "all_borrow_money"},
                            { "data": "all_borrow_procedures"},
                            { "data": "all_borrow_interest"},
                            { "data": "all_re_borrow_interest"},
                            { "data": "add_time"},
                            { "data": "action"}
                  ],
                  "order": [ 1, 'desc' ],
                  "lengthMenu": [ [ 50, 100, 200], [ 50, 100, 200] ]

            });

            $('#id').on( 'change', function () {
                    user_table
                    .columns( 1 )
                    .search( this.value )
                    .draw();
            } );
            $('#name').on( 'change', function () {
                    user_table
                    .columns( 2)
                    .search( this.value )
                    .draw();
            } );

            $('#user_table tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = user_table.row( tr );
                    if ( row.child.isShown() ) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        row.child( user_table_info (row.data()) ).show();
                        tr.addClass('shown');
                    }
            } );

    });
    </script>
    <!-- fullCalendar -->
    <script>
    $(document).ready(function() {

        var b = new Date();
        var c = b.getDate();
        var a = b.getMonth();
        var e = b.getFullYear();

        $("#calendar").fullCalendar({
            // 头部显示
            header: {
                left: "prevYear,prev,next,nextYear,today",
                center: "title",
                right: "month,basicWeek,basicDay"
            },
            // 按钮描述
            // buttonText:{
            //     prevYear:"上一年",
            //     nextYear:"下一年",
            //     prev:"上一月",
            //     next:"下一月",
            // },
            //设置显示日历每列表头信息的格式文本
            columnFormat:{
                    month: 'dddd', // Mon
                    week: 'dddd yyyy-M-d', // Mon 9/7
                    day: 'dddd yyyy-M-d' // Monday 9/7 
                },
            //  设置用于显示日历头部的文本信息
            titleFormat:{
                    month: 'yyyy年 MMMM', // September 2013
                    week: "yyyy年 MMMM d号[ yyyy]{ '-->'[ MMMm] d号} 第W周", // Sep 7 - 13 2013
                    day: 'yyyy年 MMMM d号, dddd ' // Tuesday, Sep 8, 2013
            },
            selectable:true,
            aspectRatio:2,          //设置日历单元格宽度与高度的比例
            // editable: true,             //可编辑
            // droppable: true,
            drop: function(g, h) {
                var f = $(this).data("eventObject");
                var d = $.extend({},
                f);
                d.start = g;
                d.allDay = h;
                $("#calendar").fullCalendar("renderEvent", d, true);
            },
            events: function(start, end, callback) {
                        $.getJSON("/index.php/Home/Index/richeng/m/"+end.getMonth()+"/y/"+end.getFullYear(), function(result) {
                                                                            // console.log(result.data);
                                                                            callback(result.data);
                                                                }
                        )},
        });
    });
    </script>
    <script>
        function DX(num) {
                var strOutput = "",
                strUnit = '仟佰拾亿仟佰拾万仟佰拾元角分';
                num += "00";
                var intPos = num.indexOf('.');
                if (intPos >= 0){
                        num = num.substring(0, intPos) + num.substr(intPos + 1, 2);
                }
                strUnit = strUnit.substr(strUnit.length - num.length);
                for (var i=0; i < num.length; i++){
                        strOutput += '零壹贰叁肆伍陆柒捌玖'.substr(num.substr(i,1),1) + strUnit.substr(i,1);
                }
                return strOutput.replace(/零角零分$/, '整').replace(/零[仟佰拾]/g, '零').replace(/零{2,}/g, '零').replace(/零([亿|万])/g, '$1').replace(/零+元/, '元').replace(/亿零{0,3}万/, '亿').replace(/^元/, "零元")
        }; 

    $(document).ready(function(){  
            $('input[name=name]').focus();   //登录页面焦点
            $('input[name=borrow_money]').on( 'keyup', function () {
                        var format = DX(this.value);
                        $('#input_money_format').html(format);
            });

     });

    </script>
</body>

</html>