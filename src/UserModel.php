<?php


class UserModel{
    private $pdo;

    public function  __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function getUsernameById($user_id):Array{
        $stmt = $this->pdo->prepare('SELECT nickname FROM users WHERE id = :user_id');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC | PDO::FETCH_PROPS_LATE);
        $stmt->execute();
        return $stmt->fetch();
    }
}