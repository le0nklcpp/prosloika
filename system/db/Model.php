<?php
namespace system\db;
use \ArrayObject;
use \PDO;
use \Exception;
/*
 * Primitive ActiveRecord object
 * IMPORTANT NOTICE:if you need to use fields with reserved names in your database: q, tableName, db, primaryKey, replace, allowedFields, requiredFields
 * You need to call model constructor with noprops set to true, BUT that will also disable accessing model fields as class props.
 * see Model::__construct for details
 *
 * Create child class and specify model fields to work with and their validation rules in allowedFields and requiredFields properties
 * This class allows you to access model fields as both object fields and array fields
 * This code will work well:
 * $model->password = $request->body['password'];
 * $model['login'] = $request->body['login'];
 * $model->save();
 * Specify user-input fields through
 * In your controller use asArray method to send json response like that
 * return ['code'=>'200 OK','message' => $model->asArray()]
 * If you created a new model instance and want to update the old one, 
 * you need to set $this->replace manually, or use select() function to create QueryBuilder class 
 * to then load query result to itself
 * Or just specify $oldcache argument in $this->update
 */
class Model extends ArrayObject{
    /**
     * Quotation mark. If a database connection has mysql driver it is backquote, double quote otherwise
     */
    public $q='"';
    /**
     * Table name in your database 
     */
    public $tableName;
    /**
     * PDO object @link https://www.php.net/manual/ru/book.pdo.php
     */
    public $db;
    public $primaryKey = null;
    /**
     * Indicates whether to use UPDATE command or INSERT command in save() function
     * Should be set to true manually or if a select function is called
     */
    public $replace = false;
    /**
     * @array($key => $filter) where $type is one of the following: 'int','float','float_strict','string','any','array','array_asoc','array_strict','date','hex','json','string:[insert length]'
     * Fields that can be set from constructor and their validation rules
     */
    public $allowedFields = [];
    /*
     * Fields that can not be empty or set to null
     */
    public $requiredFields = [];
    //private $validationRules;
    /**
     * Loads model from array
     * @param $array associative array to load values from. Note that 
     * the list of allowed fields is set in $model->allowedFields 
     */
    public function load($array)
    {
        foreach($this->allowedFields as $field => $val)
        {
            if((!($keyfound = array_key_exists($field,$array))||$array[$field]==null))
            {
                if(in_array($field,$this->requiredFields))
                    throw new Exception('Model validation failed: required field '.$field.' expected, but was not sent by client');
                if($keyfound)$this[$field] = null;
                continue;
            }
            if(strpos($val,'string:')===0)
            {
                $len = substr($val,7);
                // for PHP<8 change to this:
                //$len = substr($this[$field],7,strlen($this[$field])-7);
                if(strlen($array[$field])>intval($len))
                {
                    throw new Exception(\system\input\escapeInput::escape('Model validation failed for '.$field.': passed string is too long. Passed value:'.$array[$field]),\system\input\escapeInput::ESCAPE_PHP);
                }
                $this[$field] = strval($array[$field]);
            }
            else
            { // we want strict typization
                $m = $array[$field];
                $this[$field] = match($val){
                'unsigned int' => (is_int($m)&&($m>=0))?intval($m):null,
                'int' => is_int($m)?intval($m):null,
                'bool' => is_bool($m)?boolval($m):null,
                'json' => json_validate($m)?$m:null,
                'float' => is_float($m)?floatval($m):null,
                'float_strict' => is_float($m)&&!is_int($m)?floatval($m):null,
                'string' => is_string($m)?strval($m):null,
                'array' => is_array($m)?$m:$this->convertJSONFieldToArray($m,$val),
                'array_asoc' => array_is_list($m)?$m:$this->convertJSONFieldToArray($m,$val),
                'array_strict' => (is_array($m)&&!array_is_list($m))?$m:$this->convertJSONFieldToArray($m,$val),
                'date' => strtotime($m)?$m:null,
                'hex' => (trim($m, '0..9A..Fa..f') == '')?$m:null,
                'any' => $m,
                default => null};
                if($this[$field]===null)throw new Exception(\system\input\escapeInput::escape('Model validation failed for '.$val.' '.$field.'. Passed value:'.$array[$field],\system\input\escapeInput::ESCAPE_PHP));
            }
        }
        return $this;
    }
    /**
     * Class constructor
     * If your model includes fields that have the same names as this class standard fields, i.e. $model->q, $model->allowedFields
     * You can override the class constructor and call parent::__construct(array:$array,noprops:true), so that user input will not overwrite
     * Model's essential values, however, you will not be able to access model fields as class fields, only as array items.
     * I.e. without noprops set you can access model fields like that:
     * $model->date = date('Y-m-dTH:i:s');
     * $model['date'] = date('Y-m-dTH:i:s');
     * Both cases will work fine
     * With noprops only this will work:
     * $model['date'] = date('Y-m-dTH:i:s')
     * @param ?array $array initial array, which will be loaded through $this->load function
     * @param noprops set to true to disable access to model fields as class fields(model fields can be accessed only by array keys) 
     *
     */
    public function __construct(?array $array=array(),bool $noprops=false)
    {
        if($array==null)$array = array();
        parent::__construct(array(), ($noprops?0:ArrayObject::STD_PROP_LIST)|ArrayObject::ARRAY_AS_PROPS);
        if(!empty($array))$this->load($array);
        $this->initDb();
    }
    /**
     * This function should set $model->db variable in your model class
     * Also call parent::initDb to check if a database is MySQL to set attribute names escaping
     * @return $model->db or null if it is not initialized(it should never happen)
     * If you are using MySQL you should call parent::initDb after setting $this->db to set quotation symbol in your model
     */
    public function initDb()
    {
        if($this->db->getAttribute(PDO::ATTR_DRIVER_NAME)=='mysql')$this->q = '`';
    }
    /**
     * @return array of fields that are specified in $this->allowedFields
     */
    public function asArray()
    {
        return array_intersect_key($this->getArrayCopy(),$this->allowedFields);
    }
    //Escaped using $db->prepare
    /**
     * Prepares and executes SQL INSERT OR UPDATE action with the following type:
     * (ACTION) INTO (TABLE) (arg1,arg2,arg3) VALUES (arg1,arg2,arg3)
     * Arguments are read from allowedFields field. They are escaped using PDO::prepare method
     * @return true on success, false on error
     */
    public function performAction(string $action='INSERT'):bool
    {
        // INSERT INTO "tableName" (
        $queryString = $action.' INTO '.$this->q.$this->tableName.$this->q.' ('
            .$this->q; // Last concatenation is to quote the first passed argument
        $parmarray = array();
        $arraycopy = $this->asArray();
        $parmstr = '';
        $first = true;
        foreach($this->allowedFields as $k => $v)
        {
            if($first)$first = false;
            else
            {
                $queryString .= ', '.$this->q;
                $parmstr .= ', ';
            }
            $queryString .= $k.$this->q;
            $parmstr .= ':'.$k;
            $parmarray[$k] = array_key_exists($k,$arraycopy)?$this->convertToDBField($arraycopy[$k]):null;
        }
        // INSERT INTO "tableName" ("a","b") VALUES (:a,:b)
        $queryString .= ') VALUES ('.$parmstr.')';
        $st = $this->db->prepare($queryString);
        foreach($parmarray as $key => $val)
        {
            $st->bindValue(':'.$key,$val,$this->PDOType($k));
        }
        $st->execute();
        return $this->isUpdateSuccessful($st);
    }
    /**
     * Performs SQL UPDATE statement
     * @param $oldcache associative array with old table attributes or primary key or model or null if you have your current primary key set
     * This function is safe from injections unless you use simplified chinese symbols in your $model->allowedFields
     * Yet it is generally not good idea to pass user input to this function.
     * @return true on success, false on error.
     */
    public function update(mixed $oldcache=null):bool
    {
        // Filter any unsafe user input
        if(is_object($oldcache))$oldcache = $oldcache->asArray();
        else if(is_array($oldcache))$oldcache = array_intersect_key($oldcache,$this->allowedFields);// allow only permitted fields
        else if(!$this->primaryKey)
            throw new Exception('Model primaryKey is null and no attributes specified, aborting update function');
        $queryString = 'UPDATE '.$this->q.$this->tableName.$this->q.' SET ';
        $parmarray = [];
        $first = true;
        foreach($this->asArray() as $k => $v)
        {
            if($first)$first = false;
            else $queryString .= ',';
            $queryString .= $this->q.$k.$this->q.' = :'.$k.' ';
            $parmarray[$k]=$this->convertToDBField($v);
        }
        $queryString .= ' WHERE ';
        if(is_array($oldcache))
        {
            $first = true;
            if(array_is_list($oldcache))
                throw new Exception('Non-associative array passed to Model::update function.Aborting');
            foreach($oldcache as $k => $v)
            {
                if($first)$first = false;
                else $queryString .=',';
                $queryString .= $this->q.$k.$this->q.' = :'.$k.'_oldcache';
                $parmarray[$k.'_oldcache'] = [$this->convertToDBField($v),$this->PDOType($k)];
            }
        }
        else
        {
            $queryString .= $this->q.$this->primaryKey.$this->q.' = '.':'.$this->primaryKey.'_oldcache'; 
            $parmarray[$this->primaryKey.'_oldcache']=[$oldcache??$this->convertToDBField($this[$this->primaryKey]),$this->PDOType($this->primaryKey)];
        }
        $st = $this->db->prepare($queryString);
        foreach($parmarray as $k => list($v,$t))
        {
            $st->bindValue(':'.$k,$v,$t);
        }
        $st->execute();
        return $this->isUpdateSuccessful($st);
    }
    /**
     * Performs SQL INSERT or REPLACE command
     * If this->replace is set, it will perform update action, insert otherwise
     * If an oldcache is specified it will try to update the existing record
     * @param $oldcache associative array with old table attributes or primary key or model or null if you have already set replace field or want to perform INSERT
     * @return true on success, false on error
     */
    public function save(mixed $oldcache=null):bool
    {
        if($this->replace||$oldcache)
        {
            $this->update($oldcache);
        }
        return $this->performAction('INSERT');
    }
    /**
     * Performs SQL delete action
     * Will attempt to delete the record with the parameters specified in model fields
     */
    public function delete()
    {
        $qb = new \system\db\QueryBuilder();
        $qb->adjustQuery('DELETE FROM '.$this->q.$this->tableName.$this->q);
        $qb->where($this->asArray()); // Associative array will be escaped, anything else WILL NOT
        $qb->exec();
    }
    /*
     * Performs SQL select action
     * Performs select action and searches by primary key (if specified)
     */
    public function select(string $id=null)
    {
        $this->replace = true;
        $qb = new \system\db\QueryBuilder($this->db);
        $qb->select('*')->from($this->tableName);
        if($id)$qb->where([$this->primaryKey => $id]);
        return $qb;
    }
    public function isUpdateSuccessful(mixed $st)
    {
        if($st&&$st->rowCount()>0)return true;
        return (method_exists($this->db,'changes')&&($this->db->changes()>0));
    }
    public function PDOType($field)
    {
        return match($this->allowedFields[$field])
        {
         'int' => PDO::PARAM_INT,
         'bool' => PDO::PARAM_BOOL,
         default => PDO::PARAM_STR
        };
    }
    /*
     * Converts array to string for DB Records
    */
    public function convertToDBField($var)
    {
        return is_array($var)?json_encode($var):$var;
    }
    /*
     * Converts string containing JSON to array according to passed rules
     * @param rule 'array', 'array_assoc', 'array_strict'
     * @return array on success, null on fail
    */
    public function convertJSONFieldToArray($var,$rule)
    {
        if(!json_validate($var))return null;
        $arr_conv = json_decode($var);
        return match($rule){
            'array' =>is_array($arr_conv)?$arr_conv:null,
            'array_assoc'=>array_is_list($arr_conv)?$arr_conv:null,
            'array_strict' => (is_array($arr_conv)&&!array_is_list($arr_conv))?$arr_conv:null
        };
    }
}
