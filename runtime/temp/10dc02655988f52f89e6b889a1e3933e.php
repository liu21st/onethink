<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:54:"D:\www\onethink/application/home\view\index\index.html";i:1468541095;}*/ ?>
<extend name="Base/common"/>

<block name="header">
    <header class="jumbotron subhead" id="overview">
        <div class="container">
            <h2>源自相同起点，演绎不同精彩！</h2>
            <p class="lead"></p>
        </div>
    </header>
</block>

<block name="side">
<!-- 左侧 nav
================================================== -->
    <div class="span3 bs-docs-sidebar">
        <ul class="nav nav-list bs-docs-sidenav">
            <?php echo W('Category/lists', array(1, true)); ?>
        </ul>
    </div>
</block>

<block name="body">
    <div class="span9">
        <!-- Contents
        ================================================== -->
        <section id="contents">
            <article:list name="article" category="1" child="true">
                <div class="">
                    <h3><a href="<?php echo U('Article/detail?id='.$article['id']); ?>"><?php echo $article['title']; ?></a></h3>
                </div>
                <div>
                    <p class="lead"><?php echo $article['description']; ?></p>
                </div>
                <div>
                    <span><a href="<?php echo U('Article/detail?id='.$article['id']); ?>">查看全文</a></span>
                    <span class="pull-right">
                        <span class="author"><?php echo get_username($article['uid']); ?></span>
                        <span>于 <?php echo date('Y-m-d H:i',$article['create_time']); ?></span> 发表在 <span>
                        <a href="<?php echo U('Article/lists?category='.get_category_name($article['category_id'])); ?>"><?php echo get_category_title($article['category_id']); ?></a></span> ( 阅读：<?php echo $article['view']; ?> )
                    </span>
                </div>
                <hr/>
            </article:list>

        </section>
    </div>
</block>