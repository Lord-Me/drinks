<?php


class Drink
{
    private $id;
    private $author_id;
    private $category;
    private $title;
    private $ingredients;
    private $content;
    private $image;
    private $published_at;
    
    function __construct()
    {
    }

    //GETTERS

    function getId():int{
        return $this->id;
    }

    function getAuthor_id():int{
        return $this->author_id;
    }

    function getCategory():int{
        return $this->category;
    }

    function getTitle():String{
        return $this->title;
    }

    function getIngredients():String{
        return $this->ingredients;
    }

    function getContent():String{
        return $this->content;
    }

    function getImage():String{
        return $this->image;
    }

    function getPublished_at():String{
        return $this->published_at;
    }

    //SETTERS

    function setId($id){
        $this->id = $id;
    }

    function setAuthor_id($author_id){
        $this->author_id = $author_id;
    }

    function setCategory($category){
        $this->category = $category;
    }

    function setTitle($title){
        $this->title = $title;
    }

    function setIngredients($ingredients){
        $this->ingredients = $ingredients;
    }

    function setContent($content){
        $this->content = $content;
    }

    function setImage($image){
        $this->image = $image;
    }

    function setPublished_at($published_at){
        $this->published_at = $published_at;
    }

    /*
     * RENDER
     */

    public function render($sideNum){
        require_once('UserModel.php');
        //get the username of auther by their ID
        $connection = new DBConnect();
        $pdo = $connection->getConnection();
        $model = new UserModel($pdo);
        $username = $model->getUsernameById($this->getAuthor_id());
        $username = $username["nickname"];

        //make the html string
        $side1 = "";
        $side2 = "";
        if($sideNum%2){
            $side1 = "order-lg-2";
            $side2 = "order-gl-1";
        }
        $html = '';
        $html .="<section>
                    <div class='container'>
                        <div class='row align-items-center'>
                            <div class='col-lg-6 ".$side1."'>
                                <div class='p-5'>
                                    <img class='img-fluid rounded-circle' src='img/".$this->getImage().".jpg' alt='".$this->getImage()."'>
                                </div>
                            </div>
                            <div class='col-lg-6 ".$side2."'>
                                <div class='p-5'>
                                    <h2 class='display-4'>".$this->getTitle()."</h2>
                                    <p>".$username."</p>
                                    <a href='index.php?page=drink&id=".$this->getId()."' class='btn btn-primary btn-xl rounded-pill mt-5'>Make This Drink</a>
                                </div>
                            </div>
                        </div>
                    </div>
                 </section>";
        return $html;
    }


    public function renderPage(){
        $html = '';
        $html .="<div>\n";
        $html .="<h3>". $this->getTitle() . "</h3><p>" . $this->getIngredients() . "</p><div>" . $this->getImage() . "</div><p>" . $this->getContent() . "</p><p>" . $this->getPublished_at() . "<br>" . $this->getAuthor_id() . "</p>";
        $html .="</div>\n";
        return $html;
    }


}