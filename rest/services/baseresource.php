<?php 
// authorization
class BaseResource extends Tonic\Resource {
	function setup() {
       	session_start();
		$_SESSION['UserId'] = 1;
		if(!isset($_SESSION['UserId'])){
			throw new Tonic\UnauthorizedException;
        }
    }
}
?>