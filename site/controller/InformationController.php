<?php
class InformationController
{
    function paymentPolicy()
    {
        require 'view/information/paymentPolicy.php';
    }

    function returnPolicy()
    {
        require 'view/information/returnPolicy.php';
    }

    function deliveryPolicy()
    {
        require 'view/information/deliveryPolicy.php';
    }
}
