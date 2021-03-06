<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用名称
    'app_name'               => '',
    // 应用地址
    'app_host'               => '',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空模块名
    'empty_module'           => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法前缀
    'use_action_prefix'      => false,
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // HTTPS代理标识
    'https_agent_name'       => '',
    // IP代理获取标识
    'http_agent_ip'          => 'X-REAL-IP',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由延迟解析
    'url_lazy_route'         => false,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 合并路由规则
    'route_rule_merge'       => false,
    // 路由是否完全匹配
    'route_complete_match'   => true,
    // 使用注解路由
    'route_annotation'       => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],
    // 是否开启路由缓存
    'route_check_cache'      => false,
    // 路由缓存的Key自定义设置（闭包），默认为当前URL和请求类型的md5
    'route_check_cache_key'  => '',
    // 路由缓存类型及参数
    'route_cache_option'     => [],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => Env::get('think_path') . 'tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl'    => Env::get('think_path') . 'tpl/dispatch_jump.tpl',

    // 异常页面的模板文件
    'exception_tmpl'         => Env::get('think_path') . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',


  // 企业招标
    'tendtype' => [1=>'货物招标0',2=>'货物招标1',3=>'货物招标2',4=>'货物招标3',5=>'货物招标4',6=>'货物招标5',7=>'货物招标6'],
//  求购类型
    'ToType' => [1=>'货物招标0',2=>'货物招标1',3=>'货物招标2',4=>'货物招标3',5=>'货物招标4',6=>'货物招标5',7=>'货物招标6'],

  // 学历列表
  'education' => [1=>'中专以下',2=>'中专',3=>'大专',4=>'本科',5=>'研究生',6=>'硕士',7=>'博士以上'],
  // 性别
  'gender'=>[1=>'男',2=>'女'],
  //工作经验
  'hands' => [1=>'1年以下',2=>'1-3年',3=>'3-5年',4=>'5-8年',5=>'8-10年',6=>'10年以上'],
  // 期望薪资
  'salary'  => [1=>'2k以下',2=>'2k-4k',3=>'4k-6k',4=>'6k-8k',5=>'8k-10k',6=>'10k以上'],
  //发布时长
  'release_time' => [1=>'1个月',2=>'2个月',3=>'3个月',4=>'4个月',5=>'5个月',6=>'6个月'],
  //会展类型
  'expoType'  =>  [1=>'数码电子和电气展',2=>'数码展',3=>'电子展',4=>'电器展'],
  //会议类型
  'meetType'  =>  [1=>'数码电子和电气展',2=>'数码展',3=>'电子展',4=>'电器展'],
  //闲置类别
  'unusedType'  =>  [1=>'闲置设备',2=>'闲置材料',3=>'积压库存',4=>'电器',5=>'手工类',6=>'鞋帽',7=>'首饰'],
    // 新旧程度
  'degree' =>[1=>'全新',2=>'九成新',3=>'八成新',4=>'七成新',5=>'六成新',6=>'五成新',7=>'五成新以下'],

  /* 转让的参数*/
  // 公司类别
  'cor_type'  =>  [1=>'民营企业',2=>'',3=>'',4=>'',5=>'',6=>'',7=>''],
  // 公司性质
  'cor_nature'  =>  [1=>'制造型企业',2=>'',3=>'',4=>'',5=>'',6=>'',7=>''],
  ///  转让发式
  'make_mode'  =>  [1=>'整体转让',2=>'',3=>'',4=>'',5=>'',6=>'',7=>''],

  /*招聘的参数*/
  // 工作性质
  'work_na'=> [1=>'全职',2=>'兼职',3=>'实习',4=>'校园'],
  // Bright 职位亮点
    'bright' => [1=>'五险一金',2=>'年底双薪',3=>'绩效奖金',4=>'年终分红',5=>'股票期权',6=>'加班补助',7=>'全勤奖',8=>'包吃',9=>'包住',10=>'交通补助',11=>'餐补',12=>'房补',13=>'通讯补助',14=>'采暖补贴',15=>'带薪年假',16=>'弹性工作',17=>'补充医疗保险',18=>'定期体检',19=>'婚假',20=>'陪产假',21=>'丧假'],


];
