angular.module('ariaApp.controllers')
.controller('produksiFormController', function($http, Helper, ariaConstants, $window, $scope) {
var self = this;
self.helper = Helper;

self.errors = []

//init
self.details = [{}];
self.potongs = ariaConstants.potongs;
self.potong = self.potongs[0];
self.sizes = ariaConstants.sizes;

//init transactions
self.date = self.helper.getToday();

//init buttons
self.disableSubmit = false;

self.getCustomer = function(val) {
  return Customer.get(val, ariaConstants.customerType);
}

self.getAccount = function(val) {
  return Customer.get(val, 'a')
}

self.getWarehouse = function(val) {
  return Customer.get(val, ariaConstants.warehouseType);
}

self.getItem = function(val) {
  return Item.autocomplete(val);
}

self.addRow = function(index) {
  if(self.details[index].quantity > 0)
    if(self.details.length < 51)
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
      potong: self.potong,
      surat_jalan_potong: self.surat_jalan_potong
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