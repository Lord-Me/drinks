<?php


class DBConnect
{
    private $connection;
    private $username;
    private $password;
    private $options;

    public function __construct()
    {
        $strJsonFileContents = file_get_contents("config/app.json");
        $array = json_decode($strJsonFileContents, true);

        $this->username = $array["adminConnect"]["username"];
        $this->password = $array["adminConnect"]["password"];
        $this->connection = $array["adminConnect"]["connection"];
        $this->options = $array["adminConnect"]["options"];
    }
    /*
     * CONNECT
     */
    function getConnection():PDO{
        try {
            $pdo = new PDO ("$this->connection", $this->username, $this->password);

            #Perquè generi excepcions a l'hora de reportar errors.
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}