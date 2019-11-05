<?php


class recipesControl{
    private $drinks = [];

    /*
     * RENDER EACH DRINK ON LIST PAGE
     */
    public function render(): string {
        $html = "";

        foreach ($this->drinks as $drink) {
            $html.=$drink->render();
        }
        return $html;

    }

    /*
     * ADD A DRINK TO THE ARRAY OF DRINKS
     */
    function add(drink $drink){
        $this->drinks[] = $drink;
    }

    public function deleteDrink(int $drinkNum){
        unset($this->drinks[$drinkNum]);
    }

    public function getDrink(int $drinkNum){
        return $this->drinks[$drinkNum];
    }

    public function findDrink(int $drinkId):drink{
        $i=0;
        foreach ($this->drinks as $drink){
            if($drink->getId()==$drinkId){
                return $this->drinks[$i];
            }
            $i++;
        }
    }

}