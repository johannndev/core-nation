angular.module('ariaApp.controllers')
.controller('roleFormController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.role = _.isEmpty(ariaConstants.role) ? {} : ariaConstants.role;

self.errors = []

//init buttons
self.disableSubmit = false;

self.submit = function(isValid) {
  self.disableSubmit = true;
  $http.post(ariaConstants.submitURL, {
    role: self.role,
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

});