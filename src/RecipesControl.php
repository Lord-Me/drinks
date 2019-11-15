<?php


class recipesControl{
    private $drinks = [];

    /*
     * RENDER EACH DRINK ON LIST PAGE
     */
    public function render(): string {
        $html = "";
        $side = 0;
        $paginator = 0;

        foreach ($this->drinks as $drink) {
            $html.=$drink->render($side);
            $side++;
            if($paginator%10 === 0){
                paginate($html);
                $html="";
            }
        }

        //CHECK IF EMPTY BECAUSE OF FILTERS
        if(empty($html)){
            $html = "<section>
                        <div class='mi-container-centered'>
                            <div class='container'>
                                <div class='row align-items-center'>
                                    <h1>No Search Results!</h1>
                                </div>
                            </div>
                        </div>
                    </section>";
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