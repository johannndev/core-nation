angular.module('ariaApp.controllers')
.controller('balanceController', function($http, ariaConstants, $window, Helper, $scope) {
  var self = this;
  self.helper = Helper;

  //init
  self.data = ariaConstants.data;

  //init
  self.total = {}
  self.total.kas = self.total.piutang_reseller = self.total.piutang_customer = self.total.asset_tetap = self.total.hutang_usaha = 0;

  self.getLink = function(id, type) {
    switch(type) {
      case 'customer': return ariaConstants.customerDetailLink + '/' + id; break;
      case 'reseller': return ariaConstants.resellerDetailLink + '/' + id; break;
      case 'kas': return ariaConstants.kasDetailLink + '/' + id; break;
      case 'supplier': return ariaConstants.supplierDetailLink + '/' + id; break;
    }
  }

  var counter = ['kas', 'piutang_customer', 'piutang_reseller', 'hutang_usaha']

  _.forEach(counter, function(key) {
    _.forEach(self.data[key], function(val) {
      self.total[key] = parseFloat(self.total[key]) + parseFloat(val.stat.balance);
    })
    self.total.asset_lancar += Math.abs(self.total[key]);
  })

  _.forEach(self.data.asset_tetap, function(val) {
    self.total.asset_tetap = parseFloat(self.total.asset_tetap) + parseFloat(val.d_value);
  })
})