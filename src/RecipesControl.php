<?php


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
            if(($_SESSION == NULL && $filterLocation != "myDrinks") || $_SESSION["id"] != 1 ) {
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
        $finalArray = $this->paginate($pages, $currentPagi, $queryArray);
        return $finalArray;

    }

    /*
     * Add pagination buttons to end
     */
    function paginate(array $pages, int $currentPagi, array $queryArray):array{
//SORT OUT URL
        unset($queryArray['pagi']);
        //MAKE URL A STRING
        $keys = array_keys($queryArray);
        $i = 0;
        $url = "";
        foreach ($queryArray as $item){
            $url .= $keys[$i] . "=" . $item . "&";
            $i++;
        }
        $url = substr($url, 0, -1);
// -SORT OUT URL

        $forwardUrl = $url."&pagi=".($currentPagi+1);
        $backUrl = $url."&pagi=".($currentPagi-1);

        $buttons = $this->createButtons(count($pages), $url);
        $forward = "<a href='index.php?".$forwardUrl."' class='pagiButton pagiForward'><i class='fas fa-arrow-right'></i></a>";
        $back = "<a href='index.php?".$backUrl."' class='pagiButton pagiBack'><i class='fas fa-arrow-left'></i></a>";
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
    function createButtons(int $amount, string $url):string{
        $buttons="";

        for($i=0; $i<$amount; $i++){
            $buttons .= "<a href='index.php?".$url."&pagi=".($i+1)."' class='pagiButton'>".($i+1)."</a>";
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