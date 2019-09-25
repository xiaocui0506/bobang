<?php
namespace base\fmodel;

use think\Model as BaseModel;

class Extmodel extends BaseModel
{
    private   $defpagesize = 20 ;
    private   $fieldsWhite = ['page'=>'','rows'=>'','sort'=>'','rechargeorder'=>''];
    
    /**
     * 默认的属性列表
     */
    public function attributeLabels() { 
        return [] ;
    }
    
    /**
     * 客户端要用到的select编辑和显示使用的数据
     */
    public function getAllFieldsConfigs(){
        return [] ;
    }
    
    /**
     * 普通分页查询ajax用
     * @param unknown $datas  查询条件
     * @return unknown[]
     */
    public function  pagesearch($datas){
        $count = 0 ;
        $rows = [] ;
       
        if (isset($datas['s']))     {      unset($datas['s']); }
        if (isset($datas['page']))  {      $page = intval($datas['page']) ;          unset($datas['page']) ;       }else{          $page = 1 ;      }
        if (isset($datas['rows']))  {      $pagesize = intval($datas['rows']);       unset($datas['rows']) ;       }else{          $pagesize = $this->defpagesize;  }
        if (isset( $datas['sort'] )){      $order = $datas['sort'] ;                 unset($datas['sort']) ;       }else{          $order = 'id' ;       }
        if (isset( $datas['rechargeorder'] )){     $orderascdesc = $datas['rechargeorder'] ;         unset($datas['rechargeorder']) ;      }else{          $orderascdesc = 'desc' ;       }
        
        $begin = ($page-1)* $pagesize ;
        $orderstring = " `$order` $orderascdesc ";
      
        $count = $this->where($datas)->count();
        if ( $count ){
            $rows = $this->where($datas)->limit($begin,$pagesize)->order($orderstring)->select() ;
        }
        
        return ['total'=>$count,'rows'=>$rows];
    }   
    
    
    /**
     * 查询字段过滤
     * @param unknown $request
     * @param unknown $self
     * @param array $other
     * @return unknown[]
     */
    public function fieldsFilter($request,$other=[]){
        $all = array_merge( $this->attributeLabels() , $this->fieldsWhite , $other );
        $datas  = [] ;
        
        if (is_array($request)){
            foreach ($request as $key=>$val){
                if (isset($all[$key])){
                    $datas[$key] = $val ;
                }
            }   
        }        
        return $datas ;        
    }
    
    /**
     * 单条记录详细信息展示使用 progrid
     * @param unknown $model
     * @param array $config
     * @return number[]|unknown[][]
     */
    public function  formatDetail($model,$config=[]){
        if ( empty($model) ) { return [] ;  }
        $rows = [] ;
        $allkeys = $this->attributeLabels() ;
        $count = 0 ; 
      
        foreach ( $allkeys as $key=>$val){
            
            $tmp['name'] = $key ;
            $tmp['cnname'] = $allkeys[$key] ;
            $tmp['value'] = $model->$key ;
            
            if ( isset($config[$key][$tmp['value']]) ) {
                $tmp['value'] = $config[$key][$tmp['value']];
            } 
            
            $count++ ;
            $rows[] = $tmp ;
        }
       
        return ['total'=>$count,'rows'=>$rows] ;
    }
    
    
}