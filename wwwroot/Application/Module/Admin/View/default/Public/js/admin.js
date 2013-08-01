// +----------------------------------------------------------------------
// | 后台UI
// +----------------------------------------------------------------------
// | Copyright (c) Topthink.com All Rights Reserved.
// +----------------------------------------------------------------------
// | Author: topthink zzguo28 <zzguo28@topthink.com>
// +----------------------------------------------------------------------
// $Id$
(function($){
    /**
     +----------------------------------------------------------
     * 插件开发 输出调试
     +----------------------------------------------------------
    */
    $.dump = function(msg)
    {
        if(typeof msg === 'string')
        {
            msg = msg.indexOf('|') != -1 && '<p>'+ msg.split('|').join('</p><p>') ||'<p>' + msg ;
        }

        if(typeof msg === 'object')
        {
            var msgs = [];
            for(var p in msg)
            {
                var _type  = typeof msg[p],
                    _val   = msg[p];
                if('string' == _type)
                {
                    _val = '"'+ _val + '"';
                }
                msgs.push(p + ':&nbsp;' + _val + ' ('+ _type + ')');
            }
            msg = '<p>' + msgs.join('</p><p>');
        }

        if ( !$('#debug_dump').length )
        {
            $('body').prepend('<div id="debug_dump"></div>');
        }

        $('#debug_dump')
            .css({
                position        : 'absolute',
                backgroundColor : '#000',
                opacity         :  '0.65',
                top             :  '0',
                left            :  '0',
                width           :  $('body').width(),
                lineHeight      :  '28px',
                zIndex          :  '99999',
                textIndent      :  '20px',
                color           :  '#fff'
            })
            .html('输出结果是： '+ msg +'</p>')
            .click(function(){
                $(this).mouseout(function(){
                     $('#debug_dump').fadeOut('slow').empty().remove();
                });
            });
    };
})(jQuery);

