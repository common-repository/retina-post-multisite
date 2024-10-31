<?php
/* 
 * PHP class for calling RetinaPost
 *    - Documentation and latest version
 *          http://RetinaPost.com/php
 *    - Get a API Key
 *          https://RetinaPost.com/register
 *
 * Copyright (c) 2011 RetinaPost -- http://RetinaPost.com
 * AUTHOR: Dan Negrea
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
 
class RetinaPost {
	public		$publicKey;		// public  key obtained from RetinaPost.com
	public 		$privateKey;	// private key obtained from RetinaPost.com	
		
	protected   $result;		// previous result
	protected 	$error;
	protected 	$request_server 			= 	'http://RetinaPost.com/core_2/request.php';
	protected 	$request_server_noscript 	= 	'http://RetinaPost.com/core_2/request_noscript.php';
	protected 	$request_server_secure 		= 	'http://RetinaPost.com/core_2/request.php';
	protected 	$verify_server 				= 	'RetinaPost.com';
	protected 	$verify_url 				= 	'/core_2/verify.php';
	protected 	$version 					= 	'1.4';
	protected 	$singup_url 				= 	'http://www.RetinaPost.com/register';

	/**
	 *	Constructor
	**/
	function __construct(){			
	}

	/**
	 *	Get the error
	**/
	public function get_error() {
		return $this->error;
	}
	
	/**
	 * Gets the challenge HTML (javascript and noscript version).
	 * This is called with an array as a parameter
	 * get(array("model"=>"text", "title"=>$title, "description"=>$description, "link"=>$link, "message"=>$message))
	 * @param string  $model 		What type of challange content will you use ('model' or 'feed') (required)	 
	 * @param string  $title 		The title of the custom message (required)
	 * @param string  $description  The description (required)
	 * @param string  $link 		A link to more information (like Read More link) (optional, default is '')
	 * @param string  $message 		Message (instruction) shown to user (optional, default is 'Insert the colored letters')
	 
	 * get(array("model"=>"feed", "feed_URL"=>$feed_URL, "feed_remove"=>$feed_remove, "feed_limit"=>$feed_limit, "message"=>$message) 
 	 * @param string  $model 		What type of challange content will you use ('model' or 'feed') (required)
	 * @param string  $feed_URL 	A feed(URL), any type of feed
	 * @param string  $feed_remove 	Eliminate a feed based on his title, eg: the current article title
	 * @param integer $feed_limit 	It will consider only first feed_limit feeds (optional, default 10)
	 * @param string  $message 		Message (instruction) shown to user (optional, default is 'Insert the colored letters')

	 * Full parameter call:
	 * get(array("model"=>"text", "title"=>$title, "description"=>$description, "link"=>$link, "message"=>$message, "insert_before"=>$insert_before, "tab_index"=>$tab_index, "xhtml_strict"=>$xhtml_strict, "use_ssl"=>$use_ssl))
	 * get(array("model"=>"feed", "feed_URL"=>$feed_URL, "feed_remove"=>$feed_remove, "feed_limit"=>$feed_limit, "message"=>$message, "insert_before"=>$insert_before, "tab_index"=>$tab_index, "xhtml_strict"=>$xhtml_strict, "use_ssl"=>$use_ssl))  
	 
	 * @param string  $insert_before Insert the challenge before a specific element (optional, default is '' and inserts were the get method is called)
	 * @param integer $tab_index 	 The firt tab index for the challenge inputs (optional, default 5)
	 * @param boolean $xhtml_strict Should the HTML be XHTML 1.0 Strict compatible (optional, default is false)
	 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)
	 
	 * @return string - The HTML to be embedded
	*/
	 
	public function get(array $params_arr)
	{
		if (empty($this->publicKey))
			die ("To use RetinaPost you must register at <a href='".$this->get_signup_url($_SERVER['HTTP_HOST'])."'>".$this->singup_url."</a>");
		// $message = 'Register your site at RetinAds.com';  // si ii pui o public key standard ca sa vada ceva
	   
		!empty($params_arr['use_ssl'])? $server = $this->request_server_secure:$server = $this->request_server;
		
		//$params_arr['custom_text'] = htmlentities($params_arr['custom_text'], ENT_QUOTES, 'UTF-8');		
		$params_arr['pubkey'] = $this->publicKey;
		
		$params_str	 = http_build_query($params_arr, '', '&amp;');
		
		$output = '<script type="text/javascript" src="'. $server . '?'.$params_str.'"></script>';
		//$output .= '<input type="hidden" id="RetinaPost_insert_before"/>';
		
		if (empty($params_arr['xhtml_strict']) || $params_arr['$xhtml_strict']===false ){
			ob_start();
			$src = $this->request_server_noscript .'?'. $params_str;
			?>
			<noscript>
				<iframe src="<?php echo $src; ?>" height="140" width="350" style="border:0px;margin: 2px 0px 2px 0px;z-index:1000;overflow:hidden;"></iframe><br/>
				<input name="Retina_challenge" style="width:300px" value=""/>
				<input type="hidden" name="Retina_response" value="<?php echo $_SERVER["REMOTE_ADDR"]; ?>"/>
			</noscript>
			<?php
			$output.= ob_get_clean();
		}
		return $output;
	}
	
	/**
	  * Checks the user answer
	  * @return true or false
	 */
	public function check()
	{				
		if (empty($this->privateKey)) {
			die ("To use RetinaPost you must register at <a href='".$this->get_signup_url($_SERVER['HTTP_HOST'])."'>".$this->singup_url."</a>");
		}
		if (empty($_SERVER["REMOTE_ADDR"])) {
			die ("You must provide your ip as a parameter to RetinaPost");
		}
		if (empty($_POST["Retina_challenge"]) || empty($_POST["Retina_response"])) {
				$this->error = 'Empty challenge or response Solution';
				$this->result = false;
				return false;
		}

		$response = $this->http_post ($this->verify_server, $this->verify_url /*  */, 
										  array (
												 'privatekey' => $this->privateKey,
												 'remoteip' => $_SERVER["REMOTE_ADDR"],
												 'challenge' =>$_POST["Retina_challenge"],
												 'response' => $_POST["Retina_response"],
												 )
										  );
		$answers = explode ("\n", $response [1]);
		
		if (trim ($answers[0]) == 'true') {
			$this->error  = 'Good answer';
			$this->result = true;
			return true;
		}
		else {
			$this->error  = $answers[1];
			$this->result = false;			
			return false;
		}		
	}
	
	/**
	 * Returns a URL where you can obtain the keys
	 * @param  string $domain the domain of the site 
	 * @param  string $appname the application name (WordPress, php, Joomla, Drupal, ...)
	 */
	public function get_signup_url ($domain = null, $appname = null, $email= null, $submited= false) {
		return $this->singup_url."?".http_build_query(array ('domain' => $domain, 'app' => $appname, 'email' => $email, 'submited' => $submited), '', '&amp;');;
	}
	
	/**
	 * Submits an HTTP POST to a RetinaPost server
	 * @param string $host
	 * @param string $path
	 * @param array $data
	 * @param int port 		(optional, default is 80)
	 * @return array response
	 */
	private function http_post($host, $path, $data, $port = 80) {

			$request = http_build_query($data);

			$http_request  = "POST $path HTTP/1.0\r\n";
			$http_request .= "Host: $host\r\n";
			$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
			$http_request .= "Content-Length: " . strlen($request) . "\r\n";
			$http_request .= "User-Agent: Retina/PHP5 ". $this->version .";\r\n";
			$http_request .= "\r\n";
			$http_request .= $request;

			$response = '';
			if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
					die ('Could not open socket');
			}

			fwrite($fs, $http_request);

			while ( !feof($fs) )
					$response .= fgets($fs, 1160); // One TCP-IP packet
			fclose($fs);
		
			$response = explode("\r\n\r\n", $response, 2);
			return $response;
	}
}