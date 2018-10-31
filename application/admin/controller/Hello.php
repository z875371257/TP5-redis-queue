<?php

namespace app\admin\controller;

use app\admin\model\Overseas;
use app\admin\model\AdminMailLogs;

use think\queue\Job;
use think\Session;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
                  $job->release(2); //$delay为延迟时间，表示该任务延迟2秒后再执行
              }
          }
      }

      /**
       * 根据消息中的数据进行实际的业务处理
       * @param array|mixed    $data     发布任务时自定义的数据
       * @return boolean                 任务执行的结果
       */
      private function doHelloJob($data) {
        
        // $res['address'] = $data['email'];
        $res['subject'] = $data['subject'];
        $res['content'] = $data['content'];

        while ( true ) {
            $overs = Overseas::where('status', 0)->order('id DESC')->find();
            if(!$overs){
                return true;
            }
            $send = $this->send_email( $overs['email'], $res['subject'], $res['content'] );
            $overs['status']  = '2';

            $res['address'] = $overs['email'];
            $res['status'] = 1;
            $overs->save();
            AdminMailLogs::create($res);
        }

        // $result = $this->send_email('875371257@qq.com', $data['subject'], $data['content']);

       

        // if( $result )
        // {
        //   $res['status'] = 1;
        //   AdminMailLogs::create($res);
        //   return true;
        // } else {
        //   $res['status'] = 0;
        //   AdminMailLogs::create($res);
        //   return false;
        // }




      }

      public function send_email($to, $title, $content)
      {

        $mail = new phpmailer;
        try {

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->CharSet="UTF-8";
            $mail->Host = 'smtp.exmail.qq.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'Chris@cspplaza.com';                 // SMTP username
            $mail->Password = 'Plaza201805';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            $mail->AddReplyTo("Chris@cspplaza.com","Chris@cspplaza.com");  //回复地址
            $mail->setFrom('Chris@cspplaza.com');   //发信人
            $mail->FromName = "zqshine";
            $mail->addAddress($to);     // 收件人
            //Content
            $mail->Subject = $title;    //邮件标题
            $mail->Body    = $content;  //邮箱内容    
            $mail->isHTML(true);       // Set email format to HTML
        
            $res = $mail->send();

            if($res){
              return 1;
            }else{
              return 0;
            }

        } catch (Exception $e) {
            return 0;
        }
      }



  }