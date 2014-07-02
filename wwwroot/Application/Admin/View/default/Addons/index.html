<extend name="Public/base" />

<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>插件列表</h2>
	</div>
	<div>
		<a href="{:U('create')}" class="btn">快速创建</a>
	</div>

	<!-- 数据列表 -->
	<div class="data-table table-striped">
		<table>
			<thead>
				<tr>
					<th>名称</th>
					<th>标识</th>
					<th >描述</th>
					<th width="43px">状态</th>
					<th>作者</th>
					<th width="43px">版本</th>
					<th width="94px">操作</th>
				</tr>
			</thead>
			<tbody>
				<notempty name="_list">
				<volist name="_list" id="vo">
				<tr>
					<td>{$vo.title}</td>
					<td>{$vo.name}</td>
					<td>{$vo.description}</td>
					<td>{$vo.status_text|default="未安装"}</td>
					<td><a target="_blank" href="{$vo.url|default='http://www.onethink.cn'}">{$vo.author}</a></td>
					<td>{$vo.version}</td>
					<td>
						<empty name="vo.uninstall">
							<?php
								$class	= get_addon_class($vo['name']);
								if(!class_exists($class)){
									$has_config = 0;
								}else{
									$addon = new $class();
									$has_config = count($addon->getConfig());
								}
							?>
							<?php if ($has_config): ?>
								<a href="{:U('config',array('id'=>$vo['id']))}">设置</a>
							<?php endif ?>
						<?php if ($vo['status'] >=0): ?>
							<eq name="vo.status" value="0">
								<a class="ajax-get" href="{:U('enable',array('id'=>$vo['id']))}">启用</a>
							<else />
								<a class="ajax-get" href="{:U('disable',array('id'=>$vo['id']))}">禁用</a>
							</eq>
						<?php endif ?>
							{// <eq name="vo.author" value="thinkphp">}
								<a class="ajax-get" href="{:U('uninstall?id='.$vo['id'])}">卸载</a>
							{// </eq>}
						<else />
							<a class="ajax-get" href="{:U('install?addon_name='.$vo['name'])}">安装</a>
						</empty>
					</td>
				</tr>
				</volist>
				<else/>
				<td colspan="6" class="text-center"> aOh! 暂时还没有内容! </td>
				</notempty>
			</tbody>
		</table>
	</div>
	<!-- 分页 -->
    <div class="page">
        {$_page}
    </div>
</block>
