'use strict';
function RouteController($scope, $http, $route, $routeParams, $compile) {
    $route.current.templateUrl = 'partials/' + $routeParams.name + ".html";
    $http.get($route.current.templateUrl).then(function (msg) {
        $('#views').html($compile(msg.data)($scope));
    });
}
//PagesController.$inject = ['$scope', '$http', '$route', '$routeParams', '$compile'];
// evolv app controllers
var evolvAppControllers = angular.module('evolvAppControllers', []);
// sample verb Rest.HeaderInfo.get({verb: 'query', name : 'aslam', limit: 20, page : 2})
evolvAppControllers.controller('HeaderInfoCtrl', ['$scope', 'Rest',
	function($scope, Rest) {
		$scope.headerInfo = Rest.HeaderInfo.get();
	}]);

evolvAppControllers.controller('MyAccountCtrl', ['$scope', 'Rest', 'AppUtils', 
	function ($scope, Rest, AppUtils) {
		$scope._commons = AppUtils;		
		$scope.initialize = function(){
			$scope._confirmedPassword = "";
			// displayed / fetched myaccount
			$scope.myaccount = Rest.MyAccount.get();
			// edited myaccount
			$scope.editedAccount = null;
			// cloned myaccount to cancel / revert operation
			$scope._myaccount = null;
		};
		$scope.initialize();
		
		$scope.editMyAccount = function(p) {
			$scope.editedAccount = p;
			$scope._confirmedPassword = $scope.editedAccount.Password;
			$scope._myaccount = angular.extend({}, p);
		};
		
		$scope.cancelEditing = function() {
			$scope.myaccount = $scope._myaccount;
			$scope.editedAccount = null;
		};
		
		$scope.updateMyAccount = function() {
			if ($scope.editedAccount.Password != $scope._confirmedPassword) {
				$scope._commons.showAlert("Password mismatch, try again");
				$scope.editedAccount.Password = $scope._confirmedPassword = $scope._myaccount.Password;
				return;
			}
			Rest.MyAccount.update($scope.editedAccount, function (){
				$scope.initialize();
			});
		}
	}]);

evolvAppControllers.controller('TaskCtrl', ['$scope', 'CrudController','Rest', 'AppUtils', '$modal',  
	function ($scope, CrudController, Rest, AppUtils, $modal) {
		$scope._cc = CrudController;
		
		$scope.userSelector = Rest.UserSelector.select({}, function() {
			$scope.userMap = AppUtils.arrayToMap($scope.userSelector);
		});
		
		$scope.projectSelector = Rest.ProjectSelector.select({}, function() {
			$scope.projectMap = AppUtils.arrayToMap($scope.projectSelector);
		});
		
		$scope.sprintSelector = Rest.SprintSelector.select({}, function() {
			$scope.sprintMap = AppUtils.arrayToMap($scope.sprintSelector);	
		});
		
		$scope.tagSelector = Rest.TagSelector.select({}, function() {
			$scope.tags = $scope.tagSelector;
		});
		
		$scope.taskSelector = Rest.TaskSelector.select({}, function() {
			$scope.taskMap = AppUtils.arrayToMap($scope.taskSelector);
		});
				
		/* Tag */
		$scope.selected = undefined;
		$scope.tagList = [];
		$scope.setTag = function(tag) {
			if($scope.tagList.indexOf(tag) == -1 && tag)
				$scope.tagList.push(tag);
		}
		$scope.getTag = $scope.tagList;
		
		/* Status */
		$scope.getStatus = function(statusCode){
			switch(statusCode){
				case 1: return "New";
				case 3: return "In-Progress";
				case 5: return "Done";
			}
		}
		
		/* Rating */
		$scope.max = 3;
		$scope.isReadonly = false;

		$scope.hoveringOver = function(value) {
			$scope.overStar = value;
			$scope.percent = 100 * (value / $scope.max);
		};

		$scope.ratingStates = [
			{stateOn: 'glyphicon-ok-sign', stateOff: 'glyphicon-ok-circle'},
			{stateOn: 'glyphicon-star', stateOff: 'glyphicon-star-empty'},
			{stateOn: 'glyphicon-heart', stateOff: 'glyphicon-ban-circle'},
			{stateOn: 'glyphicon-heart'},
			{stateOff: 'glyphicon-off'}
		];
		
		/* initialize scope */
		$scope._cc.initialize($scope, Rest.Task, AppUtils);
		$scope.showList();
		
	}]);
	
	
