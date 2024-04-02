angular.module('ariaApp.controllers')
.controller('transactionController', function($http, Helper, Customer, Item, ariaConstants, $window, $scope) {
var self = this;
self.helper = Helper;

self.transaction = {}
self.errors = []
self.totalQuantity = 0;

//init
self.details = [{}];
self.warehouse = ariaConstants.defaultWarehouse;

//init paid
self.paid = {};
self.accounts = ariaConstants.accounts;
if(self.accounts)
  self.paid.account = self.accounts[0];
_.forEach(self.accounts, function(val) {
  if(val.id == ariaConstants.accountId)
    self.paid.account = val;
})

//init transactions
self.transaction.date = self.helper.getToday();

//init buttons
self.disableSubmit = false;

self.changeKey = function(val) {
  self.keycode = val;
}

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
  return Item.autocomplete(val, false);
}

self.getPPN = function() {

}

self.codeSearch = function(val, index) {
  var data = Item.codeSearch(val).then(function(res) {
    if(res.data.length < 1)
      alert('no item');
    self.onSelect(res.data.result, index);
  });
}

self.addRow = function(index) {
  if(!index) return self.details.push({});
  if(self.details[index].quantity > 0)
    if(self.details.length < 51)
      self.details.push({})
}

self.onSelect = function ($model, $index) {
  self.details[$index].itemId = $model.id;
  self.details[$index].code = $model.code;
  self.details[$index].name = $model.name;
  self.details[$index].type = $model.type;
  if(ariaConstants.price == 'price')
    self.details[$index].price = $model.price;
  else
    self.details[$index].price = $model.cost;
  self.details[$index].discount = 0;
  self.details[$index].quantities = [];
  _.forEach($model.quantities, function(val, key) {
    self.details[$index].quantities[key] = val;
  })
  self.details[$index].searchText = $model.name;
  if(ariaConstants.checkQuantity)
    self.checkQuantity($index)
}

self.checkQuantity = function($index) {
  var detail = self.details[$index]
  var warehouseQ = 0;
  if(detail.quantities)
    if(detail.quantities[self.warehouse.id])
      warehouseQ = detail.quantities[self.warehouse.id];

  var element = eval("$scope.tForm.detailForm_" + $index);

  if(self.warehouse.type == 5) {
    element.$setValidity('checkQuantity' + $index, true);
    return true;
  }
  if(detail.type != 5) {
    element.$setValidity('checkQuantity' + $index, true);
    return true;
  }
  if(parseFloat(detail.quantity) <= parseFloat(warehouseQ)) {
    element.$setValidity('checkQuantity' + $index, true);
    return true;
  }
  element.$setValidity('checkQuantity' + $index, false);
  return 'ng-invalid';
}

self.removeRow = function (index) {
  if (self.details.length > 1) {
    self.details.splice(index, 1);
    self.checkQuantity(index)
  }
}

self.getTotal = function() {
  var total = 0;
  self.totalQuantity = 0;
  for(var i = 0; i < self.details.length; i++) {
    var detail = self.details[i];
    var price = detail.price;
    total += parseFloat((price * detail.quantity) - parseFloat((price * detail.quantity * detail.discount / 100),10),10) || 0;
    self.totalQuantity += parseFloat(detail.quantity);
  }
  self.beforeDisc = total;
  self.transaction.discount = self.transaction.discount || 0;
  self.transaction.adjustment = self.transaction.adjustment || 0;
  total = parseFloat(total - parseFloat(total * self.transaction.discount / 100) + parseFloat(self.transaction.adjustment),10) || 0

  //calculate ppn
  self.ppn = parseFloat(total * 11 / 111);
  self.beforeppn = parseFloat(total / 1.11);

  return total;
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
.filter('subtotal', function() {
  return function(detail) {
    var price = detail.price;
    return parseFloat((price * detail.quantity) - parseFloat((price * detail.quantity * detail.discount / 100),10),10) || 0;
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
