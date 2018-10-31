<?php

namespace app\admin\controller;

use app\admin\model\Overseas;

use think\queue\Job;

use email\SendMail;
use app\admin\model\AdminMailLogs;


class Hello {
      
      /**
       * fire方法是消息队列默认调用的方法
       * @param Job            $job      当前的任务对象
       * @param array|mixed    $data     发布任务时自定义的数据
       */
      public function fire(Job $job,$data){
        

          $isJobDone = $this->doHelloJob($data);
        
          if ($isJobDone) {
              //如果任务执行成功， 记得删除任务
              $job->delete();
              print("<info>Hello Job has been done and deleted"."</info>\n");
          } else {
              if ($job->attempts() > 3) {
                  //通过这个方法可以检查这个任务已经重试了几次了
                  print("<warn>Hello Job has been retried more than 3 times!"."</warn>\n");
  				        $job->delete();
                  // 也可以重新发布这个任务
                  //print("<info>Hello Job will be availabe again after 2s."."</info>\n");
                  //$job->release(2); //$delay为延迟时间，表示该任务延迟2秒后再执行
              }
          }
      }

      /**
       * 根据消息中的数据进行实际的业务处理
       * @param array|mixed    $data     发布任务时自定义的数据
       * @return boolean                 任务执行的结果
       */
      private function doHelloJob($data) {

  		  // 根据消息中的数据进行实际的业务处理...
        // $email = array_shift($data['email']);

        $result = SendMail::send_email('875371257@qq.com', $data['subject'], $data['content']);
        print_r($result);
        if( $result['error'] == 1)
        {
          print_r('发送失败');
        } else {
          print_r('发送成功');
        }
        // if( $result['error'] == 1 ){

        //     AdminMailLogs::create($param);
        //     return false;
        // }
        // AdminMailLogs::create($param);
        // return true;

        // print_r($email);

        // $model = new Overseas();

        // foreach ($data['email'] as $k => $v) {

        // }
        
        return true;
      }


  }