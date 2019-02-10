<?php
/**
* Created By:webdesigner93 From http://www.webdesignerforum.co.uk
* =======================================
* Other Contributers:
* Andy From http://www.webdesignerforum.co.uk and other WDF Members
* =========================================================
* @Description:This is a Simple to use PHP mailing script for sending email
* via the PHP programming language
*
*/
class Mymail {
  /**
* Holds the recipient of the email
* @var to
*/
  public $to;

  /**
* Holds who the email is from
* @var from
*/
  public $from;

  /**
* Set the reply to of the email
* @var reply_to
*/
  public $reply_to;

  /**
* Holds the subject of the email
* @var subject
*/
  public $subject;

  /**
* Holds the main body of the email
* @var type
*/
  public $message;

  /**
* Sets the content type
* @var Content type
*/
  public $content_type;

  /**
* Holds the character set of the email
* @var type
*/
  public $charset = "utf-8";

  /**
* Holders headers
* @var header
*/
  public $header = array();

  /**
* Holds our final header output
* @var headerOutput
*/
  public $headerOutput;

  /**
* Holds the message to display if our mail is sent
* @var successMSG
*/
  private $_successMSG = "Thank You! Your message has now been sent!";

   /**
* Turns on debugging for our php mailer
* and allows errors to be displayed
* @var debug
*/
  private $_debug = false;

  /**
* ADDED BY Andy From http://www.webdesignerforum.co.uk/
* Generic constructor method
* @param [ array $args ] - array of data to 'quick set', example below
* $args = array(
* 'isHTML' => bool,
* 'debug' => bool,
* 'to' => 'email',
* 'from' => 'email',
* 'replyTo' => 'email',
* 'subject' => 'string',
* 'message' => 'string',
* 'charset' => 'string',
* 'errorMsg'=> 'string',
* 'successMsg' => 'string'
* );
**/
  public function __construct($args = array()){
    if (isset($args['isHTML']) && is_bool($args['isHTML'])){
      $this->isHTML($args['isHTML']);
    }
     if (isset($args['debug']) && is_bool($args['debug'])){
      $this->setDebug($args['debug']);
    }
    if (isset($args['to'])){
      $this->setTo($args['to']);
    }
    if (isset($args['from'])){
      $this->setFrom($args['from']);
    }
    if (isset($args['replyTo']) && self::isEmailValid($args['replyTo'])){
      $this->setReplyTo($args['replyTo']);
    }
    if (isset($args['subject'])){
      $this->setSubject($args['subject']);
    }
    if (isset($args['message'])){
      $this->setMessage($args['message']);
    }
    if (isset($args['charset'])){
      $this->setCharset($args['charset']);
    }
    if (isset($args['errorMsg'])){
      $this->setError($args['errorMsg']);
    }
    if (isset($args['successMsg'])){
      $this->setSuccess($args['successMsg']);
    }

  }
    /**
* Built in mailer error handler
* Handles mail errors
* @param type $message
* @return \email
*/
  private function errorHandle($message){
    if($this->_debug === true):
print("<h1>MAIL ERROR</h1>");
print("Description Of ERROR:<br />");
printf("<span style='color:#FF0000; font-size:13pt;'>%s</span>",$message);
exit;
endif;
  }
    /**
* Check to be sure required info is not blank
* such as a to address
* @param type $message
* @return \email
*/
 private function checkRequired(){
if(!isset($this->to))
$this->errorHandle("You must provide a To Address for PHP to send any email");
 }
   /**
* Turns debugging on
* @param type $debug
* @return \email
*/
  public function setDebug($debug){
      $this->_debug = $debug;
      return $this;
  }
  /**
* Sets the content type to either html
* or plain text
* @param type $html
* @return \email
*/
  public function isHTML($html = true){
      if($html === true):
      $this->content_type = "text/html";
      else:
      $this->content_type = "text/plain";
      endif;
      return $this;
  }

  /**
* Checks if a email is valid or not
* does not require you to instalize the class
* first just simply use
* email::isEmailValid("email@emailtocheck.com")
* @param type $email
* @return boolean
*/
  public static function isEmailValid($email){
     return filter_var($email,FILTER_VALIDATE_EMAIL) ? true : false;
  }

  /**
* Sets who the email is going to
* @param type $to
* @return \email
*/
  public function setTo($to){
      $this->to = $to;
      return $this;
  }

  /**
* Sets who the email is from
* @param type $from
* @return \email
*/
  public function setFrom($from){
      $this->from = $from;
      return $this;
  }

  /**
* Sets the reply to
* @param type $reply_to
* @return \email
*/
   public function setReplyTo($reply_to){
      $this->reply_to = $reply_to;
      return $this;
  }

  /**
* Sets who the email is from
* @param type $from
* @return \email
*/
  public function setSubject($subject){
      $this->subject = $subject;
      return $this;
  }

   /**
* Set the users message
* @param type $message
* @return \email
*/
   public function setMessage($message){
      $this->message = $message;
      return $this;
  }

  /**
* Set the character set default is utf-8
* @param type $charset
* @return \email
*/
   public function setCharset($charset){
      $this->charset = $charset;
      return $this;
  }

  /**
* Set error message for if mail does not send
* @param type $error
* @return \email
*/
  public function setError($error){
      $this->_errorMSG = $error;
      return $this;
  }

   /**
* Set success message if mail does send
* @param type $success
* @return \email
*/
   public function setSuccess($success){
      $this->_successMSG = $success;
      return $this;
  }
  /**
* Create our headers for sending the email
* @return string
*/
  private function _CreateHeaders(){
      /**
* Populate our header array with our headers
*/
    $this->header[] = "From:".$this->from." <".$this->to.">\n";
    $this->header[] = "Reply-To:".$this->reply_to."\n";
    $this->header[] = "Content-Type:".$this->content_type.";charset=".$this->charset."";
    
    /**
* Loop through our headers and assign the final
* output to our headerOutput property
*/
    foreach($this->header as $this->headers){
        $this->headerOutput .= $this->headers;
    }
    //Return header output
    return $this->headerOutput;
    
  }

  /**
* Send our message
*/
  public function send(){
      /**
* Create the headers for our email
*/
   $this->_CreateHeaders();
   //Check that required info is passed to our methods
   $this->checkRequired();
      /**
* Send the email
* if email will not send we throw a new
* exception and catch that exception
*/
   if(!@mail($this->to,$this->subject,$this->message,$this->headerOutput)):
   $this->errorHandle("The php mail() function has failed to send your message!");
   else:
   echo $this->_successMSG;
   endif;
  }
  
 
 
}

?>