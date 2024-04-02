angular.module('ariaApp.controllers')
.controller('transferController', function(Helper, $scope, $http, ariaConstants, $window) {
var self = this;
self.helper = Helper;

self.errors = []
self.transaction = {}
self.accounts = ariaConstants.accounts;
self.transaction.date = self.helper.getToday();

self.disableSubmit = false;

self.submit = function(isValid) {
  self.disableSubmit = true;

  $http.post(document.URL, {
      transaction: self.transaction,
      sender: self.sender,
      receiver: self.receiver
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