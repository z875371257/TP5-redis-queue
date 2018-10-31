<?php
/**
 * 用户管理
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\common\model\Attachments;

use app\admin\model\AppletCates;

class AppletCate extends Base
{
    public function index()
    {
        $model = new AppletCates();

        $list       = $model->paginate($this->webData['list_rows']);
        $this->assign([
            'list' => $list,
            'total' => $list->total(),
            'page'  => $list->render()
        ]);

        return $this->fetch();
    }


    public function add()
    {
        if ($this->request->isPost()) {

            $resultValidate = $this->validate($this->param, 'AppletCate.add');
            if (true !== $resultValidate) {
                return $this->error($resultValidate);
            }
            $attachment = new Attachments();
           
            $file                    = $attachment->upload('thumb');
            if ($file) {
                $this->param['thumb'] = $file->url;
            }else{
                return $this->error($attachment->getError());
            }

            $result = AppletCates::create($this->param);
            if ($result) {
                return $this->success();
            }
            return $this->error();
        }

        return $this->fetch();
    }


    public function edit()
    {
        $info = AppletCates::get($this->id);
        if ($this->request->isPost()) {
            $resultValidate = $this->validate($this->param, 'AppletCate.edit');
            if (true !== $resultValidate) {
                return $this->error($resultValidate);
            }

            if ($this->request->file('thumb')) {
                $attachment = new Attachments();
                $file       = $attachment->upload('thumb');
                if ($file) {
                    $this->param['thumb'] = $file->url;
                } else {
                    return $this->error($attachment->getError());
                }
            }

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
        $result = AppletCates::destroy(function ($query) use ($id) {
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
        $user         = AppletCates::get($this->id);
        $user->status = $user->status == 1 ? 0 : 1;
        $result       = $user->save();
        if ($result) {
            return $this->success();
        }
        return $this->error();
    }

}