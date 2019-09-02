<?php
/**
  * 闲置添加、展示
  */
namespace app\index\model;

use think\Model;

class Unused extends Model
{
    //
  public function save($data = [], $where = [], $sequence = null)
  {
    return parent::save($data, $where, $sequence); // TODO: Change the autogenerated stub
  }

  /**
    * 添加闲置数据
    */
  public function add(){
    $post_data = input('post.');
    try{
      $validate = new \think\Validate;
      $validate->rule([
        'unus_title|闲置标题' => 'require|chsDash|max:100',
        'unus_type|闲置类别' => 'require|between:1,7',
        'pro_address|处理地址' => 'require',
        'pro_address_xq|处理地址详情' => 'require',
        'generator|生成厂家' => 'require',
        'goods_name|货品名称' => 'require',
        'number|数量单位' => 'require',
        'factory_date|出厂时间' => 'require',
        'tf_price|转让价格' => 'require|number',
        'contacts|联系人' => 'require',
        'phone|手机号' => 'require|/^[1]([3-9])[0-9]{9}$/',
        'push_indu|推送行业' => 'require',
        'push_address|推送地区 ' => 'require',
        'push_time|推送时长' => 'require|between:1,6',
      ]);
      if ($validate->check($post_data)) {
        $post_data['user_id'] = session("?user_id")?session("user_id"):0;
        $post_data['create_time'] = time();
        $post_data['update_time'] = time();
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
