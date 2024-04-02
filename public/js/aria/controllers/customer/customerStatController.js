angular.module('ariaApp.controllers')
.controller('customerStatController', function($http, ariaConstants, Helper, $scope) {
var self = this;
self.helper = Helper;
self.showProgress = false;

self.stats = ariaConstants.stats;
self.dates = ariaConstants.dates;

self.months = Helper.getMonths();
self.years = Helper.getYears();

self.getTotalCashIn = function() {
  var total = 0;
  _.forEach(self.stats, function(val) {
      total += parseFloat(val.cash_in);
  })
  return total;
}

self.getTotalCashOut = function() {
  var total = 0;
  _.forEach(self.stats, function(val) {
      total += parseFloat(val.cash_out);
  })
  return total;
}

self.getTotalReturn = function() {
  var total = 0;
  _.forEach(self.stats, function(val) {
      total += parseFloat(val.return);
  })
  return total;
}

self.getTotalSell = function() {
  var total = 0;
  _.forEach(self.stats, function(val) {
      total += parseFloat(val.sell);
  })
  return total;
}

self.getNettoSell = function() {
  return self.getTotalSell() - self.getTotalReturn();
}

self.getNettoCash = function() {
  return self.getTotalCashIn() - self.getTotalCashOut();
}

self.submit = function() {
  self.showProgress = true;

  $http.post(ariaConstants.submitURL, {
    from: self.dates.fromMonth + '/' + self.dates.fromYear,
    to: self.dates.toMonth + '/' + self.dates.toYear
  })
  .success(function (res) {
    self.stats = res.data;
    self.showProgress = false;
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