angular.module('ariaApp.controllers')
.controller('customerLayoutController', function($scope, ariaConstants, $http, $window, Helper) {
var self = this;
self.helper = Helper;

$(document).ready(function(){
	$('ul.tabs').tabs();
	var page = $("#" + ariaConstants.page);
	page.addClass('active');
})

})