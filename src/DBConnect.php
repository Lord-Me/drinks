<?php


class DBConnect
{
    private $connection;
    private $username;
    private $password;
    private $options;

    public function __construct()
    {
        $strJsonFileContents = file_get_contents("../config/app.json");
        $array = json_decode($strJsonFileContents, true);

        $this->username = $array["adminConnect"]["username"];
        $this->password = $array["adminConnect"]["password"];
        $this->connection = $array["adminConnect"]["connection"];
        $this->options = $array["adminConnect"]["options"];
    }
    /*
     * CONNECT
     */
    function connect():void{
        try {
            $pdo = new PDO ("$this->connection", $this->username, $this->password);

            #Perquè generi excepcions a l'hora de reportar errors.
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * RETURN ALL
     */
    function getAll(PDO $pdo):array{
        $posts = $pdo->query('SELECT * FROM recipes');
        return $posts->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Recipe');
    }

    /*
     * RETURN BY SENT ID
     */
    public function getById(PDO $pdo, int $id) {
        $stmt = $pdo->prepare('SELECT * FROM recipes WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Recipe');
        $stmt->execute();
        return $stmt->fetch();
    }

    /*
     * RETURN BY SENT CATEGORY ID
     * 1 => professional drinks
     * 2 => original drinks
     */
    public function getByCategory(PDO $pdo, int $type_id):array {
        $stmt = $pdo->prepare('SELECT * FROM recipes WHERE category = :type_id');
        $stmt->bindParam(':type_id', $type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Recipe');
    }

    public function insert(PDO $pdo, Post $post):bool {

    }

    public function update(PDO $pdo, Post $post):bool {

    }

    public function delete(PDO $pdo, Post $post):bool {

    }

    // Rep un objecte Post i comprova que les propietats siguen vàlides.
    public function validate(PDO $pdo, Post $post):array {
        $errors = [];
        return $errors;
    }
}