<?php 
/**
 * @uri /headerinfo
 */
class HeaderInfoResource extends CrudResource {
	function before($action) {
	}
	 function get() {
	 try {
			$userQuery = $this->getQueryObject("User");
			$user = $userQuery->findPK($_SESSION['UserId']);
			if ($user == null) {
				return new Tonic\Response(Tonic\Response::NOTFOUND);
			} else {
				return new Tonic\Response(Tonic\Response::OK, $user->toJSON(false));
			}
		}
		catch (Exception $e) {
			return new Tonic\Response(Tonic\Response::BADGATEWAY, "Error occured while fetching list");
		}
	 }	 
}
?>