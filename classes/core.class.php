<?php
class Core
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        // building data source name from config
        $dsn = 'mysql:host=' . Config::read('db.host') .
               ';dbname='    . Config::read('db.basename') .
               ';port='      . Config::read('db.port') .
               ';connect_timeout=15';
        // getting DB user from config                
        $user = Config::read('db.user');
        // getting DB password from config                
        $password = Config::read('db.password');

        $this->dbh = new PDO($dsn, $user, $password);
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    public static function getHash($adminUser,$adminPassword) {
        $dynamics = md5(substr($adminUser, 0, strlen($adminUser)/2) . $adminPassword);
        $salt = substr($dynamics, 0, 16);
        $pepper = substr($dynamics, 16);
        $hashedPass = hash('sha512', $salt.$adminPassword.$pepper);

        return $hashedPass;
    }
    // others global functions
}
?>