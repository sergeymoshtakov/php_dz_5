<?php

class Category 
{
    public $name, $list_products;

    function __construct($_name, $_list_products)
    {
        $this->name = $_name;
        $this->list_products = $_list_products;
    }

    function getCategoryName(){
        return "$this->name";
    }

    function getCategoryProducts(){
        return $this->list_products;
    }
}