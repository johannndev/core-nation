angular.module('ariaApp.controllers')
.controller('settingsController', function($http, ariaConstants, $window, Helper, $scope, Customer) {
var self = this;
self.helper = Helper;
self.errors = []

self.ongkir = ariaConstants.ongkir;
self.sell = ariaConstants.sell;

self.settings = ariaConstants.settings;

//init
self.tutupBuku = [3,4,5,6,7,8,9,10,20,28]

//init for select
_.forEach(self.tutupBuku, function(val) {
  if(val == self.settings.tutup_buku)
    self.settings.tutup_buku = val;
})

//init buttons
self.disableSubmit = false;

self.getAccount = function(val) {
  return Customer.get(val, 'a');
}

self.submit = function(isValid) {
  self.disableSubmit = true;

  self.settings.ongkir = self.ongkir.id;
  self.settings.sell_100 = self.sell.id;

  $http.post(ariaConstants.submitURL, {
    settings: self.settings
  })
  .success(function (res) {
    $scope.$parent.openSuccessModal(res)
    self.disableSubmit = false;
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