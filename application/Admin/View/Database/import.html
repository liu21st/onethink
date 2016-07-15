<extend name="Public/base"/>

<block name="body">
    <!-- 标题栏 -->
    <div class="main-title">
        <h2>数据备份</h2>
    </div>
    <!-- /标题栏 -->

    <!-- 应用列表 -->
    <div class="data-table table-striped">
        <table>
            <thead>
                <tr>
                    <th width="200">备份名称</th>
                    <th width="80">卷数</th>
                    <th width="80">压缩</th>
                    <th width="80">数据大小</th>
                    <th width="200">备份时间</th>
                    <th>状态</th>
                    <th width="120">操作</th>
                </tr>
            </thead>
            <tbody>
                <volist name="list" id="data">
                    <tr>
                        <td>{$data.time|date='Ymd-His',###}</td>
                        <td>{$data.part}</td>
                        <td>{$data.compress}</td>
                        <td>{$data.size|format_bytes}</td>
                        <td>{$key}</td>
                        <td>-</td>
                        <td class="action">
                            <a class="db-import" href="{:U('import?time='.$data['time'])}">还原</a>&nbsp;
                            <a class="ajax-get confirm" href="{:U('del?time='.$data['time'])}">删除</a>
                        </td>
                    </tr>
                </volist>
            </tbody>
        </table>
    </div>
    <!-- /应用列表 -->
</block>

<block name="script">
    <script type="text/javascript">
        $(".db-import").click(function(){
            var self = this, status = ".";
            $.get(self.href, success, "json");
            window.onbeforeunload = function(){ return "正在还原数据库，请不要关闭！" }
            return false;
        
            function success(data){
                if(data.status){
                    if(data.gz){
                        data.info += status;
                        if(status.length === 5){
                            status = ".";
                        } else {
                            status += ".";
                        }
                    }
                    $(self).parent().prev().text(data.info);
                    if(data.part){
                        $.get(self.href, 
                            {"part" : data.part, "start" : data.start}, 
                            success, 
                            "json"
                        );
                    }  else {
                        window.onbeforeunload = function(){ return null; }
                    }
                } else {
                    updateAlert(data.info,'alert-error');
                }
            }
        });
    </script>
</block>