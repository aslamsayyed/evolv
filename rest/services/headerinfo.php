<?php 
/**
 * @uri /headerinfo
 */
class HeaderInfoResource extends CrudResource {
	function before($action) {
	}
	 function get() {
	 try {
			$name = $_SESSION['Name'];
			$data = '{"name":"' . $name . '"}';
			return new Tonic\Response(Tonic\Response::OK, $data);
		}
		catch (Exception $e) {
			return new Tonic\Response(Tonic\Response::BADGATEWAY, "Error occured while fetching list");
		}
	 }	 
}
?>