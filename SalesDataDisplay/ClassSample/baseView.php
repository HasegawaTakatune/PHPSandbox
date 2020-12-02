<?php

abstract class baseView{

    protected string $hierarchy = "";

    public function init($hierarchy = ""){
        $this->hierarchy = $hierarchy;
    }

    abstract public function ShowView();

}

?>