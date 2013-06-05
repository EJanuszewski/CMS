<?php
class Config
{
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

// db
Config::write('db.host', '{DBHOST}');
Config::write('db.port', '3306');
Config::write('db.basename', '{DBNAME}');
Config::write('db.user', '{DBUSER}');
Config::write('db.password', '{DBPASSWORD}');
Config::write('baseUrl', '{BASEURL}');
?>