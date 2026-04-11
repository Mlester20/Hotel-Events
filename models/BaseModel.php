<?php

    class BaseModel{
        protected $con;
        public function __construct($con){
            $this->con = $con;
        }
    }
?>