evolvAppControllers.factory('CrudController',[
	function () {
		var scope, RestModel, AppUtils;
		var listParams;
		var clonedObject;
		var controller =	{
			initialize : function(s, rm, u){
				scope = s;
				RestModel = rm;
				AppUtils = u;
				listParams = {};
				clonedObject = null;
				scope.setMode = function(m) {
					scope.clearAlerts();
					scope.viewMode = m;
				};
				//List
				scope.showList = function(){	
					scope.setMode("List");
					scope.editedObject = clonedObject = null;					
					scope.viewModel = RestModel.query(listParams, function () {
						if (scope.viewModel && scope.viewModel.results && scope.viewModel.results.length == 0) {
							scope.showWarningAlert('Records not found, try again.')
						}						
					});
				};
				
				// Create
				scope.addNew = function() {
					scope.setMode("Add");
					scope.editedObject = {};
				}
				scope.createObject = function() {
					RestModel.create(scope.editedObject, function() {
						scope.showList();
						scope.showSuccessAlert("Record saved successfully.");						
					}, function(r){
						scope.showErrorAlert("Save failed, " + r.data);
					});
				}
				
				// Edit / Update
				scope.editObject = function(p) {
					scope.setMode("Edit");
					scope.editedObject = p;
					clonedObject = angular.extend({}, p);
				};
				
				scope.cancelEditing = function() {
					scope.viewModel.results[scope.viewModel.results.indexOf(scope.editedObject)] = clonedObject;
					scope.editedObject = clonedObject = null;
					scope.setMode("List");
				};
				
				scope.updateObject = function() {
					RestModel.update(scope.editedObject, function() {
						scope.showList();
						scope.showSuccessAlert("Record updated successfully.");
					}, function(r){
						scope.showErrorAlert("Update failed, " + r.data);
					});
				};
				
				//Delete
				scope.deleteObject = function(a) {
					RestModel.delete({Id: a.Id}, function() {
						scope.showList();
						scope.showSuccessAlert("Record deleted successfully.");
					}, function(r){
						scope.showErrorAlert("Delete failed, " + r.data);
					});
				};
				
				scope.showPage = function(p) {
					listParams.page = p;
					scope.showList();
				};
				scope.setSortCol = function(p) {
					listParams.sortcol = p;
				};
				scope.getSortCol = function(p) {
					return listParams.sortcol;
				};
				scope.setSort = function(p) {
					listParams.sort = p;
				};
				scope.getSort = function(p) {
					return listParams.sort;
				};
				
				scope.performSearch = function() {
					if (scope.searchKey && scope.searchKey.trim().length > 0) {
						scope.searchKey = scope.searchKey.trim();
						listParams.page = 1;
						listParams.search = scope.searchKey;
						scope.showList();
					} else  {
						listParams.search = scope.searchKey = "";
					}
				};
				scope.clearSearch = function() {
					listParams.page = 1;
					scope.searchKey = '';
					listParams.search = '';
					scope.showList();
				};
				// alerts
				scope.showSuccessAlert = function(m) {
					scope.alerts = [{ type: 'success', msg: m}];
				};
				scope.showErrorAlert = function(m) {
					scope.alerts = [{ type: 'danger', msg: m}];
				};
				scope.showWarningAlert = function(m) {
					scope.alerts = [{ type: 'warning', msg: m}];
				};
				scope.clearAlerts = function() {
					scope.alerts = [];
				};
			},
		}
		return controller;
	}]);

