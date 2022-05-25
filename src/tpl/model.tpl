<?php

namespace app<namespace>model;

use think\model;
<SoftDelete>

class <model> extends Model
{
    <UseSoftDelete>
    <SoftDeleteField>
    <SoftDeleteType>

    /**
    * 获取分页列表
    * @param $where
    * @param $limit
    * @return array
    */
    public function get<model>List($where, $limit)
    {
        try {

            $list = $this->where($where)->order('<pk>', 'desc')->hidden([<HiddenField>])->paginate($limit);
        } catch(\Exception $e) {
            return dataReturn(false, $e->getMessage());
        }

        return dataReturn(true, 'success', $list);
    }

    /**
    * 添加信息
    * @param $param
    * @return $array
    */
    public function add<model>($param)
    {
        try {

           // TODO 去重校验

           $param['addtime'] = date('Y-m-d H:i:s');
           $param['edittime'] = date('Y-m-d H:i:s');
           $this->save($param);
        } catch(\Exception $e) {

           return dataReturn(false, $e->getMessage());
        }

        return dataReturn(true, 'success');
    }

    /**
    * 根据id获取信息
    * @param $id
    * @return array
    */
    public function get<model>ById($id)
    {
        try {

            $info = $this->where('<pk>', $id)->hidden([<HiddenField>])->find();
        } catch(\Exception $e) {

            return dataReturn(false, $e->getMessage());
        }
        if (!$info) {
            return dataReturn(false, '数据不存在');
        }
        return dataReturn(true, 'success', $info);
    }

    /**
    * 编辑信息
    * @param $param
    * @return array
    */
    public function edit<model>($param)
    {
        info = $this->find($param['id']);
        if (!$info) {
            return dataReturn(false, '数据不存在');
        }
        try {
            // TODO 去重校验
            $param['edittime'] = date('Y-m-d H:i:s');
            $info->save($param);
        } catch(\Exception $e) {

            return dataReturn(false, $e->getMessage());
        }

        return dataReturn(true, 'success');
    }
}

