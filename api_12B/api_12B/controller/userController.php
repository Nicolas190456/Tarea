<?php
class UserController{
    private $_method;
    private $_complement;
    private $_data;

    function __construct($method, $complement, $data){
        $this->_method = $method;
        $this->_complement = $complement == null ? 0: $complement;
        $this->_data = $data != 0 ? $data : "";
    }
    public function index(){
        switch($this->_method){

            case "GET":
                if ($this->_complement == 0) {
                    $user = UserModel::getUsers(0);
                    $json = $user;
                    echo json_encode($json,true);
                    return;
                }else{
                    $user = UserModel::getUsers($this->_complement);
                    $json = $user;
                    echo json_encode($json,true);
                    return;
                }

            case "POST":
                $createUser = UserModel::createUser($this->generateSalting());
                $json = array(
                    "response:"=>$createUser
                );
                echo json_encode($json,true);
                return;

            case "PUT":
                    $updateResult = $this->updateUser($this->_complement, $this->_data);
                    echo json_encode($updateResult, JSON_UNESCAPED_UNICODE);
                    break;

            case "ACTIVATE":
                $activateResult = $this->activateUser($this->_complement);
                echo json_encode($activateResult, JSON_UNESCAPED_UNICODE);
                break;
                    
            case "DELETE":
                    $deleteResult = $this->deleteUser($this->_complement);
                    echo json_encode($deleteResult, JSON_UNESCAPED_UNICODE);
                    break;

            default :
                $json = array(
                    "ruta:"=>"not found",
                );
                echo json_encode($json,true);
                return;
        }

    }
    private function generateSalting(){
        $trimmedData="";
        if (($this->_data != "") || (!empty($this->_data))){
            $trimmedData = array_map('trim',$this->_data);
            $trimmedData['use_pss'] = md5($trimmedData['use_pss']);
            //Generando Salting para credenciales
            $identifier = str_replace("$", "ue3", crypt($trimmedData["use_mail"], 'ue56'));
            $key = str_replace("$", "2023", crypt($trimmedData["use_mail"], '321321321321'));
            $trimmedData['us_identifier'] = $identifier;
            $trimmedData['us_key'] = $key;
            return $trimmedData;
        }
    }
}
?>