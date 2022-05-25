<?php

namespace app<namespace>controller;

use app<namespace>model\<model> as <model>Model;
use app<namespace>validate\<model>Validate;
use think\exception\ValidateException;

class <controller> extends Base
{
    /**
    * 获取列表
    */
    public function getList()
    {
        if (request()->isPost()) {

            $limit  = input('post.limit');
            $where = [];

            $<model>Model = new <model>Model();
            $res = $<model>Model->get<model>List($where, $limit);

            return json($this->retJsonFmt($res['code'], $res['msg'], $res['data']));
        }
    }

    /**
    * 添加
    */
    public function add()
    {
        if (request()->isPost()) {

            $param = input('post.');

            // 检验完整性
            $val = new <model>Validate();
            if (!$val->scene('add')->check($param)) {
                return json($this->retJsonFmt(false, $val->getError()));
            }

            $<model>Model = new <model>Model();
            $res = $<model>Model->add<model>($param);

            return json($this->retJsonFmt($res['code'], $res['msg']));
        }
    }

    /**
    * 查询信息
    */
    public function read()
    {
        $id = input('param.<pk>');

        $<model>Model = new <model>Model();
        $res = $<model>Model->get<model>ById($id);

        return json($this->retJsonFmt($res['code'], $res['msg'], $res['data']));
    }

    /**
    * 编辑
    */
    public function edit()
    {
         if (request()->isPost()) {

            $param = input('post.');

            // 检验完整性
            $val = new <model>Validate();
            if (!$val->scene('edit')->check($param)) {
                return json($this->retJsonFmt(false, $val->getError()));
            }

            $<model>Model = new <model>Model();
            $res = $<model>Model->edit<model>($param);

            return json($this->retJsonFmt($res['code'], $res['msg']));  
         }
    }

    /**
    * 删除
    */
    public function del()
    {
        $id = input('param.<pk>');
        if (!$id) {
            return json($this->retJsonFmt(false, '参数错误'));
        }

        $<model>Model = new <model>Model();
        $info = $<model>Model->find($id);
        if(!$info){
            return json($this->retJsonFmt(false,'数据不存在'));
        }
        $info->delete();
        return json($this->retJsonFmt(true));
   }
}
