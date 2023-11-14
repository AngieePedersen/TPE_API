<?php
require_once ('./app/model/model.php');
class UserModel extends Model{
    
    public function getUser($email){
        $query = $this->getDB()->prepare("SELECT * FROM `usuarios` WHERE UserName = ?");
        $query->execute([$email]);
        
        return $query->fetch(PDO::FETCH_OBJ);
    }
}