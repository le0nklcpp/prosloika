<?php
namespace app\models;
class UserModel extends \system\db\Model
{
    public $tableName = 'users';
    public $primaryKey = 'id';
    public $allowedFields = ['id'=>'int','login' => 'string:64','password' => 'string:512','role'=>'string'];
    public $requiredFields = ['login','password'];
    public function initDb()
    {
        $this->db = \app\config\DBSettings::$dbs['postgres'];
    }
}
