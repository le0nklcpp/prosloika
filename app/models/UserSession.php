<?php
namespace app\models;
class UserSession extends \system\db\Model
{
    public $tableName = 'user_session';
    public $primaryKey = 'hash';
    public function initDb()
    {
        $this->db = \app\config\DBSettings::$dbs['postgres'];
    }
    public $allowedFields = ['user_id'=>'int','hash'=>'string:512','expires'=>'string','ip'=>'string'];
    /**
     * Session hashing
     * @param $user Associative array containing login and password fields
     * @return string hash
    * */ 
    public function createHash($user)
    {
        $result = hash('sha512',random_bytes(64));
        if($this->findSession($result))return $this->createHash($user);
        return $result;
    }
    /**
     * Creates user session
     * @param $user Associative array containing login and password fields
     * @return array [
     * user_id => $user_id,
     * hash => 'hash',
     * expires => $date,
     * ip => 'ip'
     * ]
     * */
    public function createSession($user,$ip)
    {
        $this->hash = $this->createHash($user);
        $this->ip = $ip;
        $this->expires = date("Y-m-d H:i:s",strtotime("+1 day"));
        $this->user_id = $user->id;
        $this->save();
        return $this->asArray();
    }
    /**
     * Looks for existing session
     * @param $hash session hash
     * @return array [
     * user_id => $user_id,
     * hash => 'hash',
     * expires => $date,
     * ip => 'ip'
     * ]
     * */
    public function findSession($hash)
    {
        $result = $this->select($hash)->andWhere('expires > \''.date("Y-m-d H:i:s").'\'::date')->one();
        if($result)return $this->load($result);
        return null; 
    }
    public function getUser()
    {
        $result = new \app\models\UserModel();
        $result->load($result->select()->where('id' => $this->user_id)->one());
        return $result;
    }
}
