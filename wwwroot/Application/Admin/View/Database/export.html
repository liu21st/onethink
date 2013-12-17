<extend name="Public/base"/>

<block name="body">
    <!-- 标题栏 -->
    <div class="main-title">
        <h2>数据备份</h2>
    </div>
    <!-- /标题栏 -->

    <div class="cf">
        <a id="export" class="btn" href="javascript:;" autocomplete="off">立即备份</a>
        <a id="optimize" class="btn" href="{:U('optimize')}">优化表</a>
        <a id="repair" class="btn" href="{:U('repair')}">修复表</a>
    </div>

    <!-- 应用列表 -->
    <div class="data-table table-striped">
        <form id="export-form" method="post" action="{:U('export')}">
            <table>
                <thead>
                    <tr>
                        <th width="48"><input class="check-all" checked="chedked" type="checkbox" value=""></th>
                        <th>表名</th>
                        <th width="120">数据量</th>
                        <th width="120">数据大小</th>
                        <th width="160">创建时间</th>
                        <th width="160">备份状态</th>
                        <th width="120">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <volist name="list" id="table">
                        <tr>
                            <td class="num">
                                <input class="ids" checked="chedked" type="checkbox" name="tables[]" value="{$table.name}">
                            </td>
                            <td>{$table.name}</td>
                            <td>{$table.rows}</td>
                            <td>{$table.data_length|format_bytes}</td>
                            <td>{$table.create_time}</td>
                            <td class="info">未备份</td>
                            <td class="action">
                                <a class="ajax-get no-refresh" href="{:U('optimize?tables='.$table['name'])}">优化表</a>&nbsp;
                                <a class="ajax-get no-refresh" href="{:U('repair?tables='.$table['name'])}">修复表</a>
                            </td>
                        </tr>
                    </volist>
                </tbody>
            </table>
        </form>
    </div>
    <!-- /应用列表 -->
</block>

<block name="script">
    <script type="text/javascript">
    (function($){
        var $form = $("#export-form"), $export = $("#export"), tables
            $optimize = $("#optimize"), $repair = $("#repair");

        $optimize.add($repair).click(function(){
            $.post(this.href, $form.serialize(), function(data){
                if(data.status){
                    updateAlert(data.info,'alert-success');
                } else {
                    updateAlert(data.info,'alert-error');
                }
                setTimeout(function(){
	                $('#top-alert').find('button').click();
	                $(that).removeClass('disabled').prop('disabled',false);
	            },1500);
            }, "json");
            return false;
        });

        $export.click(function(){
            $export.parent().children().addClass("disabled");
            $export.html("正在发送备份请求...");
            $.post(
                $form.attr("action"),
                $form.serialize(),
                function(data){
                    if(data.status){
                        tables = data.tables;
                        $export.html(data.info + "开始备份，请不要关闭本页面！");
                        backup(data.tab);
                        window.onbeforeunload = function(){ return "正在备份数据库，请不要关闭！" }
                    } else {
                        updateAlert(data.info,'alert-error');
                        $export.parent().children().removeClass("disabled");
                        $export.html("立即备份");
                        setTimeout(function(){
        	                $('#top-alert').find('button').click();
        	                $(that).removeClass('disabled').prop('disabled',false);
        	            },1500);
                    }
                },
                "json"
            );
            return false;
        });

        function backup(tab, status){
            status && showmsg(tab.id, "开始备份...(0%)");
            $.get($form.attr("action"), tab, function(data){
                if(data.status){
                    showmsg(tab.id, data.info);

                    if(!$.isPlainObject(data.tab)){
                        $export.parent().children().removeClass("disabled");
                        $export.html("备份完成，点击重新备份");
                        window.onbeforeunload = function(){ return null }
                        return;
                    }
                    backup(data.tab, tab.id != data.tab.id);
                } else {
                    updateAlert(data.info,'alert-error');
                    $export.parent().children().removeClass("disabled");
                    $export.html("立即备份");
                    setTimeout(function(){
    	                $('#top-alert').find('button').click();
    	                $(that).removeClass('disabled').prop('disabled',false);
    	            },1500);
                }
            }, "json");

        }

        function showmsg(id, msg){
            $form.find("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg);
        }
    })(jQuery);
    </script>
</block>