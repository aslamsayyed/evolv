<?php 
	/**
 * @uri /crud/{resource}
 * @uri /crud/{resource}/{id}
 * @uri /crud/{resource}/{id}/{subresource}
 * @uri /crud/{resource}/{id}/{subresource}/{subid}
 */
class CrudResource extends BaseResource {
    private $resource = false;
	private $id = false;
	private $subResource = false;
	private $subId = false;
	
	private $qType = false;
	private $sortCol = "Id";
	private $sort = Criteria::ASC;
	private $searchKey = "";
	private $action="";
	
	private $page = 1;
	protected $recordsPerPage = 10;
	protected $paginate = false;
	/**
	* @before get
	*/
	function before($action) {
		$this->resource = $this->request->params['resource'];
		
		if (isset($this->request->params['id']))
			$this->id = $this->request->params['id'];
		
		if (isset($this->request->params['subresource']))
			$this->subResource = $this->request->params['subresource'];
		
		if (isset($this->request->params['subid']))
			$this->subId = $this->request->params['subid'];
		
		if (isset($_GET['qtype']))
			$this->qType = $_GET['qtype'];
		
		if (isset($_GET['sortcol']))
			$this->sortCol = $_GET['sortcol'];
			
		if (isset($_GET['sort']) && $_GET['sort']=="D")
			$this->sort = Criteria::DESC;
		
		if (isset($_GET['page']))
			$this->page = $_GET['page'];
		
		if (isset($_GET['search']))
			$this->searchKey = $_GET['search'];
		
		if (isset($_GET['action']))
			$this->action = $_GET['action'];
	}
	
	/**
     * @method GET
	 * @provides application/json
	 * @json
	 * @return \Tonic\Response
     */
	public function get() {
		//return new Tonic\Response(Tonic\Response::OK, var_dump($this->getSelectColumns()));
		$query = $result = null;
		if ($this->subId) {
			$query = $this->getSubResourceQuery();
		} else if ($this->subResource) {
			$this->paginate = true;
			$query = $this->getSubResourcesQuery();
		} else if ($this->id) {
			$query = $this->getResourceQuery();
		} else if ($this->resource) {
			$this->paginate = true;
			$query = $this->getResourcesQuery();
		}

		// select options
		if ($this->qType == "select") {
			$result = $query->find();
			return new Tonic\Response(Tonic\Response::OK, $result->toJSON(false));			
		}
		
		// pagination
		if ($this->paginate) {
			$page = $this->getPageResult($query);
			return new Tonic\Response(Tonic\Response::OK, json_encode($page));
		}
		
		$result = $query->find();
		return new Tonic\Response(Tonic\Response::OK, $result->toJSON(false));
	}
	protected function getPageResult($query) {
		$pager = $query->paginate($this->page, $this->recordsPerPage);
		$page = array (
			'results' => json_decode($pager->getResults()->toJSON(false)),
			'paginate' => $pager->haveToPaginate(),
			'currentPage' => $this->page,
			'totalItems' => $pager->count(),
			'itemsPerPage' => $this->recordsPerPage
		);
		return $page;
	}
	
	protected function getSelectColumns() {
		return array("Id", "Name");
	}
	
	protected function getResourceQuery() {
		return $this->getQueryObject($this->resource)->filterById($this->id);
	}
	
	protected function getSubResourceQuery() {
		return $this->getQueryObject($this->subResource)->filterById($this->subId);
	}
	
	protected function getResourcesQuery() {
		$query = $this->getQueryObject($this->resource);
		if ($this->qType == "select") {
			$this->addSelect($query);
		} else if ($this->sortCol) {			
			if ($this->searchKey)
				$this->addFilter($query);
			else {
				if ($rel = $this->getRelation($this->sortCol)) {
					$query->join($rel)->useQuery($rel)->endUse();
					$this->addOrderBy($query);
				} else {
					$query->orderBy($this->sortCol, $this->sort);
				}
			}
		}
		return $query;
	}
		
