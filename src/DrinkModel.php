<?php


class DrinkModel
{
    private $pdo;

    public function  __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }



    /*
         * RETURN ALL
         */
    function getAll():array{
        $posts = $this->pdo->query('SELECT * FROM recipes');
        return $posts->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Drink');
    }

    /*
     * RETURN BY SENT ID
     */
    public function getById(int $id):Drink {
        $stmt = $this->pdo->prepare('SELECT * FROM recipes WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Drink');
        $stmt->execute();
        return $stmt->fetch();
    }

    /*
     * RETURN BY SENT CATEGORY ID
     * 1 => professional drinks
     * 2 => original drinks
     */
    public function getByCategory(int $type_id):array {
        $stmt = $this->pdo->prepare('SELECT * FROM recipes WHERE category = :type_id');
        $stmt->bindParam(':type_id', $type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Drink');
    }

    /*
     * return all IDs
     */
    function getIds():array{
        $stmt = $this->pdo->query('SELECT id FROM recipes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_PROPS_LATE);
    }

    public function insert(Post $post):bool {

    }

    public function update(Post $post):bool {

    }

    public function delete( Post $post):bool {

    }

    // Rep un objecte Post i comprova que les propietats siguen vàlides.
    public function validate(Post $post):array {
        $errors = [];
        return $errors;
    }
}