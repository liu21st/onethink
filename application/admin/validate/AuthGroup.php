<?php
/**
 * Created by PhpStorm.
 * User: tang
 * Date: 2016/7/21
 * Time: 15:19
 */
namespace app\admin\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'description' => 'max:25',
    ];
    protected $message = [
        'title.require' => '必须设置用户组标题',
        'description.max' => '描述最多80字符'
    ];
    protected $scene=[
        'add'=>['title' => 'require']
    ];
}
