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
        $post = $stmt->fetch();
        if ($post==false)
            throw new ExceptionPageNotFound();
        else
            return $post;
    }

    public function getAllByAuthorId(int $author_id):array {
        $stmt = $this->pdo->prepare('SELECT * FROM recipes WHERE author_id = :author_id');
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Drink');
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
     * GET FORM DATA
     */

    public function getFormData():Drink{
        $author_id = $_SESSION["id"];
        $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        $ingredients = filter_input(INPUT_POST, "ingredients", FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS);
            $image = basename($_FILES["fileToUpload"]["name"]);//TODO cant find fileToUpload
        $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);


        $newDrink = new Drink();
        $newDrink->setAuthor_id($author_id);
        $newDrink->setCategory($category);
        $newDrink->setTitle($title);
        $newDrink->setIngredients($ingredients);
        $newDrink->setContent($content);
        $newDrink->setImage($image);
        return $newDrink;
    }

    public function insert(Drink $drink):bool {
        try {
            $author_id = $drink->getAuthor_id();
            $category = $drink->getCategory();
            $title = $drink->getTitle();
            $ingredients = $drink->getIngredients();
            $content = $drink->getcontent();
            $image = $drink->getImage();
            $stmt = $this->pdo->prepare('INSERT INTO recipes VALUES(:author_id, :category, :title, :ingredients, :content, :image)');
            $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            $stmt->bindParam('category', $category, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':ingredients', $ingredients, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            $stmt->execute();
            $stmt = null;

            return true;
        }catch (PDOException $err) {
            return false;
        }
    }
/*
    public function update(Post $newPost):void {
        $id = $newPost->getId();
        $author_id = $newPost->getAuthor_id();
        $title = $newPost->getTitle();
        $slug = $newPost->getSlug();
        $summary = $newPost->getSummary();
        $content = $newPost->getContent();
        $published_at = $newPost->getPublished_at();

        $stmt = $this->pdo->prepare('UPDATE post SET author_id = :author_id, title = :title, slug = :slug, summary = :summary, content = :content, published_at = :published_at where id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':summary', $summary, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':published_at', $published_at, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = null;
    }*/

    /*public function delete(int $id, bool $isComment, bool $isPostTag):void {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->beginTransaction();
            if($isPostTag) {
                $stmt = $this->pdo->prepare('DELETE FROM post_tag WHERE post_id = :id');
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt = null;
            }

            if($isComment) {
                $stmt = $this->pdo->prepare('DELETE FROM comment WHERE post_id = :id');
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt = null;
            }

            $stmt = $this->pdo->prepare('DELETE FROM post WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();
            header("Location: index.php?page=index");
        }
        catch(Exception $e){
            $this->pdo->rollBack();
            header("Location: index.php?page=index");
        }
    }*/

    function uploadImage(Drink $drink):bool{
        $target_dir = "img/";
        $target_file = $target_dir . basename($drink->getImage());

        if(!empty($drink->getImage())) {
            if (move_uploaded_file($drink->getImage(), $target_file)) {
                echo "The file " . basename($drink->getImage()) . " has been uploaded.";
                return true;
            } else {
                echo "Sorry, there was an error uploading your file.";
                return false;
            }
        }
        return true;
    }

    function validateImage(Drink $drink):array{
        $errors = [];

        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if(!empty($_FILES["fileToUpload"]["name"])) {
            $check = getimagesize($_FILES["fileToUpload"]["name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = true;
            } else {
                array_push($errors, "File is not an image.");
                $uploadOk = false;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                array_push($errors, "Sorry, file already exists.");
                $uploadOk = false;
            }
            // Check file size is less than 50KB
            if ($_FILES["fileToUpload"]["size"] > 100000) {
                array_push($errors, "Sorry, your file is too large.");
                $uploadOk = false;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                array_push($errors, "Sorry, only JPG, JPEG & PNG files are allowed.");
                $uploadOk = false;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == false) {
                array_push($errors, "Sorry, your file was not uploaded.");
                // if everything is ok, try to upload file
            }
            //TODO delete old image after changing
        }
        return $errors;
    }

    // Rep un objecte Post i comprova que les propietats siguen vàlides.
    public function validate(Drink $drink):array {
        $errors = [];
        $author_id = $drink->getAuthor_id();
        $category = $drink->getCategory();
        $title = $drink->getTitle();
        $ingredients = $drink->getIngredients();
        $content = $drink->getContent();

        if(!is_numeric($author_id) || $author_id == NULL){
            array_push($errors, "Author ID es invalido");
        }
        if(!is_numeric($category) || $category == NULL){
            array_push($errors, "Categoria es invalido");
        }
        if(!is_string($title) || $title == NULL){
            array_push($errors, "Title es invalido");
        }
        if(!is_string($ingredients) || $ingredients == NULL){
            array_push($errors, "Ingredients es invalido");
        }
        if(!is_string($content) || $content == NULL){
            array_push($errors, "Content es invalido");
        }

        return $errors;
    }
}