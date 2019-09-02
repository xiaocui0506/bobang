<?php

namespace app\index\model;

use think\Model;

class Tendering extends Model
{
    //
  public function save($data = [], $where = [], $sequence = null)
  {
    return parent::save($data, $where, $sequence); // TODO: Change the autogenerated stub
  }

  /*   添加招标信息*/
  public function add(){
    $post_data = input('post.');
    try{
      $validate = new \think\Validate;
      $validate->rule([
        'bidding_name|招标项目名称' => 'require|chsDash|max:100',
        'bidding_pro|招标产品' => 'require',
        'enroll_deadline|报名截止时间' => 'require',
        'bidding_deadline|投标截止时间' => 'require',
        'bidding_address|标书获取地点' => 'require',
        'bidding_address_xq|标书获取地点详情' => 'require',
        'contacts|联系人' => 'require',
        'phone|手机号' => 'require|/^[1]([3-9])[0-9]{9}$/',
        'bidding_text|招标正文' => 'require',
        'push_area|推送地区' => 'require',
        'push_indu|推送行业' => 'require',
      ]);
      if ($validate->check($post_data)) {
        $post_data['user_id'] = session("?user_id")?session("user_id"):0;
        $post_data['create_time'] = time();
        $post_data['see_level'] = !isset($post_data['see_level'])?$post_data['see_level']:1;
        $res = $this->save($post_data);
        if ($res)
          jsonResponse(1,$this->id,'成功');
        else
          jsonResponse(-1,'','失败');
      } else {
        jsonResponse(-1, '', $validate->getError());
      } 
    }catch (\Exception $e){
      return jsonResponse(-1,'',$e->getMessage());
    }
  }
}
