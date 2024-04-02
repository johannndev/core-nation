angular.module('ariaApp.controllers')
.controller('invoiceController', function($scope, ariaConstants, ipCookie, $window, Helper, $http) {
var self = this;
self.helper = Helper;

self.tableData = [].concat(ariaConstants.details); //for smart table
self.showImages = ipCookie('invoice_images') == false ? false : true;
self.showDesc = ipCookie('invoice_desc') == false ? false : true;

self.sortType     = 'pcode'; // set the default sort type
self.sortReverse  = false;
self.showSKU = false;
self.showStock = false;
self.stock = ariaConstants.stock;
console.log(self.stock)
self.print = function() {
  $window.print();
}

self.toggleImages = function() {
	ipCookie('invoice_images', self.showImages);
}

self.toggleDesc = function() {
  ipCookie('invoice_desc', self.showDesc);
}

self.showModal = function() {
  $('#deleteModal').modal('open');
};

self.closeModal = function() {
  $('#deleteModal').modal('close');
}

self.getTotal = function() {
  var total = 0;
  for(var i = 0; i < self.tableData.length; i++) {
    if(self.tableData[i])
      total += parseFloat(self.tableData[i].total);
  }
  return total;
}

self.displayStock = function(warehouseId, itemId) {
  if(self.stock.warehouse[warehouseId])
    if(self.stock.warehouse[warehouseId][itemId])
      return parseInt(self.stock.warehouse[warehouseId][itemId]);
  return 0;
}

self.submit = function() {
  self.disableSubmit = true;

  $http.post(ariaConstants.deleteUrl)
  .success(function (res) {
    $window.location.href = res.url;
    return false;
  })
  .error(function(data, status, headers, config) {
    self.closeModal()
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.disableSubmit = false;
    return false;
  });
}

self.checkImage = function() {
  if(!self.image) return true;
  return false;
}

self.addImage = function() {
  var fd = new FormData();
  fd.append('file', self.image);

  $http.post(ariaConstants.imageUrl, fd, {
    transformRequest: function(data) { return data; },
    headers: {'Content-Type': undefined}
  })
  .success(function (res) {
    $window.location.href = res.url;
    return false;
  })
  .error(function(data, status, headers, config) {
    self.closeModal()
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.disableSubmit = false;
    return false;
  });
}
})
