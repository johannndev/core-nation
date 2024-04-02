angular.module('ariaApp.controllers')
.controller('cashController', function(Helper, $http, Customer, ariaConstants, $window, $scope) {

var self = this;
self.helper = Helper;
self.errors = []

//init
self.details = [{}];
self.accounts = ariaConstants.accounts;
if(self.accounts)
  self.account = self.accounts[0];
_.forEach(self.accounts, function(val) {
  if(val.id == ariaConstants.accountId)
    self.account = val;
})

//init transactions
self.date = self.helper.getToday();

//init buttons
self.disableSubmit = false;

//autocomplete
self.getCustomer = function(val) {
  return Customer.get(val, 'cash');
}

self.addRow = function(index) {
  if(self.details[index].total > 0)
    if(self.details.length < 10)
      self.details.push({})
}

self.removeRow = function (index) {
  if (self.details.length > 1)
    self.details.splice(index, 1);
}

self.submit = function(isValid) {
  self.disableSubmit = true;

  $http.post(document.URL, {
      date: self.date,
      details: self.details,
      account: self.account
  })
  .success(function (res) {
    $window.location.href = res.url;
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    self.disableSubmit = false;
    $scope.$parent.openErrorModal(self.errors)
    return false;
  });
}

})
.filter('warehouseQ', function() {
  return function(detail, index) {
    if(detail.quantities)
      if(detail.quantities[index])
        return detail.quantities[index];
    return 0;
  }
})
