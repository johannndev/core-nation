angular.module('ariaApp.controllers')
.controller('itemDetailController', function($http, Helper, ipCookie, $scope) {
var self = this;
self.helper = Helper;
self.errors = []
self.show0 = false;

self.checkQuantity = function(q) {
  if(self.show0 == true) return true;
  if(q > 0) return true;
  return false;
}
})