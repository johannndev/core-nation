angular.module('ariaApp.controllers')
.controller('locationSettingsController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;

self.settings = ariaConstants.settings;

self.minutes = ['00', '15', '30', '45'];
self.hours = []
for(var i = 0; i <= 24; i++)
  self.hours.push(i);


self.submit = function() {
  self.showProgress = true;
  $http.post(ariaConstants.submitURL, {
    settings: self.settings
  })
  .success(function (res) {
    self.details = res.data;
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.showProgress = false;
    return false;
  })
}
});