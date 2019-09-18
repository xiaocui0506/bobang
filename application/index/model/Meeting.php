<?php

namespace app\index\model;

use think\Model;

class Meeting extends Model
{
    //
  public function save($data = [], $where = [], $sequence = null)
  {
    return parent::save($data, $where, $sequence); // TODO: Change the autogenerated stub
  }

  /*会展发布*/
  public function add(){
    $post_data = input('post.');
    if (!array_key_exists('img',$post_data)){
      $post_data['photo'] = '';
    }else{
      $post_data['photo'] = implode(',',$post_data['img']);
    }
    try{
      $validate = new \think\Validate;
      $validate->rule([
        'meet_title|议会标题' => 'require|chsDash|max:100',
        'meet_type|议会类型' => 'require|between:1,7',
        'sponsor|主办单位' => 'require',
        'meet_addr_p|会展地址' => 'require',
        'meet_addr_c|会展地址' => 'require',
        'meet_addr_t|会展地址' => 'require',
        'meet_addr_xq|地址详情' => 'require',
        'meet_date_s|会展时间(开始)' => 'require',
        'meet_date_e|会展时间(结束)' => 'require',
        'hold_num|举办届数' => 'require|number',
        'hold_date|举办周期' => 'require',
        'contacts|联系人' => 'require',
        'phone|手机号' => 'require|/^[1]([3-9])[0-9]{9}$/',
        'push_indu|推送行业' => 'require',
        'push_addr_p|推送地区 ' => 'require',
        'push_addr_c|推送地区 ' => 'require',
        'push_addr_t|推送地区 ' => 'require',
        'push_time|推送时长' => 'require|between:1,6',
      ]);
      if ($validate->check($post_data)) {
        $post_data['user_id'] = session("?user_id")?session("user_id"):0;
        $post_data['create_time'] = time();
        $post_data['update_time'] = time();
        $res = $this->allowField(true)->save($post_data);
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
