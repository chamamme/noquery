<?php
namespace  Layers;
/**
 * User: Klaus
 * Date: 9/18/2017
 * Time: 1:55 PM
 * @property string where
 */
class Tablet
{

    private $db;
    private static $_instance;
    private  $sql;
    private  $table;
    private  $limit;
    private  $offset;
    private  $prepared;
    private  $result;
    private  $params = [];

    public function __construct($config = null)
    {
        $this->init($config);
    }
    public static function getInstance ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * @param $config
     * @return string
     */
    private function init($config){
        #This is the driver you have selected to use
        try{
            $db = ADONewConnection($config['driver']);
            $db->debug = $config['debug'];
            $db->bulkBind = true;
            $db->bulkBind = true;
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $db->connect($config['server'], $config['username'], $config['password'], $config['database']);
            #These are the parameters required to connect to the database see connect()
            if($db->isConnected()){
                $this->db = $db;
            }else{
                die("Sorry an error occurred ");
                $this->db = false;
            }
        }catch (Exception $e){
            die("Sorry an error occured {$e->getMessage()}");
        }


    }

    public function table ($table){
        $this->table = $table;
        return $this;
    }

    /**
     * Sets the select query
     * @param array $columns
     * @return string
     */
    public  function select (array $columns=[]){
        if($columns){
            $columns = implode(',',$columns);
        }else{
            $columns = "*";
        }
        $table = $this->table;
        $sql = "SELECT {$columns} FROM {$table} ";
        $this->sql = $sql;
        return $this;
    }

    /**
     * Adds WHERE condition to the query
     * @param array $conditions
     * @return $this
     */
    public  function where (array $conditions) {
        #Ceheck if there is where already in the sql statement;
        if($conditions){
            $contains_where = stripos($this->sql," WHERE ");
            $conditions = implode(' AND ',$conditions);
//            die($conditions);
            $this->where = $conditions;
        }else{
            throw new Exception("Conditions variable must be  set and must be an array");
        }
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." AND {$conditions}";
        }
        $this->sql = $sql;

        return $this;
    }

    /**
     * Adds WHERE IN condition to the query
     * @param array $conditions
     * @return $this
     */
    public  function whereIn (array $conditions) {
        $contains_where = stripos($this->sql," WHERE ");
        if($conditions){
            $array=[];
            foreach ($conditions as $column => $value){
                if(is_array($value)){
                    $value = implode("','",$value);
                }
                $value ="'{$value}'";
                $array[] =" {$column} IN ({$value}) ";
            }

            $conditions = implode(' AND ',$array);
//            die($conditions);
            $this->where = $conditions;
        }else{
            throw new Exception("Conditions variable must be  set and must be an array");
        }
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." AND {$conditions}";
        }
        $this->sql = $sql;
        return $this;
    }

    /**
     * @param null $offset
     * @param null $limit
     * @return mixed
     */
    public function get($limit=null, $offset=null){

        if($limit){
            $this->limit = intval($limit);
            $this->sql = "{$this->sql} LIMIT {$this->limit}";
        }

        if($offset){
            $this->offset = intval($offset);
            $this->sql = "{$this->sql} OFFSET {$this->offset}";
        }

        $this->prepared = $this->db->prepare($this->sql);
        $this->result = $this->db->getAll($this->prepared,$this->params);
        return $this->result;
    }

    /**
     * @param $limit
     */
    public function limit($limit){
        $this->limit = intval($limit);
    }
    public function offset($offset){
        $this->offset = intval($offset);
    }

    public function update(array $args){

//        $columns_array = array_keys($args);
//        $values_array = array_values($args);
//        #implode to strings
//        $columns = implode(",",$columns_array);
        $values = [];
        foreach ($args as $key=>$value){
            $values[] = "{$key}='{$value}'";
        }

        $values = implode(",",$values);

        $this->sql = "UPDATE {$this->table} SET {$values} ";

        return $this;
    }

    /**
     * Executes
     * @return mixed
     */
    public function go(){
        $this->prepared = $this->db->prepare($this->sql);
        $this->result = $this->db->execute($this->prepared,$this->params);
        return $this->result;
    }
    public function toSql(){
        return $this->sql;
    }
}
