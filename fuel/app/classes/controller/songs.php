<?php 
use Firebase\JWT\JWT;
class Controller_Songs extends Controller_Rest
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
                if (! isset($_POST['title']) OR ! isset($_POST['url']) OR ! isset($_POST['artist'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Parametro incorrecto, se necesita que el parametro se llame title, url, artist',
                        'data' => ''
                        )
                    );
                    return $json;
                }
                $input = $_POST;
                // si los parametros estan vacios
                if ($input['title'] == '' OR $input['url'] == '' OR $input['artist'] == '' ) 
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
                $song = new Model_Songs();
                $song->title = $input['title'];
                $song->url = $input['url'];
                $song->artist = $input['artist'];
                $song->save();
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Cancion creada: ',
                    'data' => $input['title']
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
                return $json;
            }
        }
        else{
            $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Fallo de autentificacion',
                    'data' => ''
                    )
                );
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
            
            try{
                $input = $_POST;
                if ($input['id'] != "" && isset($input['id'])) {
                    $song = Model_Songs::find($input['id']);
                    if ($song == null) {
                            $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Error la cancion no existe',
                            'data' => Array(
                                'id'=>$input['id']
                                ))
                            );
                        return $json;
                    }
                    else
                    {
                        if (isset($input['title']) && $input['title'] != '') {
                            $song->title = $input['title'];
                        }
                        if (isset($input['url']) && $input['url'] != '') {
                            $song->url = $input['url'];
                        }
                        if (isset($input['artist']) && $input['artist'] != '') {
                            $song->artist = $input['artist'];
                        }
                    }
                    $title = $song->title;
                    $song->save();
                    $json = $this->response(array(
                    'code' => 200,
                    'message' => 'cancion guardada',
                    'data' => Array(
                                'titulo' => $title
                    ))
                    );
                    return $json;
                }
                else
                {
                    $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Error falta el id de la cancion',
                    'data' => ''
                        )
                    );
                    return $json;
                }
            }
            catch(Exception $e)
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

    public function get_songs()
    {
        $token = apache_request_headers()['Authorization'];
        $data = $this->decodeToken($token);
        // si el token es correcto
        if ($this->validateToken($token)) 
        {
            try{
                $songs = Model_Songs::find('all');
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Listado usuarios',
                    'data' => Arr::reindex($songs)
                    ));
                return $json;
            }
            catch(Exception $e)
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
        else{
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
            try 
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
                // si todo esta correcto
                $song = Model_Songs::find($input['id']);
                $title = $song->title;
                $song->delete();
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Cancion borrada: ',
                    'data' => array('title'=>$title)
                    )
                );
                return $json;
            }
            catch(Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => 'error interno del servidor',
                    'data' => $e->getMessage()
                    )
                );
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