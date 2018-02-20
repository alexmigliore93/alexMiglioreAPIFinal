<?php 
use Firebase\JWT\JWT;
class Controller_Users extends Controller_Rest
{

    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create()
    {
        try 
        {
                // Aqui comprobamos si no existe alguno de los parametros, (! -> significa si no existe , isset verifica si la variable esta definida y no es null )

            if (! isset($_POST['username']) OR ! isset($_POST['password']) OR ! isset($_POST['email'])) 
            {   
                //Si algun campo esa vacio, mandamos un eror 400

                return $this->createResponse("400","Parametro incorrecto, se necesita que el parametro se llame username, password y email","");
            }

            $input = $_POST;
          
            // si los parametros estan vacios

            if ($input['username'] == '' OR $input['password'] == ''OR $input['email'] == '') 
            {
                return $this->createResponse(400,"Error algun campo esta vacio","");
            }
            // si todo esta correcto
            //Creo un objeto del tipo model_users 
            $user = new Model_Users();
            // El atributo username del objeto user cambia su estado a input username
            $user->username = $input['username'];
            $user->email = $input['email'];
            $user->password = $input['password'];
            $user->id_rol = '2';
            $user->id_privacity = '1';
            $user->save();
            // Si todo es correcto mandamos un 200 y el usuario ha sido creado.
            return $this->createResponse(200,'Usuario creado' ,array('usuario'=>$input['username']));

            // return $this->createResponse(200, 'Usuario creado', ['user' => $newUser]);
        } 

        // Catch es una execpion 
        catch (Exception $e) 
        {
            //Aqui se mueestra si al crear un usuario y hay un problema con la API, sale el error 500

            return $this->createResponse(500,"Error del servidor ",array('error'=>$e->getMessage()));
        }
    }

    public function get_login()
    {
        $username = $_GET['username'];
        $password = $_GET['password'];
        if(!empty($username) && !empty($password)){
            $BDuser = Model_Users::find('first', array(
             'where' => array(
                 array('username', $username),
                 array('password', $password)
                 ),
             ));

            if(count($BDuser) == 1){
             $time = time();
             $token = array(
                'iat' => $time,
                'data' => [ // información del usuario
                'id' => $BDuser->id,
                'username' => $username,
                'password'=> $password
                ]
                );

             $jwt = JWT::encode($token, $this->key);
             $this->Mensaje('200', 'usuario logueado', $jwt);
         } else {
            $this->Mensaje('400', 'usuario o contraseña incorrectos', $username);
        }
    }else {
        $this->Mensaje('400', 'parametros vacios', $username);
    }
}

    public function post_update()
    {
        $token = apache_request_headers()['Authorization'];
        $data = $this->decodeToken($token);
        // si el token es correcto
        if ($this->validateToken($token)) 
        {
            
            if (! isset($_POST['password']))
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Parametro incorrecto, se necesita que el parametro se password',
                    'data' => ''
                    )
                );
                return $json;
            }
            else
            {
                $input = $_POST;
                $user = Model_Users::find($data->data->id);
                $user->password = $input['password'];
                $userName = $user->username;
                $token=$this->createToken($user->id,$user->username,$user->password,60);
                $user->save();
                $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Usuario guardado con exito',
                        'data' => array('username'=>$userName,
                                        'token'=> $token                                        )
                        )
                    );
                
                return $json;
            }
        }
        else
        {
            $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Fallo de autentificacion',
                    'data' => ''
                    )
                );
            return $json;
        }
    }

    public function post_modifyUserAdmin(){

    $jwt = apache_request_headers()['Authorization'];

    try{

        if(!empty($jwt)){

            $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
            
            $username = $tokenDecode->data->username;
            $password = $tokenDecode->data->password;

            $id = $_POST["id"];

            $input = $_POST;

        $BDuser = Model_Users::find('first', array(
            'where' => array(
                array('username', $username),
                array('password', $password)
                ),
            ));
        $BDuser2 = Model_Users::find('first', array(
            'where' => array(
                array('id', $id)
                ),
            ));

        if($BDuser != null){
            if ($BDuser2 != null) {
               
                $BDuser2->username = $input['username'];
                $BDuser2->password = $input['password'];
                $BDuser2->save();
                $this->Mensaje('200', 'usuario modificado', $id);
            } 
          }
        }
            else {
            $this->Mensaje('400', 'usuario invalido', $id);
         
        }
    } catch(Exception $e) {
        $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
    } 
}

    public function get_users()
    {
        // recibir token del header y validar
        $token = apache_request_headers()['Authorization'];
        // si el token es correcto
        if ($this->validateToken($token)) 
        {
            $users = Model_Users::find('all');
            $json = $this->response(array(
                'code' => 200,
                'message' => 'Listado usuarios',
                'data' => Arr::reindex($users)
            ));
            return $json;
        }else{
            $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Fallo de autentificacion',
                    'data' => ''
                    )
                );
            return $json;
        }
    }

    
    private function validateToken ($token)
    {
        $data = $this->decodeToken($token);
        $username = $data->data->username;
        $password = $data->data->password;
        $id = $data->data->id;
        $entry = Model_Users::find('all',array(
                'where' => array(
                    array('username', $username),
                    array('password', $password),
                    array('id', $id)
            
                )
            )
        );
        if ($entry == null) 
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function createToken($id,$name,$password,$timeEx)
    {
        $key = 'my_secret_key';
        $time = time();
        $token = array(
                'iat' => $time, // Tiempo que inició el token
                'exp' => $time + (9999*$timeEx), // Tiempo que expirará el token (+1 hora)
                'data' => [ // información del usuario
                'id' => $id,
                'username' => $name,
                'password' => $password
                ]
            );
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }
    
    public static function decodeToken($jwt)
    {
        $key = 'my_secret_key';
        $data = JWT::decode($jwt, $key, array('HS256'));
        return $data;
    }

    private function createResponse ($code,$message,$data=null)
    {
        $json = $this->response(array(
                    'code' => $code,
                    'message' => $message,
                    'data' => $data
                    )
                );
        return $json;
    }

    public function post_deleteUser(){
    $jwt = apache_request_headers()['Authorization'];
    //print($_POST["id"]);
    try{
        if(!empty($jwt)){

            $id = $_POST["id"];
            
            $BDuser = Model_Users::find('first', array(
                'where' => array(
                    array('id', $id)
                    ),
                ));
            if($BDuser != null){

                $BDuser->delete();

                $this->Mensaje('200', 'usuario borrado', $BDuser);
            } else {
                $this->Mensaje('400', 'usuario invalido', $input['username']);
            }
        } else {
            $this->Mensaje('400', 'token vacio', $jwt);
        }
    }catch(Exception $e) {
        $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
    } 
}

    function post_delete()
    {
        try{
            $jwt = apache_request_headers()['Authorization'];
            if($this->validateToken($jwt)){
                $token = $this->decodeToken($jwt);
                $id = $token->data->id;   
                $usuario = Model_Users::find($id);

                if($usuario != null){
                    $usuario->delete();
                    return $this->createResponse(200, 'Usuario borrado', ['usuario' => $usuario]);
                }else{
                    return $this->createResponse(400, 'El usuario introducido no existe');
                }
                
            }else{
                
                return $this->createResponse(400, 'No tienes permiso para realizar esta acción');
            }
        }catch (Exception $e){

            return $this->createResponse(500, $e->getMessage());
        }  
    }

    function Mensaje($code, $message, $data){
    $json = $this->response(array(
        'code' => $code,
        'message' => $message,
        'data' => $data
        ));
    return $json;
    }

}
  