<?php 
use Firebase\JWT\JWT;
class Controller_Users extends Controller_Rest
{

     // Funcion para crear Usuario

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
        // Se comprueba que no falten parametros
        if (! isset($_GET['username']) OR ! isset($_GET['password'])) 
        {
            // Aqui mandamos el mensaje por parametros
            $json = $this->response(array(
                'code' => 400,
                'message' => 'Parametro incorrecto, se necesita que el parametro se llame username, password y email',
                'data' => ''
                )
            );
            return $json;
        }

            //Creo un objeto del tipo model_users  y busco un usuario con el username y password dados


        $entry = Model_Users::find('all',array(
                'where' => array(
                     array('username', $_GET['username']),
                     array('password', $_GET['password'])
                    
            
                )
            )
        );
      
      
      //  return $this->response(array($entry));
        // Si encuentra el usuario : 
        if ($entry != null) {

            // reiniciar los indices del array (el indice del usuario que devuelve es el id, por eso los reiniciamos)

            $entry=Arr::reindex($entry);
            $json = $this->response(array(
                'code' => 200,
                'message' => 'Login correcto',
                'data' => array('token'=>$this->createToken($entry[0]->id,$entry[0]->username,$entry[0]->password,60))
                )
            );
            return $json;
        }
        // si no entuentra un usuario con esos datos
        else 
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'El usuario o contraseñas son incorrectas',
                'data' => ''
            ));
            return $json;
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
}
  