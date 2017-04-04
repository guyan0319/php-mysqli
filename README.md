一、简介
MysqliExport.php 是PHP语言写的MYSQL数据库类，主要为了方便从不同库导入数据。采用预处理方式，解决了在数据库操作中的特殊字符SQL注入问题。在PHP5.5+版本上运行。
主要功能：
  1. 数据库导入
  2. 数据查询、修改等
  
 二、实例
  
  
        // $data = [
        //     'link' => self::$linkFrom,
        //     'table' => 'a',
        //     'data' => ['name' => '批量'],
        //     'preset' => '?',
        // ];
        // $bind = [
        //     'stmt' => self::insertPrepare($data),
        //     'data' => ['name' => '批量'],
        //     'param' => 's',
        // ];

        // self::bindParam($bind);
        //       $data = [
        //           'link' => self::$linkFrom,
        //           'table' => 'a',
        //           'data' => ['name' => '批量修改1'],
        //           'where' => ['id' => '12'],
        //       ];
        //       $bind = [
        //           'stmt' => self::updatePrepare($data),
        //           // 'data' => ['name' => '批量','id'=>'12'],
        //           'data' =>  $data['data']+$data['where'],
        //           'param' => 'si',
        //       ];
        // $result = self::bindParam($bind);
        //       $data = [
        //           'link' => self::$linkFrom,
        //           'table' => 'a',
        //           'where' => ['id' => '12'],
        //       ];
        //       $bind = [
        //           'stmt' => self::deletePrepare($data),
        //           // 'data' => ['name' => '批量','id'=>'12'],
        //           'data' =>  $data['where'],
        //           'param' => 'i',
        //       ];
        // $result = self::bindParam($bind);

        //插入条
        // $data = [
        //     'link' => self::$linkFrom,
        //     'table' => 'a',
        //     'data' => ['name' => 'dasasdf'],
        //     'param' => 's',
        //     'preset' => '?',
        // ];
        // self::insert($data);
        // $row = self::getOne(self::$linkFrom, "select * from a limit 1");

        // $default = [
        //     'link' => self::$linkFrom,
        //     'table' => 'a',
        //     'where' => ['id=' => 1],
        // ];
        // $total = self::getToTal($default);
        // self::isCheck(self::$linkFrom, 'a', 'id=100');
        
三、需求及Bug反馈方式

  如有用户在使用过程遇到相应的需求或BUG ，在github上进行交流或是PullRequest。
