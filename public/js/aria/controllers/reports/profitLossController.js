angular.module('ariaApp.controllers')
.controller('profitLossController', function($http, ariaConstants, $window, Helper, $scope) {
  var self = this;
  self.helper = Helper;

  self.months = Helper.getMonths();
  self.years = Helper.getYears();

  //init
  self.pl = ariaConstants.pl;
  self.compare = ariaConstants.compare;
  self.ops = ariaConstants.operations;
  self.month = ariaConstants.compareDate.month;
  self.year = ariaConstants.compareDate.year;
  self.showProgress = false;

  self.getOps = function(id) {
    if(self.pl.operational[id]) return self.pl.operational[id].total;
    return 0;
  }

  self.getCompareOps = function(id) {
    if(self.compare.operational[id]) return self.compare.operational[id].total;
    return 0;
  }

  self.getMY = function() {
    return self.month + '/' + self.year;
  }

  self.submit = function() {
    self.showProgress = true;

    $http.post(ariaConstants.submitURL, {
      dates: self.dates
    })
    .success(function (res) {
      self.compare = res;
      self.month = self.dates.month;
      self.year = self.dates.year;
      self.showProgress = false;
      return false;
    })
    .error(function(data, status, headers, config) {
      self.errors = self.helper.formatError(data);
      $scope.$parent.openErrorModal(self.errors)
      self.showProgress = false;
      return false;
    })
  }
})