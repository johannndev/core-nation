angular.module('ariaApp.controllers')
.controller('batchController', function($http, Helper, Customer, Item, ariaConstants, $window, $scope) {
var self = this;
self.helper = Helper;

self.transaction = {}
self.errors = []
self.totalQuantity = 0;
self.totalPrice = 0;

//init
self.details = [];
self.warehouse = ariaConstants.defaultWarehouse;

//init transactions
self.transaction.date = self.helper.getToday();
self.transaction.adjustment = 0;

//init buttons
self.disableSubmit = false;

self.changeKey = function(val) {
  self.keycode = val;
}

self.getCustomer = function(val) {
  return Customer.get(val, ariaConstants.customerType);
}

self.getWarehouse = function(val) {
  return Customer.get(val, ariaConstants.warehouseType);
}

self.updateDetails = function() {
  var lines, lineNumber, data, length, itemsLoaded;
  lines = self.csv.split('\n');
  lineNumber = itemsLoaded = 0;
  for (var index = lines.length - 1; index >= 0; index--) {
    l = lines[index];
    data = l.split(',');

    var code = data[0];
    var quantity = data[1];
    var price = data[2];
    var item = Item.batchCode(data[0],data[1],data[2]).then(function(res) {
      if(res.data.length < 1)
        alert('no item');
      self.details[lineNumber] = {};

      self.details[lineNumber].code = res.data.result.code;
      self.details[lineNumber].price = res.data.result.inputPrice;
      self.details[lineNumber].quantity = res.data.result.inputQ;

      self.details[lineNumber].itemId = res.data.result.id;
      self.details[lineNumber].name = res.data.result.name;
      self.details[lineNumber].type = res.data.result.type;
      self.details[lineNumber].discount = 0;
      self.details[lineNumber].quantities = [];
      _.forEach(res.data.result.quantities, function(val, key) {
        self.details[lineNumber].quantities[key] = val;
      })

      //add to total q and price
      self.totalPrice += parseFloat(self.details[lineNumber].price);
      self.totalQuantity += parseFloat(self.details[lineNumber].quantity);
      lineNumber++;
    });
  }
}

self.removeRow = function (index) {
  if (self.details.length > 1) {
    self.details.splice(index, 1);
  }
}

self.getTotalPrice = function() {
  return parseFloat(self.totalPrice) + parseFloat(self.transaction.adjustment);
}

self.submit = function(isValid) {
  self.disableSubmit = true;
  self.sender = self.warehouse;

  $http.post(document.URL, {
      transaction: self.transaction,
      details: self.details,
      customer: self.customer,
      warehouse: self.warehouse,
      paid: self.paid,
      promo: self.promo,
      sender: self.sender,
      receiver: self.receiver,
      journal: self.journal
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
.filter('warehouseQ', function() {
  return function(detail, index) {
    if(detail.quantities) {
      if(detail.quantities[index])
        return detail.quantities[index];
    }
    return 0;
  }
})
