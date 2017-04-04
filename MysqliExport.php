<?php

//设置编码
header('content-type:text/html;charset=utf-8');
/**
 *  mysqli  mysql数据库导出导入等相关操作
 *  @author 郭志强
 *  @date 2017-03-30
 */
class MysqliExport
{

    /**
     *  数据库配置
     * @var string
     * @access private
     */
    private static $dbConfigFrom = ['host' => '127.0.0.1', 'user' => 'root', 'passwd' => '123456', 'dbname' => 'demo'];

    /**
     *  数据库配置
     * @var string
     * @access private
     */
    private static $dbConfigTo = ['host' => '127.0.0.1', 'user' => 'root', 'passwd' => '123456', 'dbname' => 'demo'];

    /**
     * 暂无说明
     * @var string
     * @access private
     */
    private static $linkFrom;

    /**
     * 暂无说明
     * @var string
     * @access private
     */
    private static $linkTo;

    /**
     * 构造函数
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access public
     */
    public function __construct()
    {
        self::$linkFrom = $this->dbConnect(self::$dbConfigFrom);
        self::$linkTo = $this->dbConnect(self::$dbConfigTo);

        //设置字符编码
        self::$linkFrom->set_charset('utf8');
        self::$linkTo->set_charset('utf8');
    }

    /**
     *  实例
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access public
     */
    public static function updateOrder()
    {

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

       	$default = [
            'link' =>self::$linkFrom,
            'table' => 'a',
            'where' => ['id='=>1],
        ];
        $total = self::getToTal($default);
        echo "<pre>";
		var_dump( $total );
        echo "</pre>";
		exit;
        // self::isCheck(self::$linkFrom, 'a', 'id=100');

    }

    /**
     * 预编译insert SQL语句
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private static function insertPrepare(array $data)
    {
        $default = [
            'link' => '',
            'table' => '',
            'data' => [],
            'preset' => '?',
        ];
        $data = array_merge($default, $data);
        $sql = "INSERT INTO {$data['table']} (" . implode(',', array_keys($data['data'])) . ") VALUE ({$data['preset']})";
        return $data['link']->prepare($sql);
    }

    /**
     * 预编译update SQL语句
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private static function updatePrepare(array $data)
    {
        $default = [
            'link' => '',
            'table' => '',
            'where' => [],
            'data' => [],
        ];
        $data = array_merge($default, $data);
        $data['data'] = count($data['data']) > 1 ? implode('=?,', $data['data']) : key($data['data']) . '=?';
        $where = '';
        if (count($data['where'])) {
            foreach ($data['where'] as $key=>$value) {
                $where .= " and {$key}=?";
            }

        }
        $sql = "UPDATE  {$data['table']} SET {$data['data']} where 1=1 {$where}";
        return $data['link']->prepare($sql);
    }

    /**
     * 预编译DELETE SQL语句
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private static function deletePrepare(array $data)
    {
        $default = [
            'link' => '',
            'table' => '',
            'where' => [],
        ];
        $data = array_merge($default, $data);
        $where = '';
        if (count($data['where'])) {
            foreach ($data['where'] as $key=>$value) {
                $where .= " and {$key}=?";
            }
        }
        $sql = "DELETE FROM   {$data['table']} where 1=1 {$where}";
        return $data['link']->prepare($sql);
    }

    /**
     * 绑定参数(需要插入的数据)，并执行
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private static function bindParam(array $data)
    {
        $default = [
            'stmt' => '',
            'data' => [],
            'param' => '',
        ];
        $data = array_merge($default, $data);
        call_user_func_array(array($data['stmt'], 'bind_param'), self::quoteValues(array_values(array($data['param']) + $data['data'])));
        $result = $data['stmt']->execute();
		$data['stmt']->close();
		return $result;
    }

    /**
     * 插入单条 预处理方式
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @param  $table:表名, $data:数组,  $param:表示绑定的值类型 d:Decimal i:Integer b:Blob (二进制类型) s:所有其它类型  preset 预置符合 ?,?,link
     * @access private
     */
    private static function insert(array $data)
    {
        $default = [
            'link' => '',
            'table' => '',
            'data' => [],
            'param' => '',
            'preset' => '?',
        ];
        $data = array_merge($default, $data);
        $sql = "INSERT INTO {$data['table']} (" . implode(',', array_keys($data['data'])) . ") VALUE ({$data['preset']})";
        $stmt = $data['link']->prepare($sql);
        call_user_func_array(array($stmt, 'bind_param'), self::quoteValues(array_values(array($data['param']) + $data['data'])));
        $result =  $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * 暂无说明
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */

    private static function quoteValues(array $arr)
    {
        $refs = array();
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }

    /**
     * 删除
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private static function delete ( $link ,$sql )
    {   
    	if ($link)
    	{
    		return $link->query($sql);
    	}else{
    		die("link Error! ");
    	}
       
    }

    /**
     * 获取所有数据
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private function getAll($link, $sql)
    {
        $list = [];
        if ($result = $link->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }
    /**
     * 获取总数
     *
     * @author 郭志强
     * @return voids
     * @throws Exception
     * @param   link ：mysql link ,table：表名 , where：where条件,如 ['id ='=>1]
     * @access private
     */
    private static function getToTal ( array $data)
    {
       	$default = [
            'link' => '',
            'table' => '',
            'where' => [],
        ];
        $data = array_merge($default, $data);
        $where = '';
        foreach ($data['where'] as $key => $value)
        {
        	$where .=" and {$key}'{$value}' ";
        }
        $sql = "SELECT COUNT(*) AS total FROM {$data['table']} WHERE 1=1 {$where}";
 		$result = $data['link']->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    /**
     * 判断某条数据是否存在
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @param $link  $where where条件
     * @access private
     */
    private static function isCheck($link, $table, $where)
    {
        if ($where) {
            $where = " where {$where}";
        }
        $sql = "select 1 from {$table} $where limit 1 ";
        $result = $link->query($sql);
        return $result->fetch_assoc();
    }

    /**
     * 获取一条数据
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private static function getOne($link, $sql)
    {
        if ($result = $link->query($sql)) {
            return $result->fetch_assoc();
        }
    }

    /**
     * 暂无说明
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access private
     */
    private static function dbConnect($dbconfig)
    {
        $link = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['passwd'], $dbconfig['dbname']);
        if ($link->connect_error) {
            die('Connect Error (' . $link->connect_errno . ') ' . $link->connect_error);
        }
        return $link;
    }

    /**
     * 析构函数
     *
     * @author 郭志强
     * @return void
     * @throws Exception
     * @access public
     */
    public function __destruct()
    {

    }
}

$updateOrder = new MysqliExport();
$updateOrder->updateOrder();
