'use strict';

//plm app
var evolvApp = angular.module('evolvApp', [
	'ngRoute',
	'ui.bootstrap',
	'evolvAppControllers',
	'evolvAppServices'
]);
evolvApp.directive("colsort", function() {
return {
    restrict: 'A',
    transclude: true,
    template : 
      '<a ng-click="onClick()">'+
        '<span ng-transclude></span>'+ 
        ' <i class="glyphicon" ng-class="{\'glyphicon-sort-by-alphabet\' : order === by && !reverse,  \'glyphicon-sort-by-alphabet-alt\' : order===by && reverse}"></i>'+
      '</a>',
    scope: {
      order: '=',
      by: '=',
      reverse : '='
    },
    link: function(scope, element, attrs) {
      scope.onClick = function () {
        if( scope.order === scope.by ) {
           scope.reverse = !scope.reverse 
        } else {
          scope.by = scope.order ;
          scope.reverse = false; 
        }
		scope.$parent.setSort(scope.reverse?'D':'A');
		scope.$parent.setSortCol(scope.by);
		scope.$parent.showList();
      }
    }
}
});

evolvApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/', { templateUrl: 'partials/dashboard.html' });
    $routeProvider.when('/:name', { templateUrl: 'partials/blank.html', controller: RouteController });
    $routeProvider.otherwise({redirectTo: '/dashboard'});
}]);