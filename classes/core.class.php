<?php
class Core
{
    public $dbh; // handle of the db connexion
    private static $instance;

    //Construct for building database connection
    private function __construct()
    {
        error_reporting(E_ALL);
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

    //Genereates hash based on username and password
    public static function getHash($adminUser,$adminPassword) {
        $dynamics = md5(substr($adminUser, 0, strlen($adminUser)/2) . $adminPassword);
        $salt = substr($dynamics, 0, 16);
        $pepper = substr($dynamics, 16);
        $hashedPass = hash('sha512', $salt.$adminPassword.$pepper);

        return $hashedPass;
    }

    //Login function receives admin username and admin password, generates hash and logs the user in.
    //Returns $_SESSION
    public static function login($adminUser,$adminPassword) {

        //Hash
        $hashedPass = self::getHash($adminUser,$adminPassword);
        //Try login
        //Perform the SQL
        $q = self::getInstance()->dbh->prepare('SELECT * FROM `users` WHERE `username` = ? AND `password` = ?');
        $q->execute(array($adminUser, $hashedPass));
        $r = $q->rowCount();
        if($r == 1) {
            //Login successful
            //Set the cookie so they see the admin panel
            $_SESSION['session']['admin'] = 1;
        } else {
            $eStr = 'Username or password wrong, please try again.';
            $_SESSION['session']['admin'] = 0;
        }
        return $_SESSION['session'];
    }

    //Destroys session and redirects to main url
    public static function logout() {
        $_SESSION['session']['logged_in'] = 0;
        session_destroy();
        if (strpos($_SERVER['HTTP_HOST'],'http://') === false){
            $baseUrl = 'http://'.$_SERVER['HTTP_HOST'];
        } else {
            $baseUrl = $_SERVER['HTTP_HOST'];
        }
        header("Location:".$baseUrl);
    }
    // others global functions
}
?>