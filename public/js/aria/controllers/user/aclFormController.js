angular.module('ariaApp.controllers')
.controller('aclFormController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.actions = ariaConstants.actions;
self.acl = ariaConstants.acl;

self.getActions = function(key) {
  return self.actions[key];
}

self.errors = []

self.checkSelected = function(app, action) {
  if(!self.acl[app]) return;
  return self.acl[app].indexOf(action) > -1;
}

self.toggleAction = function(app, action) {
  if(!self.acl[app]) self.acl[app] = [];
  var index = self.acl[app].indexOf(action);
  if (index > -1)
    self.acl[app].splice(index, 1);
  else
    self.acl[app].push(action);
}

//init buttons
self.disableSubmit = false;

self.submit = function(isValid) {
  self.disableSubmit = true;
  $http.post(ariaConstants.submitURL, {
    acl: self.acl,
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