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

    function getId(){
        return $this->id;
    }

    function getAuthor_id(){
        return $this->author_id;
    }

    function getCategory(){
        return $this->category;
    }

    function getTitle(){
        return $this->title;
    }

    function getIngredients(){
        return $this->ingredients;
    }

    function getContent(){
        return $this->content;
    }

    function getImage(){
        return $this->image;
    }

    function getPublished_at(){
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

    public function render(){
        $html = '';
        $html .="<div>\n";
        $html .=    "<div>
                        <p>". $this->gettitle() . "</p>
                     </div>
                     <div>
                        <img src=\"img/" . $this->getImage() .".jpg\" alt=\"" . $this->getImage() ."\" height=\"42\" width=\"42\">
                     </div>
                     <div>
                        <a href=\"index.php?page=drink&id=".$this->getId()."\" class=\"btn btn-primary btn-xl rounded-pill mt-5\">Make Me</a>
                     </div>";
        $html .="</div>\n";
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