(function($){

    var win  = {},
        con  = {},
        head = {},
        side = {},
        main = {};
        iframe = {};

    // 执行流程
    function ThinkAdmin()
    {
        /* $('.admin_overbg').css({backgroundColor:'#000',opacity:0.7}); 暂时弃用 */
        _initialize();
        auto_resize();
        head.menuIsShow = 0;
        bind_head_menu();
        bind_side_menu();
        bind_side_tree();
        win.self.resize(auto_resize);
    }

    // 初始化全局元素 减免再次获取消耗
    function _initialize()
    {
        win.self  = $(window);

        con.self  = $('#admin_content');
        con.div   = $('.uibox_body>div',con.self);
        iframe.self = $('#admin_view');
        iframe.historys = [];

        head.self = $('#admin_header');
        head.nav  = $('#admin_nav');
        head.h2   = head.nav.children('h2');
        head.a    = head.h2.children('a');
        head.menu = $('#nav_menu>a');
        head.title= head.menu.children('strong');
        head.menuBox = $('.admin_nav_menu','#nav_menu');
        head.userlink = $('#admin_userlink');

        side.self   = $('#admin_side');
        side.title  = $('#side_title>h2');
        side.body   = $('#side_content');
        check_side_menu();

        main.self  = $('#admin_main');
        main.title = $('#main_title>h2');
        main.body  = $('#main_content');
        main.btm   = $('.uibox_btm_content',main.self);
        main.tag1  = head.h2.eq(0).find('strong').text();
        main.topAndBody = $('.uibox_top_content,.uibox_body_content',main.self);
    }

    function check_side_menu()
    {
        side.menu   = side.body.children('#menu_default');
        side.tree   = side.body.children('#menu_tree');
        side.isMenu = side.menu.length;
        side.isTree = side.tree.length;
        // 定义默认菜单系列元素
        if(side.isMenu)
        {
            side.menuUl    = side.menu.children('ul');
            side.menuList  = side.menuUl.children('li');
            side.menuH3    = side.menuList.children('h3');
            side.menuDiv   = side.menuList.children('div');
            side.menuLink  = side.menuDiv.children('a');
            side.menuCount = side.menuList.length;
            side.menuListH = side.menuCount * 22 + 20;
            side.menuLinkFirst = side.menuList.eq(0).find('>div>a:first');
            side.menuLinkFirst.addClass('on');
            side.menuList.last().addClass('last');
            main.tag2 = side.menuList.eq(0).find('h3>a').text();
        }
        else
        {
            //alert ('暂未编写树状菜单处理方式');
        }
    }

    // UI自适应宽高
    function auto_resize()
    {
        // 记录当前size信息
        win.width       = win.self.width();
        win.height      = win.self.height();
        con.height      = win.height - 78;//50+29
        head.navWidth   = win.width - 450;
        side.height     = con.height - 30;//20+10
        side.bodyH      = side.height - 44;//32+12
        side.menuUlH    = side.bodyH - 8;
        main.height     = side.height;
        main.bodyH      = side.bodyH;
        main.width      = win.width - 268;//202+20+20+26
        // 自适应宽高
        head.nav.width(head.navWidth);
        con.self.height(con.height);
        side.self.height( side.height );
        main.self.height( main.height );
        con.div.height(side.bodyH);
        main.topAndBody.width( main.width - 24 );//12*2
        main.btm.width( main.width-48 );
        // 侧栏菜单自适应
        set_side_menu();
        set_side_tree();
    }

    // 导航区菜单隐藏与消失
    function menuBox_show(isShow)
    {
        if(isShow)
        {
            head.menuBox.slideDown(200);
            head.menuIsShow = 1;
        }
        else
        {
            head.menuBox.slideUp(200);
            head.menuIsShow = 0;
        }
    }

    // 导航区下拉菜单处理
    function bind_head_menu()
    {
        head.a.bind('click.ThinkAdmin',function(e){
            e.preventDefault();

            var $this = $(this),
                $h2   = $this.parent('h2').setOn(),
                _href = $this.attr('href');

            // 下拉菜单
            if($h2.attr('id'))
            {
                menuBox_show(!head.menuIsShow);
            }
            else
            {
                if(head.menuIsShow){menuBox_show(0);}
                head.title.html('应用管理');
                head.menuBox.find('li').removeClass('on');
                main.tag1 = $this.text();
                side.title.html(main.tag1);
                side_menu_load(_href);
            }
            $this.blur();
            return false;
        });

        head.menuBox.find('a').bind('click.ThinkAdmin',function(e){
            e.preventDefault();
            var $this  = $(this),
                _title = $this.html(),
                _href  = $this.attr('href');

            $this.parent('li').setOn();
            head.title.html(_title);
            side.title.html(_title);
            main.tag1 = _title;
            menuBox_show(0);
            side_menu_load(_href);
            $this.blur();
            return false;
        });

        head.userlink.children('a[tag]').each(function() {
            $(this).bind('click',function(){
                main.title.html($(this).attr('tag'));
            });
        });
    }

    // 侧栏菜单变更
    function side_menu_load(_href)
    {
        side.body.load(_href,function(){
            check_side_menu();
            set_side_menu();
            set_side_tree();
            bind_side_menu();
            bind_side_tree();
            iframe.self.attr('src',side.menuLinkFirst.attr('href'));
            main.title.html(main.tag1 + ' > ' + main.tag2 + ' > ' +side.menuLinkFirst.text());
            // todo main content
        });
    }

    // 绑定侧栏默认菜单事件
    function bind_side_menu()
    {
        if(!side.isMenu){ return;}
        side.menuH3.bind('click.ThinkAdmin',function(){
            var $this = $(this).blur();
            main.tag2 = $this.children('a').text();
            $this.parent('li').setOn();
            side.menuLink.removeClass('on');
        });

        side.menuLink.bind('click.ThinkAdmin',function(){
            $(this).setOn();
            main.title.html(main.tag1 + ' > ' + main.tag2 + ' > ' +$(this).text());
        });
    }

    // 绑定侧栏树菜单事件
    function bind_side_tree()
    {
        if(!side.isTree){ return;}
        alert ('触发了tree处理');
    }

    // 侧栏默认菜单处理
    function set_side_menu()
    {
        if(!side.isMenu){ return;}
        side.menuUl.height(side.menuUlH);
        side.menuDiv.height( side.menuUlH - side.menuListH );
    }

    // 侧栏树形菜单处理
    function set_side_tree()
    {
        if(!side.isTree){ return;}
        alert ('执行了tree处理');
    }

    // 当前样式添加
    $.fn.setOn  = function(_class)
    {
        _class = _class || 'on';
        $(this).addClass(_class).siblings().removeClass(_class);
        return this;
    };



    // 执行事件
    $(ThinkAdmin);
})(jQuery);