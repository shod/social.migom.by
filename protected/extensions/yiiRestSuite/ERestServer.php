<?php
/**
 * ERestServer.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @author		Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright	2011 CleverIT http://www.cleverit.com.pl
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 * @since v1.0
 */

/**
 * ERestServer
 *
 * This is merge work of tyohan, Alexander Makarov and mine
 * @since v1.0
 */
class ERestServer extends CApplicationComponent
{
	/**
     * @var string host:port
     *
     * Correct syntax is:
     * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
     *
     * @example mongodb://localhost:27017
     * @since v1.0
     */
    public $connectionString;

    /**
     * @var Mongo $_mongoConnection instance of key
     */
	private $_mongoConnection;

	/**
	 * If set to TRUE findAll* methods of models, will return {@see EMongoCursor} instead of
	 * raw array of models.
	 *
	 * Generally you should want to have this set to TRUE as cursor use lazy-loading/instaninating of
	 * models, this is set to FALSE, by default to keep backwards compatibility.
	 *
	 * Note: {@see EMongoCursor} does not implement ArrayAccess interface and cannot be used like an array,
	 * because offset access to cursor is highly ineffective and pointless.
	 *
	 * @var boolean $useCursor state of Use Cursor flag (global scope)
	 */
	public $useCursor = false;

	/**
	 * Storage location for temporary files used by the GridFS Feature.
	 * If set to null, component will not use temporary storage
	 * @var string $gridFStemporaryFolder
	 */
	public $gridFStemporaryFolder = null;

    public $http_auth = null;
    public $http_user = null;
    public $http_pass = null;
    public $password;

    public $servers;
    public $supported_formats = array(
        'xml'                => 'application/xml',
        'json'               => 'application/json',
        'serialize'          => 'application/vnd.php.serialized',
        'php'                => 'text/plain',
        'csv'                => 'text/csv'
    );
    public $auto_detect_formats = array(
        'application/xml'                => 'xml',
        'text/xml'                       => 'xml',
        'application/json'               => 'json',
        'text/json'                      => 'json',
        'text/csv'                       => 'csv',
        'application/csv'                => 'csv',
        'application/vnd.php.serialized' => 'serialize'
    );
    private $format;
    private $mime_type;

    private $response_string;
    private $_curl;
    private $_headers = array();
    private $_conection;


	/**
	 * Returns Mongo connection instance if not exists will create new
	 *
	 * @return Mongo
	 * @throws ERestException
	 * @since v1.0
	 */
	public function getConnection()
	{
        Yii::log('REST Class Initialized');
        $this->_curl = new ERestCurl();

		if($this->_mongoConnection === null)
		{
			try
			{
				Yii::trace('Opening Rest connection', 'ext.Rest.ERestServer');
                $this->_conection = yii::app()->cache->get($this->password . '_suid');
                if (!$this->_conection) {
                    $responce = $this->_json($this->get('auth/login/' . $this->password));

                    if (!isset($responce->content->suid)) {
                        throw new ERestException(Yii::t(
                            'yii',
                            'ERestServer failed to open connection: {error}'
                        ), $responce->content->message);
                    }
                    $this->_mongoConnection = $responce->content->suid;
                    yii::app()->cache->set($this->password . '_suid', $this->_mongoConnection);
                    $this->connected = true;

                }
				return $this;
			}
			catch(Exception $e)
			{
				throw new ERestException(Yii::t(
					'yii',
					'ERestServer failed to open connection: {error}',
					array('{error}'=>$e->getMessage())
				), $e->getCode());
			}
		}
		else
			return $this;
	}


	/**
	 * Set the connection
	 *
	 * @param Mongo $connection
	 * @since v1.0
	 */
//	public function setConnection(Mongo $connection)
//	{
//		$this->_mongoConnection = $connection;
//	}

	/**
	 * Get MongoDB instance
	 * @since v1.0
	 */
	public function getServerInstance()
	{
		if($this->servers === null)
			return $this->servers = $this->getConnection();
		else
			return $this->servers;
	}

	/**
	 * Set MongoDB instance
	 * Enter description here ...
	 * @param string $name
	 * @since v1.0
	 */
//	public function setServerInstance($name)
//	{
//		$this->servers = $this->getConnection()->selectDb($name);
//	}

	/**
	 * Closes the currently active Mongo connection.
	 * It does nothing if the connection is already closed.
	 * @since v1.0
	 */
	protected function _close(){
        if($this->_mongoConnection!==null){
            $this->_mongoConnection=null;
            Yii::trace('Closing MongoDB connection', 'ext.Rest.ERestServer');
        }
	}

	/**
	 * If we have don't use presist connection, close it
	 * @since v1.0
	 */
	public function __destruct(){
//        $this->_curl->set_default();
        $this->_close();
    }

    /**
     * Logs a message.
     *
     * @param string $message Message to be logged
     * @param string $level Level of the message (e.g. 'trace', 'warning',
     * 'error', 'info', see CLogger constants definitions)
     */
    public static function log($message, $level = 'error')
    {
        Yii::log($message, $level, __CLASS__);
    }

    /**
     * Dumps a variable or the object itself in terms of a string.
     *
     * @param mixed variable to be dumped
     */
    protected function dump($var = 'dump-the-object', $highlight = true)
    {
        if ($var === 'dump-the-object') {
            return CVarDumper::dumpAsString($this, $depth = 15, $highlight);
        } else {
            return CVarDumper::dumpAsString($var, $depth = 15, $highlight);
        }
    }

