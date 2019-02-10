<?php

/*
* Satellite class for session handling.
*/

class session
{
	public function __construct( $autostart = TRUE )
	{
		$this->started = ( isset( $_SESSION ) ? TRUE : FALSE );

		if ( $autostart == TRUE && $this->started == FALSE )
		{
			$this->start();
		}
	}

	/* Avoids that pesky notice error if the session was already started previously in the stack. --Kris */
	static public function start()
	{
		if ( !$this->started )
		{
			session_start();

			$this->started = TRUE;
		}
	}

	/* Stop the session. --Kris */
	public function stop( $clear_cookie = TRUE, $clear_data = TRUE )
	{
		if ( $this->started )
		{
			if ( $clear_cookie == TRUE
				&& ini_get( "session.use_cookies" ) == TRUE )
			{
				$params = session_get_cookie_params();

				setcookie( session_name(), '', time() - 42000,
					$params["path"], $params["domain"], $params["secure"], $params["httponly"] );
			}

			if ( $clear_data == TRUE )
			{
				$_SESSION = array();
			}

			session_destroy();
			session_write_close();

			$this->started = FALSE;
		}
		return true;
	}

	/* Generates a random string for use as a new sessionid or any other purpose. --Kris */
	static public function generate_sid( $chars = 100, $alpha = TRUE, $numeric = TRUE, $symbols = TRUE, $timestamp = TRUE )
	{
		if ( $chars <= 0 || !is_numeric( $chars ) )
		{
			return FALSE;
		}

		$salt = NULL;

		if ( $alpha == TRUE )
		{
			$salt .= "abcdefghijklmnopqrstuvwxyz";
		}

		if ( $numeric == TRUE )
		{
			$salt .= "1234567890";
		}

		if ( $symbols == TRUE )
		{
			$salt .= "-_";
		}

		$sid = NULL;
		for ( $c = 1; $c <= $chars; $c++ )
		{
			$sid .= $salt{mt_rand( 0, strlen( $salt ) - 1 )};

			if ( mt_rand( 0, 1 ) == 1 )
			{
				$sid{strlen( $sid ) - 1} = strtoupper( $sid{strlen( $sid ) - 1} );
			}
		}

		if ( $timestamp == TRUE )
		{
			$sid .= time();
		}

		return $sid;
	}

	static public  function set( $var, $val )
	{
		$_SESSION[$var] = MInit::cryptage($val,1);

	}



	static public function clear( $var )
	{
		unset( $_SESSION[$var] );
	}

	static public  function get( $var )
	{
		return ( isset( $_SESSION[$var] ) ? MInit::cryptage($_SESSION[$var],0) : FALSE );
	}

	static public function set_cookie($cok, $val, $time = 3600, $crypt = true )
	{
		$val = !$crypt ? $val : MInit::cryptage($val,1);
		setcookie($cok, $val, time()+3600);
	}

	static public  function get_cookie( $cok, $crypt = true )
	{
		if(!$crypt){
			return (isset( $_COOKIE[$cok]) ?  $_COOKIE[$cok] : FALSE );
		}
		
		return (isset( $_COOKIE[$cok]) ? MInit::cryptage( $_COOKIE[$cok],0) : FALSE );
	}

	static public  function clear_cookie( $cok )
	{
		unset($_COOKIE[$cok]);
	}


}