evolvAppControllers.controller('UserCtrl', ['$scope', 'CrudController','Rest', 'AppUtils',  
	function ($scope, CrudController, Rest, AppUtils) {
		$scope._cc = CrudController;
		$scope._cc.initialize($scope, Rest.User, AppUtils);
		$scope.showList();				
		
	}]);

evolvAppControllers.controller('ProjectCtrl', ['$scope', 'CrudController','Rest', 'AppUtils',  
	function ($scope, CrudController, Rest, AppUtils) {
		$scope._cc = CrudController;
		$scope.userSelector = Rest.UserSelector.select({}, function() {
			$scope.userMap = AppUtils.arrayToMap($scope.userSelector);
				$scope._cc.initialize($scope, Rest.Project, AppUtils);
				$scope.showList();
		});				
		
	}]);
	
evolvAppControllers.controller('SprintCtrl', ['$scope', 'CrudController','Rest', 'AppUtils',  
	function ($scope, CrudController, Rest, AppUtils) {
		$scope._cc = CrudController;
		$scope.userSelector = Rest.UserSelector.select({}, function() {
			$scope.userMap = AppUtils.arrayToMap($scope.userSelector);
			$scope.projectSelector = Rest.ProjectSelector.select({}, function() {
				$scope.projectMap = AppUtils.arrayToMap($scope.projectSelector);
				$scope._cc.initialize($scope, Rest.Sprint, AppUtils);
				$scope.setSortCol("StartDate");
				$scope.showList();
			});	
		});
		
		$scope.getTask = function(id) {
			//Rest.Sprint({id: id, verb: 'task'});
			return id;
		};
		
		$scope.getClassForStatus = function(start_date,end_date,retrospective) {
			return  getStatus(start_date,end_date,retrospective,false);
		};
		$scope.getSprintStatus = function(start_date,end_date,retrospective) {
			return  getStatus(start_date,end_date,retrospective,true);
		};
		
		function getStatus(start_date,end_date,retrospective,isStatus) {
			var status;
			var sDate = new Date(start_date).getTime();
			var eDate = new Date(end_date).getTime();
			var cDate = new Date().getTime();
			if(cDate >= sDate && cDate <= eDate)
				status = "Started";
			else if( cDate <= sDate)
				status = "Un-started";
			else if(cDate >= eDate && !retrospective)
				status = "Elapsed";
			else if(cDate >= eDate && retrospective)
				status = "Completed";
			
			if(isStatus){
				return status;
			}
			else{
				var label;
				switch(status){
					case 'Started': label =  "label-info"; break;
					case 'Elapsed': label =  "label-warning"; break;
					case 'Completed': label =  "label-success"; break;
					default : label =  "label-default";
				}
				return label;
			}
		}
	}]);
/*
evolvAppControllers.controller('TaskCtrl', ['$scope', 'CrudController','Rest', 'AppUtils',  
	function ($scope, CrudController, Rest, AppUtils) {
		$scope._cc = CrudController;
		$scope.userSelector = Rest.UserSelector.select({}, function() {
			$scope.userMap = AppUtils.arrayToMap($scope.userSelector);
			$scope.projectSelector = Rest.ProjectSelector.select({}, function() {
				$scope.projectMap = AppUtils.arrayToMap($scope.projectSelector);
				$scope.sprintSelector = Rest.SprintSelector.select({}, function() {
					$scope.sprintMap = AppUtils.arrayToMap($scope.sprintSelector);	
					$scope._cc.initialize($scope, Rest.Task, AppUtils);
					$scope.showList();
				});	
			});	
		});				
		
	}]);
*/	
evolvAppControllers.controller('TagCtrl', ['$scope', 'CrudController','Rest', 'AppUtils',  
	function ($scope, CrudController, Rest, AppUtils) {
		$scope._cc = CrudController;
		$scope._cc.initialize($scope, Rest.Tag, AppUtils);
		$scope.showList();				
		
	}]);
