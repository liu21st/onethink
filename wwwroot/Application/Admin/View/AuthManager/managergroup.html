<!-- 管理员用户组新增和编辑页面 -->
<extend name="Public/base" />
<block name="body">
	<div class="tab-wrap">
		<ul class="tab-nav nav">
			<li class="current"><a href="javascript:;">访问授权</a></li>
            <li><a href="{:U('AuthManager/category',array('group_name'=>I('group_name') ,'group_id'=> I('group_id')))}">分类授权</a></li>
			<li><a href="{:U('AuthManager/user',array('group_name'=>I('group_name') ,'group_id'=> I('group_id')))}">成员授权</a></li>
			<li class="fr">
				<select name="group">
					<volist name="auth_group" id="vo">
						<option value="{:U('AuthManager/access',array('group_id'=>$vo['id'],'group_name'=>$vo['title']))}" <eq name="vo['id']" value="$this_group['id']">selected</eq> >{$vo.title}</option>
					</volist>
				</select>
			</li>
		</ul>
		<div class="tab-content">
			<!-- 访问授权 -->
			<div class="tab-pane in">
				<form action="{:U('AuthManager/writeGroup')}" enctype="application/x-www-form-urlencoded" method="POST" class="form-horizontal auth-form">
					<volist name="node_list" id="node" >
						<dl class="checkmod">
							<dt class="hd">
								<label class="checkbox"><input class="auth_rules rules_all" type="checkbox" name="rules[]" value="<?php echo $main_rules[$node['url']] ?>">{$node.title}管理</label>
							</dt>
							<dd class="bd">
								<present name="node['child']">
								<volist name="node['child']" id="child" >
                                    <div class="rule_check">
                                        <div>
                                            <label class="checkbox" <notempty name="child['tip']">title='{$child.tip}'</notempty>>
                                           <input class="auth_rules rules_row" type="checkbox" name="rules[]" value="<?php echo $auth_rules[$child['url']] ?>"/>{$child.title}
                                            </label>
                                        </div>
                                       <notempty name="child['operator']">
                                           <span class="divsion">&nbsp;</span>
                                           <span class="child_row">
                                               <volist name="child['operator']" id="op">
                                                   <label class="checkbox" <notempty name="op['tip']">title='{$op.tip}'</notempty>>
                                                       <input class="auth_rules" type="checkbox" name="rules[]"
                                                       value="<?php echo $auth_rules[$op['url']] ?>"/>{$op.title}
                                                   </label>
                                               </volist>
                                           </span>
                                       </notempty>
				                    </div>
								</volist>
								</present>
							</dd>
						</dl>
					</volist>

			        <input type="hidden" name="id" value="{$this_group.id}" />
                    <button type="submit" class="btn submit-btn ajax-post" target-form="auth-form">确 定</button>
                    <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
			    </form>
			</div>

			<!-- 成员授权 -->
			<div class="tab-pane"></div>

			<!-- 分类 -->
			<div class="tab-pane"></div>
		</div>
	</div>

</block>
<block name="script">
<script type="text/javascript" src="__STATIC__/qtip/jquery.qtip.min.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/qtip/jquery.qtip.min.css" media="all">
<script type="text/javascript" charset="utf-8">
    +function($){
        var rules = [{$this_group.rules}];
        $('.auth_rules').each(function(){
            if( $.inArray( parseInt(this.value,10),rules )>-1 ){
                $(this).prop('checked',true);
            }
            if(this.value==''){
                $(this).closest('span').remove();
            }
        });

        //全选节点
        $('.rules_all').on('change',function(){
            $(this).closest('dl').find('dd').find('input').prop('checked',this.checked);
        });
        $('.rules_row').on('change',function(){
            $(this).closest('.rule_check').find('.child_row').find('input').prop('checked',this.checked);
        });

        $('.checkbox').each(function(){
            $(this).qtip({
                content: {
                    text: $(this).attr('title'),
                    title: $(this).text()
                },
                position: {
                    my: 'bottom center',
                    at: 'top center',
                    target: $(this)
                },
                style: {
                    classes: 'qtip-dark',
                    tip: {
                        corner: true,
                        mimic: false,
                        width: 10,
                        height: 10
                    }
                }
            });
        });

        $('select[name=group]').change(function(){
			location.href = this.value;
        });
        //导航高亮
        highlight_subnav('{:U('AuthManager/index')}');
    }(jQuery);
</script>
</block>
