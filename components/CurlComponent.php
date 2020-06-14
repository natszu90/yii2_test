<?php

namespace app\components;

use yii\base\Component;

class CurlComponent extends Component
{
    private $_config;
    public $static_token = '494a8e00b19ac7b582f3ffadf8d5b23fa3442716'; // static access token
    public $username = 'natszu90';  // username

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->_config = [
            'host' => 'https://api.imgur.com/',
            'client_id' => '875beb88a67f5fc',
            'client_secret' => 'da53af19850bf5ba71282ba5a29ee5d959a7b727',
            'refresh_token' => '8a51aa4358c5d27a85aa98346763898b74e8674f',
            'grant_type' => 'refresh_token'
        ];
    }


    public function post($endpoint, $data = [])
    {
        // list of apis from imgur
        $api_ids = [
            'create_access_token' => [
                'name' => 'create_access_token',
                'url' => $this->_config['host'] . 'oauth2/token',
            ],
            'create_album' => [
                'name' => 'create_album',
                'url' => $this->_config['host'] . '3/album',
            ],
            'upload_image' => [
                'name' => 'upload_image',
                'url' => $this->_config['host'] . '3/upload',
            ],
        ];

        if (!empty($api_ids[$endpoint])) {
           
            // prep params on generating access token
            if($api_ids[$endpoint]['name'] == 'create_access_token'){
                $data = [
                    'client_id'     => $this->_config['client_id'],
                    'client_secret' => $this->_config['client_secret'],
                    'grant_type'    => $this->_config['grant_type'],
                    'refresh_token' => $this->_config['refresh_token']
                    
                ];
            }

            // Set url
            $url = $api_ids[$endpoint]['url'];
            $params = $data;

            if(!empty($data['accessToken'])) {
                $header = 'Authorization: Bearer ' . $data['accessToken'];
                unset($data['accessToken']);
                unset($params['accessToken']);
            } else if(isset($data['unAuthorize'])) {
                $header = 'Authorization: Client-ID ' . $this->_config['client_id'];
            } else {
                $header = '';
            }
          
            $response = $this->_post($url, $params, $header);

            return [
                'request_url' => $url,
                'request_body' => $params,
                'response' => json_decode($response['response']),
                'http_code' => $response['code']
            ];
        }
        return FALSE;
    }


    public function get($endpoint, $data = [])
    {
        // list of apis from imgur
        $api_ids = [
            'get_album' => [
                'name'=> 'get_album',
                'url'=> $this->_config['host'] . '3/album/' . $data['album_id']
            ],
            
        ];

        if (!empty($api_ids[$endpoint])) {
            
            // Set url
            $url = $api_ids[$endpoint]['url'];
            $params = array_merge([], []);

            if(!empty($data['accessToken'])) {
                $header = 'Authorization: Bearer ' . $data['accessToken'];
                unset($data['accessToken']);
                unset($params['accessToken']);
            } else {
                $header = '';
            }

            $response = $this->_get($url, $params, $header);

            return [
                'request_url' => $url,
                'request_body' => $params,
                'response' => json_decode($response['response']),
                'http_code' => $response['code']
            ];
           
        }
        return FALSE;
    }


    public function delete($endpoint, $data = [])
    {
        $data['username'] = isset($data['username']) ? $data['username'] : '';
        $data['album_id'] = isset($data['album_id']) ? $data['album_id'] : '';
        $data['image_id'] = isset($data['image_id']) ? $data['image_id'] : '';

        // list of apis from imgur
        $api_ids = [
            'delete_album' => [
                'name' => 'delete_album',
                'url' => $this->_config['host'] . '3/account/' . $data['username'] . '/album/' . $data['album_id'],
            ],
            'delete_image' => [
                'name' => 'delete_image',
                'url' => $this->_config['host'] . '3/image/' . $data['image_id'],
            ],
        ];

        if (!empty($api_ids[$endpoint])) {
           
            // Set url
            $url = $api_ids[$endpoint]['url'];
            $params = $data;

            if(!empty($data['accessToken'])) {
                $header = 'Authorization: Bearer ' . $data['accessToken'];
                unset($data['accessToken']);
                unset($params['accessToken']);
            } else if(isset($data['unAuthorize'])) {
                $header = 'Authorization: Client-ID ' . $this->_config['client_id'];
            } else {
                $header = '';
            }
          
            $response = $this->_delete($url, $params, $header);

            return [
                'request_url' => $url,
                'request_body' => $params,
                'response' => json_decode($response['response']),
                'http_code' => $response['code']
            ];
        }
        return FALSE;
    }


    private function _get($url, $params, $headers)
    {
        try {
            $curl = $this->_curl_init($url, $headers);
            return $this->_exec($curl);
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function _post($url, $params, $headers)
    {
        try {
            $curl = $this->_curl_init($url, $headers);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            return $this->_exec($curl);
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function _delete($url, $params, $headers)
    {
        try {
            $curl = $this->_curl_init($url, $headers);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            return $this->_exec($curl);
        } catch (Exception $e) {
            return FALSE;
        }
    }

    private function _exec($ch)
    {
        try {
          
            $response = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (FALSE === $response)
                throw new Exception(curl_error($ch), curl_errno($ch));

            curl_close($ch);
        } catch (Exception $e) {
            $response =  json_encode(['error' => true], true);
            $code = $e->getCode();
        }
        return [
            'response' => $response,
            'code' => $code
        ];
    }

    private function _curl_init($url, $headers)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", $headers]);

        return $ch;
    }
}
