<?php
class Filter
{
    private $filters = [
        "author" => false,
        "all" => false,
        "pro" => false,
        "ori" => false,
        "search" => false,
        "titleSearch" => false,
        "date" => false
    ];
    private $drinks = [];
    private $authorId;
    private $searchValue;
    private $titleSearchValue;
    private $filterRadio;
    private $time1;
    private $time2;
    private $dm;

    function __construct($author_id)
    {
        if(empty($author_id)) {
            $this->authorId = filter_input(INPUT_GET, 'author', FILTER_SANITIZE_SPECIAL_CHARS) ?? NULL;
        }else{
            $this->authorId = $author_id;
        }
        $this->searchValue = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->titleSearchValue = filter_input(INPUT_GET, 'titleSearch', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->filterRadio = filter_input(INPUT_GET, 'categoryFilter', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->time1 = filter_input(INPUT_GET, 'time1', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->time2 = filter_input(INPUT_GET, 'time2', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /*
     * FILTER CHECKS
     */
    function checkAuthorId(): string
    {
        if (!empty($this->authorId)) {                              //Filter by author
            $authorUrl = "&author=" . $this->authorId;
            $this->filters["author"] = true;
        } else {
            $authorUrl = "";
        }
        return $authorUrl;
    }

    function checkFilterRadio():void
    {
        if ($this->filterRadio == "pro") {                          //Filter by pro
            $this->filters["pro"] = true;
        } elseif ($this->filterRadio == "ori") {                    //Filter by ori
            $this->filters["ori"] = true;
        } elseif ($this->filterRadio == "all") {                    //No category filter
            $this->filters["all"] = true;
        }
    }

    function checkSearchValue(): bool
    {
        if (!empty($this->searchValue)) {                           //Search bar used
            $this->filters["search"] = true;
            return true;
        }
        return false;
    }

    function checkTitleSearchValue(): bool
    {
        if (!empty($this->titleSearchValue)) {                      //Search bar used (title)
            $this->filters["titleSearch"] = true;
            return true;
        }
        return false;
    }

    function checkDate(): bool{
        if (!empty($this->time1) && !empty($this->time2)) {         //Date search used
            $this->filters["date"] = true;
            return true;
        }
        return false;
    }

    function setAll(){
        $this->filters["all"] = true;
    }


    /*
     * DRINK FILTERS
     */
    function runFilter(DrinkModel $dm):void{
        $this->dm = $dm;
        /*
         * FILTER GET ORDER:
         *  category
         *  author
         *  date
         *  search
         */

        //category
        if($this->filters["all"]){
            $this->drinks = $this->dm->getAll();
        }elseif ($this->filters["ori"]){
            $this->drinks = $this->dm->getByCategory(2);
        }elseif ($this->filters["pro"]){
            $this->drinks = $this->dm->getByCategory(1);
        }

        //author
        if($this->filters["author"]){
            $filteredDrinks=[];
            for($i=0; $i<count($this->drinks); $i++){
                $id = $this->drinks[$i]->getAuthor_id();
                if($id == $this->authorId){
                    array_push($filteredDrinks, $this->drinks[$i]);
                }
            }
            $this->drinks = $filteredDrinks;
        }

        //time
        if($this->filters["date"]){
            $filteredDrinks=[];
            for($i=0; $i<count($this->drinks); $i++){
                $age = $this->drinks[$i]->getPublished_at();
                if(($age > $this->time1 && $age < $this->time2) || ($age < $this->time1 && $age > $this->time2)){ //see if its between time1 and time2
                    array_push($filteredDrinks, $this->drinks[$i]);
                }
            }
            $this->drinks = $filteredDrinks;
        }

        //search
        if($this->filters["search"]){
            $filteredDrinks=[];
            for($i=0; $i<count($this->drinks); $i++){
                $title = $this->drinks[$i]->getTitle();
                $ingredients = $this->drinks[$i]->getIngredients();
                $content = $this->drinks[$i]->getContent();

                //Check searchValue word in title, ingredients and content with stripos.
                if(stripos($title, $this->searchValue) !== false || stripos($ingredients, $this->searchValue) !== false || stripos($content, $this->searchValue) !== false){
                    array_push($filteredDrinks, $this->drinks[$i]);
                }
            }
            $this->drinks = $filteredDrinks;
        }

        //search
        if($this->filters["titleSearch"]){
            $filteredDrinks=[];
            for($i=0; $i<count($this->drinks); $i++){
                $title = $this->drinks[$i]->getTitle();

                //Check searchValue word in title, ingredients and content with stripos.
                if(stripos($title, $this->titleSearchValue) !== false){
                    array_push($filteredDrinks, $this->drinks[$i]);
                }
            }
            $this->drinks = $filteredDrinks;
        }
    }

    function runSort():void{
        //Order from newest to oldest
        function sortFunction(Drink $a, Drink $b ) {
            return strtotime($b->getPublished_at()) - strtotime($a->getPublished_at());
        }
        usort($this->drinks, "sortFunction");
    }

    function getDrinks():array{
        return $this->drinks;
    }
}