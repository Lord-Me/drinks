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
    public function getFormData():Post{
        $id = $this->pdo->lastInsertId();
        $author_id = filter_input(INPUT_POST, "author_id",FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        $slug = filter_input(INPUT_POST, "slug", FILTER_SANITIZE_SPECIAL_CHARS);
        $summary = filter_input(INPUT_POST, "summary", FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS);
        date_default_timezone_set('Europe/Madrid');
        $date = date('Y-m-d H:i:s');

        $post = new Post();
        $post->setId($id);
        $post->setAuthor_id($author_id);
        $post->setTitle($title);
        $post->setSlug($slug);
        $post->setSummary($summary);
        $post->setContent($content);
        $post->setPublished_at($date);
        return $post;
    }

    public function insert(Post $post):bool {
        try {
            $id = $post->getId();
            $author_id = $post->getAuthor_id();
            $title = $post->getTitle();
            $slug = $post->getSlug();
            $summary = $post->getSummary();
            $content = $post->getContent();
            $published_at = $post->getPublished_at();
            $stmt = $this->pdo->prepare('INSERT INTO post VALUES(:id, :author_id, :title, :slug, :summary, :content, :published_at)');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->bindParam(':summary', $summary, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':published_at', $published_at, PDO::PARAM_STR);
            $stmt->execute();
            $stmt = null;

            return true;
        }catch (PDOException $err) {
            return false;
        }
    }

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
    }

    public function delete(int $id, bool $isComment, bool $isPostTag):void {
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
    }

    // Rep un objecte Post i comprova que les propietats siguen vàlides.
    public function validate(Post $post):array {
        $errors = [];
        $author_id = $post->getAuthor_id();
        $title = $post->getTitle();
        $slug = $post->getSlug();
        $summary = $post->getSummary();
        $content = $post->getContent();
        echo "<br>";
        if(!is_numeric($author_id) || $author_id == NULL){
            array_push($errors, "Author ID es invalido");
        }
        if(!is_string($title) || $title == NULL){
            array_push($errors, "Title es invalido");
        }
        if(!is_string($slug) || $slug == NULL){
            array_push($errors, "Slug es invalido");
        }
        if(!is_string($summary) || $summary == NULL){
            array_push($errors, "Summary es invalido");
        }
        if(!is_string($content) || $content == NULL){
            array_push($errors, "Content es invalido");
        }

        return $errors;
    }
    */
}