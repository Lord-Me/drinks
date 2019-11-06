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
    function add(Drink $drink){
        $this->drinks[] = $drink;
    }


}