@extends('common.layout')

@section('content')
    <div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a><cite>考试列表</cite></a>
    </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
           onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <table id="exam_table" lay-filter="test"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @include('common.addbtn',['name'=>'添加'])
    @include('common.operation')


    <script>
        layui.use(['laydate', 'form'],
            function () {
                var laydate = layui.laydate
                    , form = layui.form;
                //执行一个laydate实例
                laydate.render({
                    elem: '#start' //指定元素
                    , trigger: 'click'
                });

                //执行一个laydate实例
                laydate.render({
                    elem: '#end' //指定元素
                    , trigger: 'click'
                });
            });
    </script>
    <script>
        layui.use('table',
            function () {
                var table = layui.table;
                table.render({
                    elem: '#exam_table'
                    , height: 'full-130'
                    , method: 'post'
                    , url: '{{route('getexams')}}' //数据接口
                    , page: true //开启分页
                    , cellMinWidth: 50 //全局定义常规单元格的最小宽度
                    , cols: [[
                        {type: 'numbers', title: '序号', width: 100, align: 'center'}
                        , {field: 'name', title: '考试名称', sort: true, align: 'center'}
                        , {field: 'time', title: '考试时间', sort: true, align: 'center'}
                        @can('isAdmin')
                        , {title: '操作', toolbar: '#barDemo', width: 150}
                        @endcan
                    ]]
                    , limit: 10
                    , limits: [10, 20, 30, 50, 100]
                    , toolbar: '#toolbarDemo'
                });

                //监听行工具事件
                table.on('tool(test)', function (obj) {
                    var data = obj.data;
                    switch (obj.event) {
                        case 'edit':
                            var url = '{{route('exam.edit','xxx')}}'.replace('xxx', data.id);
                            xadmin.open('修改考试信息', url, 700, 400);
                            break;

                        case 'del':
                            layer.confirm('真的删除<span style="color:red;">' + data.name + '</span>考试么', function (index) {
                                var url = '{{route('exam.destroy','xxx')}}'.replace('xxx', data.id);
                                $.ajax({
                                    type: 'delete',
                                    url: url,
                                    success: function (res) {
                                        if (res.status === 'success') {
                                            layer.alert(res.msg, {icon: 6}, function (index) {
                                                window.location.reload();
                                            })
                                        } else {
                                            layer.alert(res.msg, {icon: 5});
                                        }
                                    }
                                });

                            });
                            break;
                    }
                });

                //头工具栏事件
                table.on('toolbar(test)', function (obj) {
                    switch (obj.event) {
                        case 'add':
                            xadmin.open('添加考试', '{{route('exam.create')}}', 700, 400);
                            break;
                    }
                });

                @can('isAdmin')
                //监听行双击事件（双击事件为：row）
                table.on('rowDouble(test)', function (obj) {
                    var data = obj.data;
                    // 在这里显示某个学生具体的弹窗
                    var url = '{{route('exam.edit','xxx')}}'.replace('xxx', data.id);
                    xadmin.open('修改考试信息', url, 800);
                    //标注选中样式
                    obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
                });
                @endcan
            });
    </script>
@stop
