<?php

namespace APP;

use App\Entity\Drink;

class recipesControl{
    private $drinks = [];

    /*
     * RENDER EACH DRINK ON LIST PAGE
     */
    public function render(int $currentPagi, int $drinksPerPage, string $filterLocation, array $queryArray):array {
        $html = [];
        $side = 0;
        $pages = [];

        foreach ($this->drinks as $drink) {
            //Only add those which have the same ID as the current sesion
            if($_SESSION == NULL || $filterLocation == "drinks" || $_SESSION["id"] == 1 ) {
                array_push($html, $drink->render($side, $filterLocation));  //Add them all
            }else{
                //Remove all posts not belonging to you in the case of the session not being the admin (1)
                if($drink->getAuthor_Id() == $_SESSION["id"]) {
                    array_push($html, $drink->render($side, $filterLocation));
                }
            }
            if(count($html) != 0){
                //Remove all NULL fields which are the ones where view == 0
                if ($html[count($html) - 1] == NULL) {
                    array_pop($html);
                }
                $side++;

                //create a page every 10 html posts
                if (count($html) == $drinksPerPage) {
                    array_push($pages, $html);
                    $html = [];
                }
            }
        }
        //create a final page with left over drinks if there are any due to pagination
        if(!empty($html)){
            array_push($pages, $html);
        }

        //CHECK IF EMPTY BECAUSE OF FILTERS
        if(empty($pages)){
            array_push($html, "<section>
                                                <div class='mi-container-centered'>
                                                    <div class='container'>
                                                        <div class='row align-items-center'>
                                                            <h1>No Search Results!</h1>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>");
            array_push($pages, $html);
        }

        if ($filterLocation=="myDrinks"){
            $start = "<section>
                        <div class='mi-container-centered'>
                            <div class='container'>
                                <div class='row align-items-center'>
                                    <div class='col-lg-12'>
                                        <div class='p-5'>
                                            <table class='myDrinksTable'>";
            $end =                         "</table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>";
            for($i=0; $i<count($pages); $i++) {
                array_unshift($pages[$i], $start);
                array_push($pages[$i], $end);
            }
        }

        //paginate everything
        $finalArray = $this->paginate($pages, $currentPagi, $filterLocation, $queryArray);
        return $finalArray;

    }

    /*
     * Add pagination buttons to end
     */
    function paginate(array $pages, int $currentPagi, string $filterLocation, array $queryArray):array
    {
//SORT OUT URL
        //MAKE URL A STRING
        $keys = array_keys($queryArray);
        $i = 0;
        $queryString = "";
        foreach ($queryArray as $item){
            $queryString .= $keys[$i] . "=" . $item . "&";
            $i++;
        }
        $queryString = substr($queryString, 0, -1);
        $queryString = "?".$queryString;
// -SORT OUT URL

        if ($filterLocation == "myDrinks") {
            $forwardUrl = "/drinks/user/myDrinks/" . ($currentPagi + 1) . $queryString;
            $backUrl = "/drinks/user/myDrinks/" . ($currentPagi - 1) . $queryString;
        }else{
            $forwardUrl = "/drinks/ourDrinks/" . ($currentPagi + 1) . $queryString;
            $backUrl = "/drinks/ourDrinks/" . ($currentPagi - 1) . $queryString;
        }

        $buttons = $this->createButtons(count($pages), $filterLocation, $queryString);
        $forward = "<a href='".$forwardUrl."' class='pagiButton pagiForward'><i class='fas fa-arrow-right'></i></a>";
        $back = "<a href='".$backUrl."' class='pagiButton pagiBack'><i class='fas fa-arrow-left'></i></a>";
        $pagiButtons="";
        for($i=0; $i<count($pages); $i++) {
            $pagiButtons .= "<section>
                                <div class='mi-container-centered'>
                                    <div class='container'>
                                        <div class='row align-items-center'>
                                            <div class='col-lg-12 text-center'>";
            if(count($pages) == 1){
                //add no buttons
            }elseif($i==0){
                $pagiButtons .= $buttons;
                $pagiButtons .= $forward;
            }elseif ($i==count($pages)-1){
                $pagiButtons .= $back;
                $pagiButtons .= $buttons;
            }else{
                $pagiButtons .= $back;
                $pagiButtons .= $buttons;
                $pagiButtons .= $forward;
            }
            $pagiButtons .="                </div>
                                        </div>
                                    </div>
                                </div>
                            </section>";

            array_push($pages[$i], $pagiButtons);
            $pagiButtons="";
        }
        return $pages;
    }

    /*
     * Generate the bottom buttons
     */
    function createButtons(int $amount, string $filterLocation, string $queryString):string{
        $buttons="";

        for($i=0; $i<$amount; $i++){
            if ($filterLocation == "myDrinks") {
                $buttons .= "<a href='/drinks/user/myDrinks/" . ($i + 1) . $queryString . "' class='pagiButton'>" . ($i + 1) . "</a>";
            }else{
                $buttons .= "<a href='/drinks/ourDrinks/" . ($i + 1) . $queryString . "' class='pagiButton'>" . ($i + 1) . "</a>";
            }
        }
        return $buttons;
    }

    /*
     * ADD A DRINK TO THE ARRAY OF DRINKS
     */
    function add(Drink $drink){
        $this->drinks[] = $drink;
    }


}