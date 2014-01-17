<?php 
/**
 * @uri /myaccount
 * @uri /myaccount/:id
 */
class MyAccountResource extends CrudResource {

   function before($action) {
	}
	
   function get() {
	 try {
			$query = PropelQuery::from("evolv\\orm\\User");
			$results = $query->findPK($_SESSION['UserId']);
			$result = $results->toJSON(false);			
			return new Tonic\Response(Tonic\Response::OK, $result);
		}
		catch (Exception $e) {
			return new Tonic\Response(Tonic\Response::BADGATEWAY, "Error occured while fetching list");
		}
	 }	 
	 
    function update() {
		try {
			$query = PropelQuery::from("evolv\\orm\\User");
			$account = $query->findPK($_SESSION['UserId']);
			if ($account == null) {
				return new Tonic\Response(Tonic\Response::NOTFOUND);
			} else {
				$json = json_decode($this->request->data, true);
				if (isset($json['Password']) && $json['Password'] && $account->getPassword() != $json['Password']) {
					$json['Password'] = md5($json['Password']);
				}
				$account->fromJSON(json_encode($json));
				if ($account->validate()) {
					$account->save();
				} else {
					$message = "";
					foreach ($account->getValidationFailures() as $failure) {
						$message .= $failure->getMessage() . "\n";
					}
					return new Tonic\Response(422, $message);
				}
				//$result = $account->toJSON(false);
				return  new Tonic\Response(Tonic\Response::OK);
			}
		} catch(Exception $e) {
			return new Tonic\Response(Tonic\Response::BADGATEWAY, "Error occured while updating");
		}
	 }	 
}
?>