    public function get($uri, $params = array(), $format = NULL)
    {
        if ($params) {
            $uri .= '?' . (is_array($params) ? http_build_query($params) : $params);
        }
        return $this->_call('get', $uri, NULL, $format);
    }

    public function post($uri, $params = array(), $format = NULL)
    {
        return $this->_call('post', $uri, $params, $format);
    }

    public function put($uri, $params = array(), $format = NULL)
    {
        return $this->_call('put', $uri, $params, $format);
    }

    public function delete($uri, $params = array(), $format = NULL)
    {
        return $this->_call('delete', $uri, $params, $format);
    }

    public function api_key($key, $name = 'X-API-KEY')
    {
        $this->_curl->http_header($name, $key);
    }

    public function set_header($name, $value)
    {
        $this->_headers[$name] = $value;
    }

    public function language($lang)
    {
        if (is_array($lang)) {
            $lang = implode(', ', $lang);
        }

        $this->_curl->http_header('Accept-Language', $lang);
    }

    private function _call($method, $uri, $params = array(), $format = NULL)
    {
        $this->_set_headers();

        // Initialize cURL session
        if ($uri) {
            $uri = '/' . $uri;
        }
        $this->_curl->create($this->connectionString . $uri);
        // If authentication is enabled use it
        if ($this->http_auth != '' && $this->http_user != '') {
            $this->_curl->http_login($this->http_user, $this->http_pass, $this->http_auth);
        }

        // We still want the response even if there is an error code over 400
        // Call the correct method with parameters
        $this->_curl->{$method}($params);

        // Execute and return the response from the REST server
        $response = $this->_curl->execute();

        // Format and return
        if ($format !== NULL) {
            $this->format($format);
            return $this->_format_response($response);
        } else
            return $response;
    }

    // If a type is passed in that is not supported, use it as a mime type
    public function format($format)
    {
        if (array_key_exists($format, $this->supported_formats)) {
            $this->format = $format;
            $this->mime_type = $this->supported_formats[$format];
        } else {
            $this->mime_type = $format;
        }

        return $this;
    }

    public function debug()
    {
        $request = $this->_curl->debug();

        echo "=============================================<br/>\n";
        echo "<h2>REST Test</h2>\n";
        echo "=============================================<br/>\n";
        echo "<h3>Request</h3>\n";
        echo $request['url'] . "<br/>\n";
        echo "=============================================<br/>\n";
        echo "<h3>Response</h3>\n";

        if ($this->response_string) {
            echo "<code>" . nl2br(htmlentities($this->response_string)) . "</code><br/>\n\n";
        } else {
            echo "No response<br/>\n\n";
        }

        echo "=============================================<br/>\n";

        if ($this->_curl->error_string) {
            echo "<h3>Errors</h3>";
            echo "<strong>Code:</strong> " . $this->_curl->error_code . "<br/>\n";
            echo "<strong>Message:</strong> " . $this->_curl->error_string . "<br/>\n";
            echo "=============================================<br/>\n";
        }

        echo "<h3>Call details</h3>";
        echo "<pre>";
        print_r($this->_curl->info);
        echo "</pre>";
    }

    // Return HTTP status code
    public function status()
    {
        return $this->info('http_code');
    }

    // Return curl info by specified key, or whole array
    public function info($key = null)
    {
        return $key === null ? $this->_curl->info : @$this->_curl->info[$key];
    }

    // Set custom options
    public function option($code, $value)
    {
        $this->_curl->option($code, $value);
    }

    private function _set_headers()
    {
        if (!array_key_exists("Accept", $this->_headers))
            $this->set_header("Accept", $this->mime_type);
        foreach ($this->_headers as $k => $v) {
            $this->_curl->http_header(sprintf("%s: %s", $k, $v));
        }
    }

    private function _format_response($response)
    {
        $this->response_string = & $response;

        // It is a supported format, so just run its formatting method
        if (array_key_exists($this->format, $this->supported_formats)) {
            return $this->{"_" . $this->format}($response);
        }

        // Find out what format the data was returned in
        $returned_mime = @$this->_curl->info['content_type'];

        // If they sent through more than just mime, stip it off
        if (strpos($returned_mime, ';')) {
            list($returned_mime) = explode(';', $returned_mime);
        }

        $returned_mime = trim($returned_mime);

        if (array_key_exists($returned_mime, $this->auto_detect_formats)) {
            return $this->{'_' . $this->auto_detect_formats[$returned_mime]}($response);
        }

        return $response;
    }

    // Encode as JSON
    private function _json($string)
    {
        return json_decode(trim($string));
    }


    public function query($controller, $function = '', $id = null, $method = 'get', $params = array())
    {
        $server = $this->getApiTitle();
        $this->_rest->initialize($server);
        $params['key'] = $this->_getSuid($server);
        $uri = $this->_createUri($controller, $function, $id);
        Yii::trace(get_class($this) . '.query()', 'RESTClient');
        $responce = $this->_rest->{$method}($uri, $params, 'json');
        if($responce->content->success !== true){
            Yii::log($responce->content->message, CLogger::LEVEL_ERROR, 'api_client');
        }
        $this->_responce = $responce;
//        $this->_rest->debug();
        return $responce;
    }

}
