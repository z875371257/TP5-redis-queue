<?php
/**
 * 用户管理
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\Overseas;

class Email extends Base
{
    public function index($keywords = '', $page = 1)
    {   


        $map = [];
        if ($keywords) {
            $map['company|uname|email|tel'] = ['like', "%{$keywords}%"];
        }

        $list = Overseas::where($map)->paginate($this->webData['list_rows'], false, ['page' => $page, 'query' => ['keywords' => $keywords ]]);
   

        return $this->fetch('index', ['list' => $list, 'keywords' => $keywords, 'page' => $list->render(), 'total' => $list->total() ]);
    }


    public function add()
    {
        if ($this->request->isPost()) {

            $result = Overseas::create($this->param);
            if ($result) {
                return $this->success();
            }
            return $this->error();
        }

        return $this->fetch();
    }


    public function edit()
    {
        $info = Overseas::get($this->id);
        if ($this->request->isPost()) {

            if (false !== $info->save($this->param)) {
                return $this->success();
            }
            return $this->error();
        }

        $this->assign([
            'info'       => $info,
        ]);
        return $this->fetch('add');
    }


    public function del()
    {

        $id     = $this->id;
        $result = Overseas::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });
        if ($result) {
            return $this->deleteSuccess();
        }
        return $this->error('删除失败');
    }

    //启用/禁用
    public function disable()
    {
        $user         = Overseas::get($this->id);
        $user->status = $user->status == 1 ? 0 : 1;
        $result       = $user->save();
        if ($result) {
            return $this->success();
        }
        return $this->error();
    }

}