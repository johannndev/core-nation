angular.module('ariaApp.controllers')
.controller('userFormController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.user = _.isEmpty(ariaConstants.user) ? {} : ariaConstants.user;
self.user.location_id = _.isEmpty(self.user.location_id) ? 0 : self.user.location_id;
self.roles = ariaConstants.roles;
self.locations = [{id: 0, name: '---'}].concat(ariaConstants.locations);

self.errors = []

_.forEach(self.roles, function(val) {
  if(val.id == self.user.role_id) self.role = val;
})

_.forEach(self.locations, function(val) {
  if(val.id == self.user.location_id) self.location = val;
})

//init buttons
self.disableSubmit = false;

self.submit = function(isValid) {
  self.disableSubmit = true;
  if(self.role) self.user.role_id = self.role.id;
  if(self.location) self.user.location_id = self.location.id;
  $http.post(ariaConstants.submitURL, {
    user: self.user,
    update_password: self.update_password
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

});