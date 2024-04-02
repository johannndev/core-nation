angular.module('ariaApp.controllers')
.controller('personnelDetailController', function($scope, ariaConstants, $http, $window, Helper) {
var self = this;
self.helper = Helper;
self.action = ariaConstants.action;

self.switch = function() {
	if(self.action.deleted_at) self.action.button = 'Restore';
	else self.action.button = 'Delete';
}

self.showModal = function() {
  if(self.action && self.action.deleted_at)
   self.msg = 'Restoring';
  else if(self.action.restore)
	 self.msg = 'Deleting';
  self.msg += ' personnel = bad data entry, get a new employee.';
  $('#personnelModal').modal('open');
};

self.closeModal = function() {
  $('#personnelModal').modal('close');
}

self.submit = function() {
  self.disableSubmit = true;

  var url = ariaConstants.deleteUrl;
  if(self.action && self.action.deleted_at)
    url = ariaConstants.restoreUrl;

  $http.post(url)
  .success(function (res) {
    $('#personnelModal').modal('close');
    if(res.success) {
      $scope.$parent.openSuccessModal(res)
      self.action.deleted_at = res.deleted_at;
      self.switch();
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

})