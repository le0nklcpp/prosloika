<?php
namespace system\db;
use \system\input\escapeInput;
use \PDO;
/**
 * WELCOME TO THE LEAST SAFE CLASS
 * USING IT WITHOUT CAUTION MAY LEAD TO SQL INJECTIONS BEING EXPLOITED AND YOUR DATA BEING STOLEN
 */
class QueryBuilder
{
    /**
     * IMPORTANT SECURITY NOTE UNLESS YOU WISH YOUR BOSS TO GO BROKE
     * NEVER PASS THE UNESCAPED USER INPUT LIKE THAT
     * 
     * ASSOCIATIVE ARRAY IS ESCAPED, EVERYTHING ELSE IS NOT
     * IF YOU PASS STRING OR NON-ASSOCIATIVE ARRAY AS A PARAMETER IT WILL NOT ESCAPE IT, 
     * ALLOWING SEVERAL SQL INJECTIONS
     * YOU SHOULD SANITIZE YOUR INPUT ANYWAY.
     * UNFORTUNATELY, I CAN'T STOP PEOPLE FROM MAKING VULNERABLE CODE, BUT I MAY TRY TO WARN YOU
     * 
     * SAFE AND UNSAFE USE CASES:
     * 
     * NOT OKAY  $query->select()->from('users')->where(['login','=',$request->body['login']])
     * NOT OKAY $query->select()->from('users')->where(['login','=','\''.(string)$request->body['login'].'\''])
     * OKAY IN SOME CASES $query->select()->from('users')->where(['login','=','\''.\system\input\escapeInput::escape($request->body['login'],\system\input\escapeInput::ESCAPE_SQL).'\''])
     * GOOD $query->select()->from('users')->where(['login' => $request->body['login']])
     *  
     * Same goes to join
     * NOT OKAY $query->select()->from('users')->leftJoin('userSessions',['user_id','=',$request->post['user_id']])
     * OKAY $query->select()->from('users')->leftJoin('userSessions',['user_id','=',intval($request->post['user_id'])]) no array is safe from intval Ted
     * GOOD $query->select()->from('users')->leftJoin('userSessions',['user_id'=>$request->post['user_id']]) will be escaped
     * BEST $query->select()->from('users')->leftJoin('userSessions',['user_id' => (new UserSessionModel())->load($request->post)->user_id])
     * and well, if you need to compare not equal values, you should escape them by yourself. Here's how you can do it:
     * $query->where(['title','like',$query->escapeInput('%value%')])
     * 
     * Table and column names are escaped, but it is generally not a good idea to pass user input in there, and well, it requires setting the same encoding on server as used in database.
     * 
     * Also keep in mind that the query is built at the moment you call a function, so you should keep the strict query order in your code.
     */
    protected $queryString;
    protected $db = null;
    protected $q = '"';
    public function __construct($conn)
    {
        $this->db = $conn;
        $this->queryString = '';
        if($this->db&&$this->db->getAttribute(PDO::ATTR_DRIVER_NAME)=='mysql')$this->q='`';
    }
    /**
     * This function will escape quotes using PDO::quote.That will also put the result inside of a quotes
     * @param string $s string to escape
     * You can pass non-string values as well
     */
    public function escapeInput(mixed $s,bool $backtick = false)
    {
        if(is_array($s))throw new Exception(\system\input\escapeInput::escape('QueryBuilder->escapeInput error:array passed as argument.Possible SQL injection attempt.',\system\input\escapeInput::ESCAPE_PHP));
        if(!is_string($s))return $s; // is_string will return false on array, but it should fail on concatenation
        if($backtick)return $this->q.addcslashes(strval($s),'\\'.$this->q).$this->q;
        $result = $this->db->quote($s);
        return $result;
    }
    /**
     * UNSAFE FUNCTION. IF YOU FOR SOME REASON NEED TO PASS USER INPUT, YOU SHOULD ESCAPE IT BY YOURSELF
     * Begin SELECT statement
     * @param mixed $attributes string or array of columns to select. Note that array will be passed as-is, without escaping
     */
    public function select(mixed $attributes = '*')
    {
        $this->queryString = 'SELECT ';
        if($attributes=='*')$this->queryString .= $attributes;
        else if(is_array($attributes))
        {
            $this->queryString .= $this->q.implode($this->q.','.$this->q,$attributes).$this->q;
        }
        else $this->queryString .= $this->escapeInput($attributes,true);
        return $this;
    }
    /**
     * UNSAFE FUNCTION. IF YOU SOMEHOW NEED TO PASS USER INPUT, YOU SHOULD ESCAPE IT BY YOURSELF
     * Adds FROM to current query string
     * @param string $tableName Note that it is passed as-is, without escaping
     */
    public function from(string $tableName='')
    {
        if(empty($tableName))throw new Exception('QueryBuilder->from called without table name');
        $this->queryString.=' FROM '.$this->escapeInput($tableName,true);
    }
    private function addConditions(array $ar)
    {
        $first = true;
        foreach($ar as $key => $value)
        {
            if($first)$first = false;
            else $this->queryString .= ' AND ';
            $this->queryString .= $this->escapeInput($key,true).(($value!=null)?(' = '.$this->escapeInput($value)):' IS NULL');
        }
    }
    private function addConditions_legacy(array $ar)
    {
        $keys = array_keys($ar);
        $this->queryString .= $this->escapeInput($keys[0],true).(($ar[$keys[0]]!=null)?(' = '.$this->escapeInput($ar[$keys[0]])):' IS NULL');
        $len = count($ar);
        for($i=1;$i<$len;$i++)
        {
            $this->queryString.=' AND '.$this->escapeInput($keys[$i],true).(($ar[$keys[$i]]!=null)?(' = '.$this->escapeInput($ar[$keys[$i]])):' IS NULL');
        }
    }
    /*
     * Adds WHERE clause to current SQL query.
     * Note that the only case where passed parameters are escaped is when an associative array is used
     * Example: $query->select('*')->from('tUsers')->where(['login'=>$login,'password'=>$password])->exec() will result in execution of this string: SELECT * FROM tUsers WHERE login = 'anonymous' and password = 'bob'';--'
     * @param mixed $params array or string. Note that only ASSOCIATIVE arrays will be ESCAPED. Any other user input should be ESCAPED manually
     * @param string $clause='WHERE' specifies the actual clause. Set it to 'AND WHERE' to perform AND WHERE in your query
     * 
     */
    public function where(mixed $params='1=0',string $clause='WHERE')
    {
        $this->queryString.=' '.$clause.' (';
        if(is_array($params))
        {
            if(!array_is_list($params))
                 $this->addConditions($params);
            else 
            {
                $first = true;
                foreach($params as $iter)
                {
                    if($first==false)$this->queryString .= ' AND ';
                    else $first = false;
                    if(is_array($iter))
                    {
                        if(!array_is_list($iter))$this->addConditions($iter);
                        else throw new Exception('Too many array levels for '.escapeInput::escape($clause,escapeInput::ESCAPE_PHP).', possible SQL injection attempt');
                    }
                    else $this->queryString .= $iter; // WATCH OUT! NO DOUBLE QUOTES ADDED
                }
            }
        }
        else $this->queryString .=$params;
        $this->queryString .= ')';
        return $this;
    }
    /*
     * adds one more condition to where
     *
     *
     */
    public function andWhere(mixed $params='1=0')
    {
        return $this->where($params,'AND');
    }
    /*
     * adds OR CLAUSE
     *
     *
     */
    public function orWhere(mixed $params='1=0')
    {
        return $this->where($params,'OR');
    }
    /*
     * Performs the SQL JOIN action. Join type is specified by $type argument
     * @param string $type 'LEFT' or 'RIGHT' 
     * @param string $tableName table name to join
     * @param mixed $params array or string. Note that only ASSOCIATIVE arrays will be ESCAPED. Any other user input should be ESCAPED manually
     */
    public function join(string $type='LEFT',string $tableName,mixed $clause='0=1')
    {
        $this->queryString .= ' '.$type.' JOIN '.tableName.' ON (';
        if(is_array($clause))
        {
            if(!array_is_list($clause))
                $this->addConditions($clause);
            else foreach($iter as $item)
            {
                    $this->queryString .= $item;
            }
        }
        else $this->queryString .= $clause;
        $this->queryString .= ')';
        return $this;
    }
    /*
     * Performs the SQL LEFT JOIN action. Join type is specified by $type argument 
     * @param string $tableName table name to join
     * @param mixed $params array or string. Note that only ASSOCIATIVE arrays will be ESCAPED. Any other user input should be ESCAPED manually
     */
    public function leftJoin(string $tableName,mixed $clause='0=1')
    {
        return $this->join('LEFT',$tableName,$clause);
    }
    /*
     * Performs the SQL RIGHT JOIN action. Join type is specified by $type argument
     * @param string $tableName table name to join
     * @param mixed $params array or string. Note that only ASSOCIATIVE arrays will be ESCAPED. Any other user input should be ESCAPED manually
     */
    public function rightJoin(string $tableName,mixed $clause='0=1')
    {
        return $this->join('RIGHT',$tableName,$clause);
    }
    /*
     * Adds UNESCAPED string to current query string
     * USE WITH CAUTION, SINCE THIS FUNCTION DOES NOT ESCAPE USER INPUT.
     * @param $statement ESCAPED IN ADVANCE query string
     */
    public function adjustQueryUnsafe(string $statement)
    {
        $this->queryString.=$statement;
        return $this;
    }
    /*
     * Executes current query string
     * @return Array [containing associative array] with query results, or null if there are no results
     */
    public function exec()
    {
        $result = $this->db->query($this->queryString,PDO::FETCH_BOTH)->fetchAll();
        return (!empty($result))?$result:null;
    }
    /*
     * Executes current query string
     * @return associative array containing first matching record, or null if there are no results
     */
    public function one()
    {
        $result = $this->exec();
        return (!empty($result))?$result[0]:null;
    }
}
