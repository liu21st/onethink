<extend name="Public/base" />
<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>权限管理</h2>
	</div>

    <div class="tools auth-botton">
        <a id="add-group" class="btn" href="{:U('createGroup')}">新 增</a>
        <a url="{:U('changestatus',array('method'=>'resumeGroup'))}" class="btn ajax-post" target-form="ids" >启 用</a>
        <a url="{:U('changestatus',array('method'=>'forbidGroup'))}" class="btn ajax-post" target-form="ids" >禁 用</a>
        <a url="{:U('changestatus',array('method'=>'deleteGroup'))}" class="btn ajax-post confirm" target-form="ids" >删 除</a>
    </div>
	<!-- 数据列表 -->
	<div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">用户组</th>
		<th class="">描述</th>

		<th class="">授权</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<notempty name="_list">
		<volist name="_list" id="vo">
		<tr>
            <td><input class="ids" type="checkbox" name="id[]" value="{$vo.id}" /></td>
			<td><a href="{:U('AuthManager/editgroup?id='.$vo['id'])}">{$vo.title}</a> </td>
			<td><span>{:mb_strimwidth($vo['description'],0,60,"...","utf-8")}</span></td>


			<td><a href="{:U('AuthManager/access?group_name='.$vo['title'].'&group_id='.$vo['id'])}" >访问授权</a>
			<a href="{:U('AuthManager/category?group_name='.$vo['title'].'&group_id='.$vo['id'])}" >分类授权</a>
			<a href="{:U('AuthManager/user?group_name='.$vo['title'].'&group_id='.$vo['id'])}" >成员授权</a>
			</td>
			<td>{$vo.status_text}</td>
			<td><eq name="vo.status" value="1">
				<a href="{:U('AuthManager/changeStatus?method=forbidGroup&id='.$vo['id'])}" class="ajax-get">禁用</a>
				<else/>
				<a href="{:U('AuthManager/changeStatus?method=resumeGroup&id='.$vo['id'])}" class="ajax-get">启用</a>
				</eq>
				<a href="{:U('AuthManager/changeStatus?method=deleteGroup'.$vo['id'])}" class="confirm ajax-get">删除</a>
                </td>
		</tr>
		</volist>
		<else/>
		<td colspan="6" class="text-center"> aOh! 暂时还没有内容! </td>
		</notempty>
	</tbody>
    </table>

	</div>
    <div class="page">
        {$_page}
    </div>
</block>

<block name="script">
<script type="text/javascript" charset="utf-8">
    //导航高亮
    highlight_subnav('{:U('AuthManager/index')}');
</script>
</block>
