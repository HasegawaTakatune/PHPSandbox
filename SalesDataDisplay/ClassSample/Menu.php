<?php

require 'baseView.php';

class Menu extends baseView{

    public function ShowView()
    {
        $hier = $this->hierarchy;
        $view = "
        <h6>${hier}</h6>
        </br>
        </br>";
        return $view;
    }

}

?>