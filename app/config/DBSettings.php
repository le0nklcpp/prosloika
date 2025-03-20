<?php
namespace app\config;
class DBSettings
{
    public static $dbs = [
    'postgres' => ['pgsql:host=localhost;port=5432;dbname=public;user=micromc;password=micromc',null,null]
    ];
    public static function init_connections()
    {
        foreach(DBSettings::$dbs as $db => $ar)
        {
            DBSettings::$dbs[$db] = new \PDO($ar[0],$ar[1],$ar[2]);
            // Prepare statements on database side. Useful in case if your database encoding differs from the one you use here/ In some cases it won't save you.
            (DBSettings::$dbs[$db])->setAttribute(\PDO::ATTR_EMULATE_PREPARES,false);
        }
     }
    public static function getDb($key)
    {
        return DBSettings::$dbs[$key];
    }
}
