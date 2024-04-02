angular.module('ariaApp.controllers')
.controller('assetFormController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.item = _.isEmpty(ariaConstants.item) ? {} : ariaConstants.item;

self.errors = []

//init buttons
self.disableSubmit = false;

self.submit = function(isValid) {
  self.disableSubmit = true;

  var fd = new FormData();
  fd.append('file', self.image);
  fd.append('selected', angular.toJson(self.selected));
  fd.append('item', angular.toJson(self.item));

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
    $scope.$parent.openErrorModal(self.errors);
    self.disableSubmit = false;
    return false;
  });
}

});