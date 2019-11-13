<?php


class UserModel{
    private $pdo;

    public function  __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function getUserById(int $id):User{
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $stmt->execute();
        return $stmt->fetch();
    }

    function getUserByName(string $username):User{
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $stmt->execute();
        $user = $stmt->fetch();
        if(!empty($user)){
            return $user;
        }else{
            throw new PDOException("El nombre de usuario es invalido");
        }
    }

    function insert(User $user):bool {
        try {
            $username = $user->getUsername();
            $email = $user->getEmail();
            $password = password_hash($user->getPassword(), PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
            // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.

            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
        catch (PDOException $err) {
            return false;
        }
    }
}