angular.module('ariaApp.controllers')
.controller('boronganFormController', function($http, Helper, ariaConstants, $window, $scope) {
var self = this;
self.helper = Helper;

self.errors = []

//init
self.permak = self.lain2 = self.tres = 0;
self.borongan = [{}];
self.jahits = ariaConstants.jahits;
self.jahit = false;
self.to = self.helper.getToday();
self.from = self.helper.getLastWeek();

//init transactions
self.date = self.helper.getToday();

//init buttons
self.disableSubmit = false;

self.displayItemName = function(index) {
  if(!self.borongan[index]) return false;
  if(self.borongan[index].item) return self.borongan[index].item.name;
  return self.borongan[index].temp_name;
}

self.calculateTotal = function(index) {
  if(!self.borongan[index]) return false;
  if(!self.borongan[index].quantity || !self.borongan[index].ongkos) return 0;
  return self.borongan[index].quantity * self.borongan[index].ongkos;
}

self.invalidBorongan = function() {
  if(!self.from || !self.to || !self.jahit)
    return true;
  if(self.borongan.length <= 0)
    return true;
  return false;
}

self.loadBorongan = function(isValid) {
  self.disableSubmit = true;

  $http.post(ariaConstants.loadURL, {
      from: self.from,
      to: self.to,
      jahit_id: self.jahit.id
  })
  .success(function (res) {
    self.borongan = res.data;
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

self.submit = function(isValid) {
  self.disableSubmit = true;

  $http.post(ariaConstants.submitURL, {
      from: self.from,
      to: self.to,
      jahit_id: self.jahit.id,
      tres: self.tres,
      permak: self.permak,
      lain2: self.lain2
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

self.getTotal = function() {
  var total = 0;
  _.forEach(self.borongan, function(val, index) {
    total += self.calculateTotal(index)
  })
  return parseFloat(total) + parseFloat(self.lain2) + parseFloat(self.permak) + parseFloat(self.tres)
}

})