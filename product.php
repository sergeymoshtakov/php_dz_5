<?php 

class Product {
    public $name, $price;

    function __construct($_name, $_price)
    {
        $this->name = $_name;
        $this->price = $_price;
    }

    function  getProduct(){
        return "Name: $this->name; price: $this->price";
    }
}