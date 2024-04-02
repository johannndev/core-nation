angular.module('ariaApp.controllers')
.controller('ItemStatController', function($http, ariaConstants, Helper, ipCookie, $scope) {
var self = this;
self.helper = Helper;
self.errors = []

//init
self.items = [{}];
self.items = ariaConstants.items;

self.displaySell = function(index) {
  return self.items[index].data[ariaConstants.sellConstant] ? self.items[index].data[ariaConstants.sellConstant].quantity : 0;
}

self.displayMove = function(index) {
  return self.items[index].data[ariaConstants.moveConstant] ? self.items[index].data[ariaConstants.moveConstant].quantity : 0;
}

self.displayReturn = function(index) {
  return self.items[index].data[ariaConstants.returnConstant] ? self.items[index].data[ariaConstants.returnConstant].quantity : 0;
}

self.displayProduksi = function(index) {
  console.log(self.items[index].data)
  return self.items[index].data[ariaConstants.produksiConstant] ? self.items[index].data[ariaConstants.produksiConstant].quantity : 0;
}

self.getTotalSell = function() {
  var total = 0;
  _.forEach(self.items, function(val) {
    console.log(val)
    total += val.data[ariaConstants.sellConstant] ? parseFloat(val.data[ariaConstants.sellConstant].quantity) : 0;
  })
  return total;
}

self.getTotalMove = function() {
  var total = 0;
  _.forEach(self.items, function(val) {
    console.log(val)
    total += val.data[ariaConstants.moveConstant] ? parseFloat(val.data[ariaConstants.moveConstant].quantity) : 0;
  })
  return total;
}

self.getTotalReturn = function() {
  var total = 0;
  _.forEach(self.items, function(val) {
    console.log(val)
    total += val.data[ariaConstants.returnConstant] ? parseFloat(val.data[ariaConstants.returnConstant].quantity) : 0;
  })
  return total;
}

self.getTotalProduksi = function() {
  var total = 0;
  _.forEach(self.items, function(val) {
    console.log(val)
    total += val.data[ariaConstants.produksiConstant] ? parseFloat(val.data[ariaConstants.produksiConstant].quantity) : 0;
  })
  return total;
}
})