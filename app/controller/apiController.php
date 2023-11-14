<?php
     require_once ('./app/views/apiView.php');
     
    abstract class ApiController {
        private $data;
        private $view;
        
        function __construct() {
            $this->data = file_get_contents('php://input');
            $this->view = new ApiView();
        }

        function getData() {
            return json_decode($this->data);
        }

        function getView(){
            return $this->view;
        }
        
    }