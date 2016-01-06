<?php
/**
 * author: talent4636@gmail.com
 *   date: 2016/1/5 16:37
 */

require_once "config.php";

class db{

    public $connect;

    public function __construct(){
        $conn = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)){
            die('something wrong with mysql');
        }else {
            $this->connect = $conn;
//            mysql_query("SET NAMES UTF8");
//            mysql_query("set character_set_client=utf8");
//            mysql_query("set character_set_results=utf8");
        }
    }

    //获取一数据
    public function getRow($type_id = '',$joke_id = ''){
        if($joke_id){
            $where = " `id` = {$joke_id}";
            $randomNo = rand(1,(int)$joke_id);
        }else{
            if($type_id){
                $where = " `joke_type` = {$type_id}";
                $typeInfo = $this->getType($type_id);
                $randomNo = rand(1,(int)$typeInfo['total']);
            }else{
                //这种？直接从总数中random一条出来
                $randomNo = rand(1,25176);
            }
        }

        if($joke_id){
            $sql = 'SELECT * FROM '.DB_NAME.'.joke WHERE '. $where;
        }else{
            if(isset($where)){
                $sql = 'SELECT * FROM '.DB_NAME.'.joke WHERE '. $where. ' LIMIT '.$randomNo.',1 ';
            }else{
                $sql = 'SELECT * FROM '.DB_NAME.'.joke LIMIT '.$randomNo.',1 ';
            }

        }

        $sql_result = mysql_query($sql,$this->connect);
        if(!is_resource($sql_result)) return ;
        $j = 0;
        while ($row = mysql_fetch_array($sql_result)) {
            $i = 0;
            foreach ($row as $key => $value) {
                $i++;
                if ($i%2 == 0) {
                    $data[$j][$key] = $value;
                }
            }
            $j = $j+1;
            unset($row);
        }
        mysql_free_result($sql_result);
        if($data[0]['joke_type']){
            $typeArr = $this->getType($data[0]['joke_type']);
            $data[0]['joke_type'] = $typeArr['name'];
        }
        return $data[0];

    }

    public function getRowByType($type_id){
        return $this->getRow($type_id);
    }

    public function getRowByJokeId($joke_id){
        return $this->getRow('',$joke_id);
    }

    public function getType($type_id=''){
        $type_array = $this->typeArray();
        if(!$type_id){
            return $type_array;
        }else{
            return $type_array[$type_id];
        }
    }

    // 1爆笑男女 2社会 3冷笑话 4校园 5儿童 6 夫妻 7综合 9家庭 10动物 11职场 12短信笑话 14幽默笑话 21愚人 23明间 36军事
    public function typeArray(){
        return array(
            '1' => array('name' => '爆笑男女', 'total' => 4381),
            '2' => array('name' => '社会', 'total' => 2289),
            '3' => array('name' => '冷笑话', 'total' => 3119),
            '4' => array('name' => '校园', 'total' => 2402),
            '5' => array('name' => '儿童', 'total' => 1812),
            '6' => array('name' => '夫妻', 'total' => 1668),
            '7' => array('name' => '综合', 'total' => 1883),
            '9' => array('name' => '家庭', 'total' => 1108),
            '10' => array('name' => '动物', 'total' => 764),
            '11' => array('name' => '职场', 'total' => 987),
            '12' => array('name' => '短信笑话', 'total' => 2236),
            '14' => array('name' => '幽默笑话', 'total' => 1028),
            '21' => array('name' => '愚人', 'total' => 398),
            '22' => array('name' => '其他', 'total' => 654),
            '23' => array('name' => '民间', 'total' => 332),
            '36' => array('name' => '军事', 'total' => 115),
        );
    }

}

//调试代码
//$db = new db();
//$row = $db->getRow();
//print_r($row);