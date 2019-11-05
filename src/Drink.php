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
    
    /*
    id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `category` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `ingredients` longtext COLLATE utf8_spanish2_ci NOT NULL,
  `content` longtext COLLATE utf8_spanish2_ci NOT NULL,
  `image` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `published_at` 
    */

    function __construct($id, $author_id, $category, $title, $ingredients, $content, $image, $published_at)
    {
        $this->id = $id;
        $this->author_id = $author_id;
        $this->category = $category;
        $this->title = $title;
        $this->ingredients = $ingredients;
        $this->content = $content;
        $this->image = $image;
        $this->published_at = $published_at;
    }

    //GETTERS

    function getId(){
        return $this->id;
    }

    function getAuthor_id(){
        return $this->author_id;
    }

    function getcategory(){
        return $this->category;
    }

    function gettitle(){
        return $this->title;
    }

    function getingredients(){
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

    function setcategory($category){
        $this->category = $category;
    }

    function settitle($title){
        $this->title = $title;
    }

    function setingredients($ingredients){
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
        $html .="<p>". $this->getId() . ". " . $this->getcategory() . "<br>" . $this->gettitle() . "<br>" . $this->getingredients() . "<br>" . $this->getAuthor_id() . "<br>" . $this->getPublished_at() ."</p><a href='SeeDrink.php?Drink=".$this->getId()."'>Ver entrada</a>";
        $html .="</div>\n";
        return $html;
    }

    public function renderPage(){
        $html = '';
        $html .="<div>\n";
        $html .="<h3>". $this->getId() . ". " . $this->getcategory() . "</h3><p>" . $this->getContent() . "</p><p>" . $this->getPublished_at() . "</p>";
        $html .="</div>\n";
        return $html;
    }


}