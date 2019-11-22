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

    public function getInsertFormData():Drink{
        $id = -1; //just fill in. It isn't used in the insert
        $author_id = $_SESSION["id"];
        $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        $ingredients = filter_input(INPUT_POST, "ingredients", FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS);
            $image = basename($_FILES["fileToUpload"]["name"]);
        $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);


        $newDrink = new Drink();
        $newDrink->setId($id);
        $newDrink->setAuthor_id($author_id);
        $newDrink->setCategory($category);
        $newDrink->setTitle($title);
        $newDrink->setIngredients($ingredients);
        $newDrink->setContent($content);
        $newDrink->setImage($image);
        return $newDrink;
    }

    public function getUpdateFormData(Drink $drink):Drink{
        $id = $drink->getId();
        $author_id = $drink->getAuthor_id();
        if(!empty($_POST["category"])) {
            $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $category = $drink->getCategory();
        }
        if(!empty($_POST["title"])){
            $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $title = $drink->getTitle();
        }
        if(!empty($_PAGE["ingredients"])){
            $ingredients = filter_input(INPUT_POST, "ingredients", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $ingredients = $drink->getIngredients();
        }
        if(!empty($_POST["content"])){
            $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $content = $drink->getContent();
        }
        if(!empty($_FILES["fileToUpload"]["name"])){
            $image = basename($_FILES["fileToUpload"]["name"]);
            $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $image = $drink->getImage();
        }

        $newDrink = new Drink();
        $newDrink->setId($id);
        $newDrink->setAuthor_id($author_id);
        $newDrink->setCategory($category);
        $newDrink->setTitle($title);
        $newDrink->setIngredients($ingredients);
        $newDrink->setContent($content);
        $newDrink->setImage($image);
        return $newDrink;
    }

    /*
     * INSERT NEW DRINK
     */
    public function insert(Drink $drink):bool {
        try {
            $author_id = $drink->getAuthor_id();
            $category = $drink->getCategory();
            $title = $drink->getTitle();
            $ingredients = $drink->getIngredients();
            $content = $drink->getcontent();
            $image = $drink->getImage();
            //Check if $image is empty due to that field not being mandatory. If empty, do not send and let database set default
            if (!empty($image)) {
                $stmt = $this->pdo->prepare('INSERT INTO recipes(author_id, category, title, ingredients, content, image) VALUES(:author_id, :category, :title, :ingredients, :content, :image)');
                $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            }else{
                $stmt = $this->pdo->prepare('INSERT INTO recipes(author_id, category, title, ingredients, content) VALUES(:author_id, :category, :title, :ingredients, :content)');
            }
            $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':ingredients', $ingredients, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);

            $stmt->execute();
            $stmt = null;

            return true;
        }catch (PDOException $err) {
            return false;
        }
    }

    /*
     * UPDATE A DRINK
     */
    public function update(Drink $newDrink):bool {
        try{
            $id = $newDrink->getId();
            $category = $newDrink->getCategory();
            $title = $newDrink->getTitle();
            $ingredients = $newDrink->getIngredients();
            $content = $newDrink->getContent();
            $image = $newDrink->getImage();

            $stmt = $this->pdo->prepare('UPDATE recipes SET category = :category, title = :title, ingredients = :ingredients, content = :content, image = :image where id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
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


    /*public function delete(int $id):void {
        $stmt = $this->pdo->prepare('DELETE FROM post WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }*/
    /*
     * MARCAR DE BAJA
     */
    public function markAsDeleted(int $id):void{
        $stmt = $this->pdo->prepare('UPDATE recipes SET view = 0 where id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = null;
    }

    public function markAsUndeleted(int $id):void{
        $stmt = $this->pdo->prepare('UPDATE recipes SET view = 1 where id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = null;
    }

    /*
     * UPLOAD IMAGE TO FILES
     */
    function uploadImage(Drink $drink):bool{
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        if(!empty($drink->getImage())) {
            $path = $_FILES['fileToUpload']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $drink->getTitle() . $ext)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
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
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = true;
            } else {
                array_push($errors, "File is not an image.");
                $uploadOk = false;
            }
            // Check file size is less than 200KB
            if ($_FILES["fileToUpload"]["size"] > 200000) {
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
            } else {
                // Check if file already exists and delete it
                if (file_exists($target_file)) {
                    if ($target_file == "img/avatars/defaultAvatar.jpg") {
                        array_push($errors, "Sorry, you can not upload a file with that name.");
                    } else {
                        unlink($target_file);
                    }
                }
            }
        }
        return $errors;
    }

    // Rep un objecte Post i comprova que les propietats siguen vàlides.
    public function validate(Drink $drink):array {
        $errors = [];

        if(!is_numeric($drink->getId()) || $drink->getId() == NULL){
            array_push($errors, "ID es invalido");
        }
        if(!is_numeric($drink->getAuthor_id()) || $drink->getAuthor_id() == NULL){
            array_push($errors, "Author ID es invalido");
        }
        if(!is_numeric($drink->getCategory()) || $drink->getCategory() == NULL){
            array_push($errors, "Categoria es invalido");
        }
        if(!is_string($drink->getTitle()) || $drink->getTitle() == NULL){
            array_push($errors, "Title es invalido");
        }
        if(!is_string($drink->getIngredients()) || $drink->getIngredients() == NULL){
            array_push($errors, "Ingredients es invalido");
        }
        if(!is_string($drink->getContent()) || $drink->getContent() == NULL){
            array_push($errors, "Content es invalido");
        }

        return $errors;
    }
}