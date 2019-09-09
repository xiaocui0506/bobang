<?php
/*商业会议 */
namespace app\index\controller;

use app\index\controller\Common;

class Meeting extends Common
{
    //
  public function index(){

    return $this->fetch();
  }
}
