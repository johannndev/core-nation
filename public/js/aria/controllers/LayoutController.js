angular.module('layout', [])
.controller('LayoutController', function($scope, Helper, $window, $log, ariaGlobal) {
  var self = this;
  self.helper = Helper;
  $scope.openErrorModal = function(modal) {
  	self.modal = modal;
  	self.modal.class = 'red darken-1';
    $scope.launchModal()
  }

  $scope.openToast = function(msg) {
    Materialize.toast(msg, 3000);
  }

  $scope.openSuccessModal = function(modal) {
  	self.modal = modal;
  	self.modal.class = 'cyan accent-1';
    if(!_.isArray(self.modal.msg)) self.modal.msg = [self.modal.msg];
    $scope.launchModal()
  }

  $scope.launchModal = function() {
    $(document).ready(function () {
      $('#flashModal').modal('open');
    })
  }

  if(ariaGlobal.modal)
  	$scope.openSuccessModal(ariaGlobal.modal)

});