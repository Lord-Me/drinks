<?php

namespace App\Entity;

use App\Model\UserModel;
use App\Model\DrinkModel;
use App\DBConnect;

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
    private $view;

    private $author_name;
    private $author_avatar;
    
    function __construct()
    {
    }

    //GETTERS
    //No strict get types so null errors can be managed elsewhere

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

    function getView(){
        return $this->view;
    }

    function getAuthor_name(){
        return $this->author_name;
    }

    function getAuthor_avatar(){
        return $this->author_avatar;
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

    function setView($view){
        $this->view = $view;
    }

    function setAuthor_name($author_name){
        $this->author_name = $author_name;
    }

    function setAuthor_avatar($author_avatar){
        $this->author_avatar = $author_avatar;
    }
}