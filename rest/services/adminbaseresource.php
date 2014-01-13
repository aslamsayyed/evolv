<?php 
// includes authentication
class AdminBaseResource extends Tonic\Resource {
	//override the setup method
	function setup() {
       	session_start();
		//if(!isset($_SESSION['AccountId'])){
        //    throw new Tonic\UnauthorizedException;
        //}
    }
}
?>