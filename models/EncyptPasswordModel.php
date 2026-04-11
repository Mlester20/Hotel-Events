<?php

    class EncryptModel{
        //create a hash passes into model to access globally 
        public function hashPassword($password){
            return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        }
    }

?>