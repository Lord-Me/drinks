<?php
//SANITIZE INPUTS
$authorId = filter_input(INPUT_GET, 'author', FILTER_SANITIZE_SPECIAL_CHARS) ?? NULL;
$searchValue = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
$time1 = filter_input(INPUT_POST, 'time1', FILTER_SANITIZE_SPECIAL_CHARS);
$time2 = filter_input(INPUT_POST, 'time2', FILTER_SANITIZE_SPECIAL_CHARS);

$author = $all = $pro = $ori = $search = $date = false;
$drinks = [];


//Check which filters are in use
if(!empty($authorId)){                          //Filter by author TODO try and get it to still search by author after adding another filter
    $author = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filterRadio = $_POST["categoryFilter"];

    if ($filterRadio == "pro") {                //Filter by pro
        $pro = true;
    }
    if ($filterRadio == "ori") {                //Filter by ori
        $ori = true;
    }
    if ($filterRadio == "all") {                //No category filter
        $all = true;
    }
    if(!empty($searchValue)){                   //Search bar used
        $search = true;
    }
    if(!empty($time1) && !empty($time2)){       //Date search used
        $date = true;
    }
}else{                                      //If no filters are used, display all by default
    $all = true;
}
/*
 * END FILTERS
 *
 * FILTER GET ORDER:
 *  category
 *  author
 *  date
 *  search
 */
//category
if($all){
    $drinks = $dm->getAll();
}elseif ($ori){
    $drinks = $dm->getByCategory(2);
}elseif ($pro){
    $drinks = $dm->getByCategory(1);
}

//author
if($author){
    $filteredDrinks=[];
    for($i=0; $i<count($drinks); $i++){
        $id = $drinks[$i]->getAuthor_id();
        if($id == $authorId){
            array_push($filteredDrinks, $drinks[$i]);
        }
    }
    $drinks = $filteredDrinks;
}

//time
if($date){
    $filteredDrinks=[];
    for($i=0; $i<count($drinks); $i++){
        $age = $drinks[$i]->getPublished_at();
        if(($age > $time1 && $age < $time2) || ($age < $time1 && $age > $time2)){ //see if its between time1 and time2
            array_push($filteredDrinks, $drinks[$i]);
        }
    }
    $drinks = $filteredDrinks;
}

//search
if($search){
    $filteredDrinks=[];
    for($i=0; $i<count($drinks); $i++){
        $title = $drinks[$i]->getTitle();
        $ingredients = $drinks[$i]->getIngredients();
        $content = $drinks[$i]->getContent();

        //Check searchValue word in title, ingredients and content with stripos.
        if(stripos($title, $searchValue) !== false || stripos($ingredients, $searchValue) !== false || stripos($content, $searchValue) !== false){
            array_push($filteredDrinks, $drinks[$i]);
        }
    }
    $drinks = $filteredDrinks;
}

//Order from newest to oldest
function sortFunction(Drink $a, Drink $b ) {
    return strtotime($b->getPublished_at()) - strtotime($a->getPublished_at());
}
usort($drinks, "sortFunction");