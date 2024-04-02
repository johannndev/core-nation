angular.module('ariaApp.controllers')
.controller('adjustController', function(Helper, $scope, $http, Customer, ariaConstants, $window) {
var self = this;
self.helper = Helper;

self.errors = []
self.transaction = {}

self.transaction.date = self.helper.getToday();
self.disableSubmit = false;

self.getCustomer = function(val) {
  return Customer.get(val, 'cash');
}

self.selectedSender = function(item) {
  self.sender = item;
  console.log(self.sender)
}

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