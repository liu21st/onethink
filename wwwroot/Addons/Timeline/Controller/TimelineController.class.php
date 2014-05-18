<?php

namespace Addons\Timeline\Controller;
use Home\Controller\AddonsController;

class TimelineController extends AddonsController{
	public function json(){
        $list = D('Addons://Timeline/Timeline')->select();
        foreach ($list as $key => $value) {
            $list[$key]['headline'] = $value['title'];
            $list[$key]['asset'] = array(
                'caption'=>$value['media_title'],
                'credit'=>$value['author'],
                'media'=>get_cover($value['cover_id'], 'path')
            );
        }

        $info = array(
            'timeline'=>array(
                'headline'=>'杨维杰毕业后的故事',
                'type'=>'default',
                'startDate'=>2011,
                'text'=>'<p>毕业后，我的足迹.................................</p>',
                'asset'=>array(
                    'media'=>'https://1.gravatar.com/avatar/84f6e5f94665151b559d1c5d65d80739?d=https%3A%2F%2Fidenticons.github.com%2Ff5cc5ceb9554674acefe0a2f8da49549.png&r=x&s=60',
                    'credit'=>"技术党's博主",
                    'caption'=>'Jay(杨维杰)QQ:917647288'
                )
            )
        );
        $info['timeline']['date'] = $list;
    	exit(json_encode($info));
	}
}
