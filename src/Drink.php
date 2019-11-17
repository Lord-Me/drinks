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

    public function render($sideNum, string $filterLocation){
        require_once('UserModel.php');
        require_once('DrinkModel.php');
        //get the username of auther by their ID
        $connection = new DBConnect();
        $pdo = $connection->getConnection();
        $um = new UserModel($pdo);
        $dm = new DrinkModel($pdo);
        $username = $um->getUserById($this->getAuthor_id())->getUsername();

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
                                        <p>Author: " . ucfirst($username) . "</p>
                                        <a href='index.php?page=drink&id=" . $this->getId() . "' class='btn btn-primary btn-xl rounded-pill mt-5'>Make This Drink</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </section>";
        }else{                                      //RUN THE RENDER FOR THE MYDRINKS PAGE WHER ITS ONLY A LIST
            $html = '';
            $html .="<tr>
                        <td><a href='index.php?page=drink&id=".$this->getId()."' class='myDrinksButtons myDrinksButtonsTitle'>".$this->getTitle()."</a></td>
                        <td>".$category."</td>
                        <td>".$this->getPublished_at()."</td>
                        <td><a href='index.php?page=editPost&post=".$this->getId()."' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a></td>
                        <td><a href='index.php?page=deletePost&post=".$this->getId()."' class='myDrinksButtons myDrinksButtonsDelete'>Delete</a></td>
                    </tr>";
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
                                    <a>
                                        <td>Author: <a href='index.php?page=drinks&author=".$this->getAuthor_id()."'>
                                                        ".ucfirst($username)." <img src='img/avatars/".$userAvatar."' alt='userImage' width='30' height='30' class='rounded-circle'></td>
                                                    </a>
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