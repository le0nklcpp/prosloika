<?php
namespace system\input;
class IpFilter
{
    const $bantime = '+7 DAYS';
    const $bannedattempts = 20;
    public static function loadSession()
    {
        if(function_exists('apcu_enabled')&&apcu_enabled())
        {
            if(!apcu_exists('ip_punishment'))return array();
            return apcu_fetch('ip_punishment');
        }
        return array_key_exists($_SESSION,'ip_punishment')?$_SESSION['ip_punishment']:array();
    }
    public static function saveSession($var)
    {
        if(function_exists('apcu_enabled')&&apcu_enabled())
        {
            apcu_add('ip_punishment',$var);
        }
        else $_SESSION['ip_punishment'] = $var;
    }
    public static function block(string $ip)
    {
        $sv = self::loadSession();
        if(array_key_exists($sv,$ip))
        {
            $sv[$ip]['expires']=strtotime($bantime);
            $sv[$ip]['attempts']++;
        }
        else $sv[$ip] = ['attempts' => 1,'expires' => strtotime($bantime)];
        self::saveSession($sv[$ip]);
    }
    public static function isBlocked(string $ip)
    {
        $sv = self::loadSession();
        if(!array_key_exists($sv,$ip))return false;
        if(strtotime($sv[$ip]['expires'])<strtotime(date()))
        {
            unset($sv[$ip]);
            return false;
        }
        return ($sv[$ip]['attempts']>=$bannedattempts);
    }
}