<?php
namespace app\addons\digg;

use app\common\controller\Addon;

/**
 * 顶一下，踩一下插件插件
 *
 * @author thinkphp
 */
class Digg extends Addon
{

    public $info = array(
        'name' => 'Digg',
        'title' => 'Digg插件',
        'description' => '网上通用的文章顶一下，踩一下插件（不支持后台作弊修改数据）。',
        'status' => 1,
        'author' => 'thinkphp',
        'version' => '0.3'
    );

    public function install()
    {
        $db_prefix = config('DB_PREFIX');
        $table_name = "{$db_prefix}digg";
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `{$table_name}` (
  `document_id` int(10) unsigned NOT NULL,
  `good` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞数',
  `bad` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `uids` longtext NOT NULL COMMENT '投过票的用户id 字符合集 id1,id2,',
  PRIMARY KEY (`document_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL;
        model()->execute($sql);
        if (count(db()->query("SHOW TABLES LIKE '{$table_name}'")) != 1) {
            session('addons_install_error', ',digg表未创建成功，请手动检查插件中的sql，修复后重新安装');
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        $db_prefix = config('DB_PREFIX');
        $sql = "DROP TABLE IF EXISTS `{$db_prefix}digg`;";
        model()->execute($sql);
        return true;
    }
    
    // 实现的documentDetailAfter钩子方法
    public function documentDetailAfter($param)
    {
        $vote = db('Digg')->find($param['id']);
        if (! $vote) {
            db('Digg')->add(array(
                'document_id' => $param['id'],
                'good' => 0,
                'bad' => 0,
                'create_time' => time(),
                'uids' => ','
            ));
            $vote = db('Digg')->find($param['id']);
        }
        $this->assign('addons_config', $this->getConfig());
        $this->assign('addons_vote_record', $vote);
        return $this->fetch('vote');
    }
}
