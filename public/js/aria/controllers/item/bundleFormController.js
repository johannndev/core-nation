angular.module('ariaApp.controllers', [])
.controller('bundleFormController', function($http, Item, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;

self.bundle = {}
self.errors = []

//init
self.items = [];

//init buttons
self.disableSubmit = false;

self.getItem = function(val) {
  return Item.autocomplete(val);
}

self.addRow = function() {
  if(self.quantity > 0 && _.isObject(self.item))
    if(self.items.length < 31) {
      self.items.push({
        "id" : self.item.id,
        "name": self.item.name,
        "code": self.item.code,
        "quantity": self.quantity
      })
      self.item = null;
      self.quantity = 0;
      self.searchText = '';
    }
}

self.removeRow = function (index) {
  console.log(index)
  if (self.items.length > 1)
    self.items.splice(index, 1);
}

self.showModal = function() {
  if(self.action && self.action.deleted_at)
   self.msg = 'Restoring';
  else if(self.action.restore)
   self.msg = 'Deleting';
  self.msg += ' customer = bad data entry, get a new employee.';
  $('#bundleModal').modal('open');
};

self.closeModal = function() {
  $('#bundleModal').modal('close');
}

self.submit = function(isValid) {

  self.disableSubmit = true;

  var fd = new FormData();
  fd.append('file', self.image);
  fd.append('bundle', angular.toJson(self.bundle));
  fd.append('items', angular.toJson(self.items));

  $http.post(ariaConstants.submitURL, fd, {
      transformRequest: function(data) { return data; },
      headers: {'Content-Type': undefined}
  })
  .success(function (res) {
    $window.location.href = res.url;
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.parent.openErrorModal(self.errors);
    self.disableSubmit = false;
    return false;
  });
}
})