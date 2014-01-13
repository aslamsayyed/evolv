'use strict';

// evolv app services
var evolvAppServices = angular.module('evolvAppServices', ['ngResource']);


evolvAppServices.factory('AppUtils', ['$window',
	function ($window) {
		return {
			showAlert: function(msg) {
				$window.alert(msg);
			},
			onResourceSuccess: function(){
				$window.alert("Success");
			},
			onResourceError: function() {
				$window.alert("Error");
			},
			arrayToMap:function(arr, k, kv) {
				k = k || 'Id';
				kv = kv || 'Name';
				var map = {};
				for(var i=0; i < arr.length; i++) {
					map[arr[i][k]] = arr[i][kv];
				}
				return map;
			},
			arrayToObjectMap:function(arr, k) {
				k = k || 'Id';
				var map = {};
				for(var i=0; i < arr.length; i++) {
					map[arr[i][k]] = arr[i];
				}
				return map;
			}			
			/*
			,
			newFunction: function() {
			}
			*/
		}
	}
]);
evolvAppServices.factory('AppHttpInterceptors', ['$q', 'AppUtils', 
	function ($q, _appCommons) {
		return {
			'request': function(config) {
				_appCommons.showAlert("In interceptor request");
				return config || $q.when(config);
			},
			'response': function(response) {
				//_appCommons.showAlert("In interceptor response");
				return response || $q.when(response);
			}
		};
	}
]);

evolvAppServices.factory('Constants', [function() {
        return {
            RESOURCE_URL: "../rest/"
        }
    }
]);

evolvAppServices.factory('Rest', ['Constants', 'AppHttpInterceptors', '$resource', function(C, AppHttpInterceptors, $resource) {
	var actions = {
		'create' : {method: 'POST', isArray:false, interceptor: AppHttpInterceptors},
		'update' : {method: 'PUT', isArray:false, interceptor: AppHttpInterceptors},
		'delete' : {method: 'DELETE', isArray:false, interceptor: AppHttpInterceptors},
		'get' 	: {method: 'GET', isArray:false, interceptor: AppHttpInterceptors},
		'query' : {method: 'GET', isArray:false, interceptor: AppHttpInterceptors},
		'select' : {method: 'GET', isArray:true, interceptor: AppHttpInterceptors}
	};
	var crudResource = function(url) {
		return $resource(C.RESOURCE_URL + 'crud/' + url + '/:Id/:Verb', {
				Id: '@Id',
				Verb: '@Verb'
			},actions);
	};
	var customResource = function(url) {
		return $resource(C.RESOURCE_URL + url + '/:Id/:Verb', {
				Id: '@Id',
				Verb: '@Verb'
			},actions);
	};	
	return {
        MyAccount: customResource('myaccount'), 
		HeaderInfo: customResource('headerinfo'),
		User: crudResource('user'),
		Project: crudResource('project'),
		Sprint: crudResource('sprint'),
		Task: crudResource('task'),
		Tag: crudResource('tag')
    }
}]);

evolvAppServices.factory('HeaderInfo', ['$resource', 
	function ($resource) {
		return $resource('../services/headerinfo');
	}
]);
