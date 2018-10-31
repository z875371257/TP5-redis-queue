<?php
/**
 * 用户管理
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Queue;
use app\admin\model\Overseas;


class Work extends Base
{

    public function actionWithHelloJob()
    {
        $model = new Overseas();

        $res = $model->where('status', 0)->select();

        $email = [];

        if($res){
            foreach ($res as $key => $value) {
                $email[] = $value['email'];
            }    
        }
        
        // 1.当前任务将由哪个类来负责处理。 
        //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
        $jobHandlerClassName  = 'app\admin\controller\Hello'; 
        // 2.当前任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName       = "helloJobQueue"; 
        // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
        //   ( jobData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对)
        $jobData            = ['subject' => '测试测试测试标题', 'content' => '测试内容'];
        // 4.将该任务推送到消息队列，等待对应的消费者去执行
        $isPushed = Queue::push( $jobHandlerClassName , $jobData , $jobQueueName );   
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if( $isPushed !== false ){  
            echo 'ok';
        }else{
            echo 'error';
        }
    }


}