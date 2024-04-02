angular.module('ariaApp.controllers')
.controller('spgFormController', function($http, Helper, ariaConstants, $window, $scope) {
var self = this;
self.helper = Helper;

self.errors = []

//init
self.stores = ariaConstants.stores;
self.store = self.stores[0];

//init buttons
self.disableSubmit = false;

self.submit = function(isValid) {
  self.disableSubmit = true;

  $http.post(document.URL, {
      name: self.name,
      username: self.username,
      warehouse_id: self.store.id
  })
  .success(function (res) {
    $window.location.href = res.url;
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.disableSubmit = false;
    return false;
  });
}

})