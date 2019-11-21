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
    private $view;
    
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

    /*
     * RENDER
     */

    public function render($sideNum, string $filterLocation){
        require_once('UserModel.php');
        require_once('DrinkModel.php');
        //get the username of auther by their ID
        $connection = new DBConnect();
        $pdo = $connection->getConnection();
        $um = new UserModel($pdo);
        $dm = new DrinkModel($pdo);
        $username = $um->getUserById($this->getAuthor_id())->getUsername();

        //See if user is logged in and if so, add the edit button
        $edit="";
        if($this->getAuthor_id() == $_SESSION['id']){
            $edit = " <a href='index.php?page=editDrink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a>";
        }

        $category="";
        if($dm->getById($this->getId())->getCategory() == 1){
            $category = "Professional";
        }elseif ($dm->getById($this->getId())->getCategory() == 2){
            $category = "Original";
        }

        if($filterLocation == "drinks") {               //RUN THE RENDER FOR THE MAIN DRINKS PAGE
            //make the html string
            //Alternate between left and right
            $side1 = "";
            $side2 = "";
            if ($sideNum % 2) {
                $side1 = "order-lg-2";
                $side2 = "order-lg-1";
            }
            if($this->view == 1) {
                $html = '';
                $html .= "<section class='scroll'>";
                if ($sideNum != 0) {
                    $html .= "<div class='fillerDiv'></div>";
                }
                $html .= "<div class='mi-container-centered'>
                        <div class='container'>
                            <div class='row align-items-center'>
                                <div class='col-lg-6 " . $side1 . "'>
                                    <div class='p-5'>
                                        <img class='img-fluid rounded-circle' src='img/" . $this->getImage() . "' alt='" . $this->getImage() . "'>
                                    </div>
                                </div>
                                <div class='col-lg-6 " . $side2 . "'>
                                    <div class='p-5'>
                                        <h2 class='display-4'>" . $this->getTitle() . "</h2>
                                        <p>Author: " . ucfirst($username) . $edit ."</p>
                                        <a href='index.php?page=drink&id=" . $this->getId() . "' class='btn btn-primary btn-xl rounded-pill mt-5'>Make This Drink</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </section>";
            }else{return null;}
        }else{                                      //RUN THE RENDER FOR THE MYDRINKS PAGE WHERE ITS ONLY A LIST
            $html = '';
            if($this->view == 1){ //If set to normal
                $html .="<tr>
                    <td><a href='index.php?page=drink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsTitle'>".$this->getTitle()."</a></td>
                    <td>".ucfirst($username)."</td>
                    <td>".$category."</td>
                    <td>".$this->getPublished_at()."</td>
                    <td><a href='index.php?page=editDrink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a></td>
                    <td><a href='index.php?page=deleteDrink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsDelete'>Delete</a></td>
                </tr>";
            }
            if($this->view == 0){ //if set as deleted
                $html .="<tr>
                    <td><a href='index.php?page=drink&id=".$this->getId()."' class='myDrinksButtonsRed myDrinksButtonsTitle'>".$this->getTitle()."</a></td>
                    <td class='myDrinksButtonsRed'>".ucfirst($username)."</td>
                    <td class='myDrinksButtonsRed'>".$category."</td>
                    <td class='myDrinksButtonsRed'>".$this->getPublished_at()."</td>
                    <td><a href='index.php?page=editDrink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a></td>
                    <td><a href='index.php?page=undeleteDrink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsUndelete'>Undelete</a></td>
                </tr>";
            }
        }
        return $html;
    }


    public function renderPage(){
        require_once('UserModel.php');
        //get the username of author by their ID
        $connection = new DBConnect();
        $pdo = $connection->getConnection();
        $um = new UserModel($pdo);
        $username = $um->getUserById($this->getAuthor_id())->getUsername();
        $userAvatar = $um->getUserById($this->getAuthor_id())->getAvatar();

        //See if user is logged in and if so, add the edit button
        $edit="";
        if($this->getAuthor_id() == $_SESSION['id']){
            $edit = " <a href='index.php?page=editDrink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a>";
        }

        //Añadir saltos de linea <br> a en cada salto de linea que hay en el texto sacado del DB
        $ingredients = $this->getIngredients();
        $steps = $this->getContent();
        $ingredients = str_replace("\n", '<br />', $ingredients);
        $steps = str_replace("\n", '<br />', $steps);

        $html = '';
        $html .="<section>
                    <div style='height: 59px;'></div>
                    <div class='container .mi-container-center'>
                        <div class='row align-items-center'>
                            <div class='col-lg-6 order-lg-2'>
                                <div class='p-5'>
                                    <img class='img-fluid rounded-circle' src='img/".$this->getImage()."' alt='".$this->getImage()."'>
                                </div>
                            </div>
                            <div class='col-lg-6 order-lg-1'>
                                <div class='p-5'>
                                    <h1 class='display-4'>".$this->getTitle()."</h1>
                                </div>
                            </div>
                        </div>
                        <div class='row align-items-center'>
                            <div class='col-lg-6'>
                                <table>
                                    <tr>
                                        <a href='index.php?page=drinks&author=".$this->getAuthor_id()."&pagi=0'>
                                            Author: ".ucfirst($username)."<img src='img/avatars/".$userAvatar."' alt='userImage' height='30' width='30' class='rounded-circle'>
                                        </a> - ".$edit."
                                    </tr>
                                    <tr>
                                        <td>Posted at: ".$this->getPublished_at()."</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class='row align-items-center'> 
                            <div class='col-lg-6'>
                                <h3>Ingredients</h3>
                                <p>".$ingredients."</p>
                            </div>
                        </div>
                        <div class='row align-items-center'>
                            <div class='col-lg-6'>
                                <h3>Steps</h3>
                                <p>".$steps."</p>
                            </div>
                        </div>
                    </div>
                 </section>";
        return $html;
    }


}