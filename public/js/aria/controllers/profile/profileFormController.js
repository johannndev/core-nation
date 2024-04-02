angular.module('ariaApp.controllers')
.controller('profileFormController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;

//init
self.list = ariaConstants.list;
self.account = ariaConstants.account;
self.warehouse = ariaConstants.warehouse;
self.settings = ariaConstants.settings;

//init buttons
self.disableSubmit = false;

for(var type in self.list) {
  if(self.list.hasOwnProperty(type)) {
    if(self.list[type].type == ariaConstants.warehouseType) {
      _.forEach(self.warehouse, function(val) {
        if(val.id == self.settings[type].value)
          self.settings[type].value = val;
      })
    }
    else {
      _.forEach(self.account, function(val) {
        if(val.id == self.settings[type].value)
          self.settings[type].value = val;
      })
    }
  }
}


self.submit = function(isValid) {
  self.disableSubmit = true;

  $http.post(ariaConstants.submitURL, {
    settings: self.settings
  })
  .success(function (res) {
    $scope.$parent.openSuccessModal(res)
    self.disableSubmit = false;
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.disableSubmit = false;
    return false;
  });
}

});