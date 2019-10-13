<?php

//用户修改某个数据
if (!function_exists('modify')){
  function modify($db,$id,$st){
    if ($db){
      if ($id ){
        $st = $st == 1 ? 0 : 1;
        $bool = $db->where(['id'=>$id])->update(['status'=>$st]);
        if ($bool){
          jsonResponse(1,0,'修改成功');
        }else{
          jsonResponse(-1,0,'修改失败');
        }
      }else{
        jsonResponse(-1,0,'缺少参数');
      }
    }else{
      jsonResponse(-1,0,'缺少重要数据');
    }
  }
}


//用户修改某个数据
if (!function_exists('del')){
  function del($db,$id,$isimg = 0){
    if ($db && $id){
        if ($isimg){
          $img = $db::where(['id'=>$id])->value('img');
          if ($img) {
            if (file_exists('./public/uploads/' . $img)) {
              unlink('./public/uploads/' . $img);
            }
          }
        }
        $bool = $db::where(['id'=>$id])->delete();
        if ($bool){
          jsonResponse(1,0,'删除成功');
        }else{
          jsonResponse(-1,0,'删除失败');
        }
    }else{
      jsonResponse(-1,0,'缺少重要数据');
    }
  }
}





