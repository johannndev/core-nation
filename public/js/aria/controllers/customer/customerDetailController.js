angular.module('ariaApp.controllers')
.controller('customerDetailController', function($scope, ariaConstants, $http, $window, Helper, Customer) {
var self = this;
self.helper = Helper;
self.action = ariaConstants.action;
self.portal = ariaConstants.portal;
self.warehouse = ariaConstants.defaultWarehouse;

self.getWarehouse = function(val) {
  return Customer.get(val, ariaConstants.warehouseType);
}

self.switch = function() {
	if(self.action.deleted_at) self.action.button = 'Restore';
	else self.action.button = 'Delete';
}

self.tooglePortal = function() {
  console.log(self.portal)
  if(self.portal.active == 1) self.portal.button = 'Remove Portal';
  else self.portal.button = 'Add Portal';
}

self.showModal = function() {
  if(self.action && self.action.deleted_at)
   self.msg = 'Restoring';
  else if(self.action.restore)
	 self.msg = 'Deleting';
  self.msg += ' customer = bad data entry, get a new employee.';
  $('#customerModal').modal('open');
};

self.portalModal = function() {
  if(self.portal && self.portal.active == 1)
    self.msg = 'Deleting Portal';
  else
    self.msg = 'Adding Portal';

  $('#portalModal').modal('open');
};

self.closeModal = function() {
  $('#customerModal').modal('close');
  $('#portalModal').modal('close');
}

self.submit = function() {
  var url = ariaConstants.deleteUrl;
  if(self.action && self.action.deleted_at)
    url = ariaConstants.restoreUrl;

  self.post(url, {}, '#customerModal')
}

self.submitPortal = function() {
  var url = ariaConstants.portalUrl;
  if(self.portal && self.portal.active == 1)
    url = ariaConstants.unportalUrl;

  self.post(url, {}, '#portalModal');
}

self.addWarehouse = function() {
  self.post(ariaConstants.warehouseUrl, {warehouse: self.warehouse}, false)
}

self.post = function(url, data, modal) {
  $http.post(url, data)
  .success(function (res) {
    if(modal)
      $(modal).modal('close');
    if(res.success) {
      $scope.$parent.openSuccessModal(res)
      self.portal.active = res.active;
      self.tooglePortal();
    }
    else {
      $scope.$parent.openErrorModal(res);
    }

    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);

    self.disableSubmit = false;
    $scope.$parent.openErrorModal(self.errors);

    return false;
  });
}

self.switch();
self.tooglePortal();

})