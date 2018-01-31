<?php 
use Firebase\JWT\JWT;
class Controller_Lists extends Controller_Rest
{
	public function post_create()
	{
		$token = apache_request_headers()['Authorization'];
        $user = $this->decodeToken($token);
        // si el token es correcto
        if ($this->validateToken($token)) 
        {
	        try 
	        {
	            // si no nos envian los parametros
	            if (! isset($_POST['title'])) 
	            {
	                $json = $this->response(array(
	                    'code' => 400,
	                    'message' => 'Parametro incorrecto, se necesita que el parametro se llame title',
	                    'data' => ''
	                    )
	                );
	                return $json;
	            }
	            $input = $_POST;
	            // si los parametros estan vacios
	            if ($input['title'] == '' ) 
	            {
	                $json = $this->response(array(
	                    'code' => 400,
	                    'message' => 'Error algun campo esta vacio',
	                    'data' => ''
	                    )
	                );
	                return $json;
	            }
	            // si todo esta correcto
	            $list = new Model_Lists();
	            $list->title = $input['title'];
	            $list->id_user = $user->data->id;
	            $list->save();
	            $json = $this->response(array(
	                'code' => 200,
	                'message' => 'Lista creada: ',
	                'data' => Array(
	                			'title'=> $input['title']
	                	)
	                )
	            );
	            return $json;
	        } 
	        catch (Exception $e) 
	        {
	            $json = $this->response(array(
	                'code' => 500,
	                'message' => 'error interno del servidor',
	                'data' => $e->getMessage()
	                )
	            );
	            echo $e;
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

    public function get_lists()
	{
		// recibir token del header y validar
        $token = apache_request_headers()['Authorization'];
        $user = $this->decodeToken($token);
        // si el token es correcto
        if ($this->validateToken($token)) 
        {
            try
            {
    	        $lists = Model_Lists::find('all', array(
    	    		'where' => array(
    	        		array('id_user', $user->data->id)
    	    			)
    	    		)
    	    	);
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Listado de listas',
                    'data' => Array(
                    	'lists'=>Arr::reindex($lists),
                    	'username'=>$user->data->username
                    	) 
                ));
                return $json;
            }catch(Exception $e)
            {
               $json = $this->response(array(
                    'code' => 500,
                    'message' => 'error interno del servidor',
                    'data' => $e->getMessage()
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

	public function post_addsong()
    {
        $token = apache_request_headers()['Authorization'];
        $user = $this->decodeToken($token);
        // si el token es correcto
        if ($this->validateToken($token)) 
        {
            try 
            {
                // si no nos envian los parametros
                if (! isset($_POST['id_list']) || ! isset($_POST['id_song'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Parametro incorrecto, se necesita que el parametro se llame id_list y id_song',
                        'data' => ''
                        )
                    );
                    return $json;
                }
                $input = $_POST;
                // si los parametros estan vacios
                if ($input['id_list'] == '' || $input['id_song'] == '') 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error algun campo esta vacio',
                        'data' => ''
                        )
                    );
                    return $json;
                }
                $list = Model_Lists::find($input['id_list']);
                if ($list->id_user == $user->data->id) {
                    $contienen = new Model_Contienen();
                    $contienen->id_song = $input['id_song'];
                    $contienen->id_list = $input['id_list'];
                    $contienen->save();
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Cancion añadida con exito: ',
                        'data' => Array(
                                    'list'=> $list->title
                            )
                        )
                    );
                    return $json;
                }else
                {
                    $json = $this->response(array(
                    'code' => 400,
                    'message' => 'error la lista no pertenece al usuario',
                    'data' => ''
                    )
                );
                echo $e;
                return $json;
                }               
            } 
            catch (Exception $e) 
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => 'error interno del servidor',
                    'data' =>$e-> getmessage()
                    )
                );
                echo $e;
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

    public function post_delete()
    {
        // valdiar token
        $token = apache_request_headers()['Authorization'];
        $data = $this->decodeToken($token);
        // si el token es correcto
        if ($this->validateToken($token)) 
        {
            // si no nos envian los parametros
            if (! isset($_POST['id'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Parametro incorrecto, se necesita que el parametro se llame id',
                    'data' => ''
                    )
                );
                return $json;
            }
            $input = $_POST;
            // si los parametros estan vacios
            if ($input['id'] == '') 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Error algun campo esta vacio',
                    'data' => ''
                    )
                );
                return $json;
            }
            try
            {
                // si todo esta correcto
                $list = Model_Lists::find($input['id']);
                // si la lista es del usuario logeado
                if ($list->id_user == $data->data->id) {
                    $title = $list->title;
                    $list->delete();
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Lista borrada: ',
                        'data' => array('title'=>$title
                            )
                        )
                    );
                    return $json;
                }
                else
                {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Error la lista no pertenece al usuario logeado',
                    'data' => ''
                    )
                );
                }
            }catch(Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => 'error interno del servidor',
                    'data' => $e->getMessage()
                    )
                );
                echo $e;
                return $json;
            }
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

    

    public static function decodeToken($jwt)
    {
        $key = 'my_secret_key';
        $data = JWT::decode($jwt, $key, array('HS256'));
        return $data;
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
}    