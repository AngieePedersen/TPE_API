<?php
require_once('./app/model/userModel.php');
require_once('./app/helpers/authHelper.php');

class UserApiController extends ApiController {
    private $model;
    private $authHelper;

    public function __construct(){
        parent::__construct();
        $this->model = new UserModel();
        $this->authHelper = new AuthHelper();
    }
    
    public function getModel(){
        return $this->model;
    }

    public function getAuthHelper(){
        return $this->authHelper;
    }

    public function getToken($params = []){
        $basic = $this->getAuthHelper()->getAuthHeaders();

        if(empty($basic)){
            $this->getView()->response(['msg' => 'No envio encabezados de Autenticacion'],401);
            return;
        }

        $basic = explode(" ", $basic);

        if($basic[0] != "Basic"){
            $this->getView()->response(['msg' => 'Los encabezados de Autenticacion son incorrectos'],401);
            return;
        }

        $userPass = base64_decode($basic[1]);
        $userPass = explode(":", $userPass);
        $email = $userPass[0];
        $pass = $userPass[1];

        $user = $this->getModel()->getUser($email);

        if($user && password_verify($pass,$user->password)){
            $payload = array('id'=> $user->id_usuario,
                            'email'=>$user->email);
            $token = $this->getAuthHelper()->createToken($payload);
            $this->getView()->response($token,200);
        }else{
            $this->getView()->response(['msg' => 'Usuario o Contraseña invalido'],401);
        }
    }

}