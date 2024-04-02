angular.module('ariaApp.controllers')
.controller('newsController', function($scope, $http, News) {
	$scope.loading = true;

	News.get().success(function(data) {
		$scope.loading = false;
//		console.log(data.responseData.feed.entries)
		$scope.newsList = data.responseData.feed.entries;
	})
})