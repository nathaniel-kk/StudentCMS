@extends('common.layout')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-xs6 layui-col-xs-offset3">
                <form class="layui-form" lay-filter="exam">
                    <div class="layui-form-item">
                        <label class="layui-form-label">考试名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" class="layui-input" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">考试时间</label>
                        <div class="layui-input-inline">
                            <input autocomplete="off" id="time" class="layui-input" name="time" type="text">
                        </div>
                    </div>
                    <div class="layui-form-item" style="text-align: center;">
                        <button class="layui-btn" lay-filter="save" lay-submit="">提交</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        layui.use(['laydate','form', 'layer'],
            function () {
                $ = layui.jquery;
                var form = layui.form,
                    laydate = layui.laydate,
                    layer = layui.layer;

                laydate.render({
                    elem: '#time', //指定元素
                    trigger: 'click'
                });
                form.val('exam', @json($exam));
                //监听提交
                form.on('submit(save)',
                    function (data) {
                        $.ajax({
                            type: 'put',
                            url: '{{route('exam.update',$exam->id)}}',
                            data: data.field,
                            success: function (res) {
                                if (res.status === 'success') {
                                    layer.alert(res.msg, {icon: 6}, function () {
                                        xadmin.father_reload();
                                    });
                                } else {
                                    layer.msg(res.msg, {icon: 5, time: 1000});
                                }
                            }
                        });
                        return false;
                    });
            });
    </script>
@stop
