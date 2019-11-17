<?php


class recipesControl{
    private $drinks = [];

    /*
     * RENDER EACH DRINK ON LIST PAGE
     */
    public function render(int $currentPagi, int $drinksPerPage, string $filterLocation):array {
        $html = [];
        $side = 0;
        $pages = [];

        foreach ($this->drinks as $drink) {
            array_push($html, $drink->render($side, $filterLocation));
            $side++;
            if(count($html) == $drinksPerPage){
                array_push($pages, $html);
                $html=[];
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
                    </section>";//TODO this wont add
            for($i=0; $i<count($pages); $i++) {
                array_unshift($pages[$i], $start);
                array_push($pages[$i], $end);
            }
        }

        //paginate everything
        $finalArray = $this->paginate($pages, $currentPagi, $filterLocation);
        return $finalArray;

    }

    /*
     * Add pagination buttons to end
     */
    function paginate(array $pages, int $currentPagi, string $filterLocation):array{
        $backOne=$currentPagi-1;
        $forwardOne=$currentPagi+1;
        $buttons = $this->createButtons(count($pages), $filterLocation);
        $forward = "<a href='index.php?page=".$filterLocation."&pagi=".$forwardOne."' class='pagiButton pagiForward'><i class='fas fa-arrow-right'></i></a>";
        $back = "<a href='index.php?page=".$filterLocation."&pagi=".$backOne."' class='pagiButton pagiBack'><i class='fas fa-arrow-left'></i></a>";
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
    function createButtons(int $amount, string $filterLocation):string{
        $buttons="";
        for($i=0; $i<$amount; $i++){
            $buttons .= "<a href='index.php?page=".$filterLocation."&pagi=".$i."' class='pagiButton'>".$i."</a>";
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