	public function addFilter($query) {
		$this->searchKey = "*" . trim($this->searchKey) . "*";
		$this->searchKey = preg_replace( '/\s+/', '* *', $this->searchKey );
		$keys = explode(" ", $this->searchKey);
		switch($this->resource) {
			case "tag":
				foreach($keys as $key)
					$query->filterByName($key);
				$this->addOrderBy($query, true);
			break;
			
		}
	}
	
	private function getRelation($col){
		if ($col != "Id") {
			$arr = explode("Id", $col);
			if (count($arr) == 2 && $arr[1] == "")
				return $arr[0];
		}
		return "";
	}
	protected function addOrderBy($query, $skipId=false) {
		if ($this->sortCol == "Id" && $skipId)
			return;
		$col = $this->sortCol;
		if ($rel = $this->getRelation($this->sortCol)) {
			$col = $rel . ".Name";
		}
		$query->orderBy($col, $this->sort);
	}
	
	protected function getSubResourcesQuery() {
		$query = $this->getQueryObject($this->subResource);
		if ($this->qType == "select") {
			$this->addSelect($query);
		} else if ($this->sortCol) {			
			$this->addOrderBy($query);
		}
		$parent = $this->getQueryClassName($this->resource);
		return $query->Join($parent)->filterBy("{$parent}Id", $this->id);	
	}
	
	public function addSelect($query) {
		$selectCols = $this->getSelectColumns();
		$query->select($selectCols);
		$query->orderBy($selectCols[1]);
	}
	
	protected function getQueryObject($resource) {
		return PropelQuery::from($this->getQueryClassName($resource));
	}
	
	protected function getQueryClassName($resource) {
		return "evolv\\orm\\" . ucfirst(strtolower($resource));
	}

    /**
     * @method POST
	 * @accepts application/json
	 * @provides application/json
	 * @json
	 * @return \Tonic\Response
     */

	 function create() {
		try {
			$json = json_decode($this->request->data, true);
			$loader = PropelAutoloader::getInstance();
			$class = $this->getQueryClassName($this->resource);
			$object = new $class();
			$object->fromJSON(json_encode($json));
			if ($object->validate()) {
				$object->save();
			} else {
				$message = "";
				foreach ($object->getValidationFailures() as $failure) {
					$message .= $failure->getMessage() . "\n";
				}
				return new Tonic\Response(422, $message);
			}
			return new Tonic\Response(Tonic\Response::CREATED);
		} catch(Exception $e) {
			return new Tonic\Response(Tonic\Response::BADGATEWAY);
		}
	 }
	 
    /**
     * @method PUT
	 * @accepts application/json
	 * @provides application/json
	 * @json
	 * @return \Tonic\Response
     */

	 function update() {
		try {
			$query = $this->getQueryObject($this->resource);
			$object = $query->findPK($this->id);
			if ($object == null) {
				return new Tonic\Response(Tonic\Response::NOTFOUND);
			} else {
				$json = json_decode($this->request->data, true);
				$object->fromJSON(json_encode($json));			
				if ($object->validate()) {
					$object->save();
				} else {
					$message = "";
					foreach ($object->getValidationFailures() as $failure) {
						$message .= $failure->getMessage() . "\n";
					}
					return new Tonic\Response(Tonic\Response::BADREQUEST, $message);
				}
				//$data = '{"object":' . $object->toJSON(false) . "}";
				return  new Tonic\Response(Tonic\Response::OK);
			}
		} catch(Exception $e) {
			return new Tonic\Response(Tonic\Response::BADGATEWAY, "Error occured while updating");
		}
	 }	
    /**
     * @method DELETE
	 * @provides application/json
	 * @json
	 * @return \Tonic\Response
     */

	 function delete() {
		try {
			$query = $this->getQueryObject($this->resource);
			$object = $query->findPK($this->id);
			if ($object != null) {
				$object->delete();
				return new Tonic\Response(Tonic\Response::NOCONTENT);
			} else {
				return new Tonic\Response(Tonic\Response::NOTFOUND, "Object not found");
			}
		} catch(Exception $e) {
			return new Tonic\Response(Tonic\Response::BADGATEWAY, "Error occured while deleting");
		}
	 }	
}
?>