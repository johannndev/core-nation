angular.module('ariaApp.controllers')
.controller('forexController', function($scope, $http, Forex) {
	$scope.forexData = {};

	$scope.loading = true;

	Forex.get().success(function(data) {
		$scope.loading = false;
		$scope.forexList = data.query.results.rate;
	})
})