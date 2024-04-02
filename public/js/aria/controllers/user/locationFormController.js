angular.module('ariaApp.controllers')
.controller('locationFormController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.location = _.isEmpty(ariaConstants.location) ? {} : ariaConstants.location;

self.children = ariaConstants.children;
self.selected = ariaConstants.selected;
self.errors = []

self.toggleTag = function(tagIndex) {
  var item = self.children[tagIndex].id;
  var index = self.selected.indexOf(item);
  if (index > -1)
    self.selected.splice(index, 1);
  else
    self.selected.push(item);
}

self.checkSelected = function(tagIndex) {
  return self.selected.indexOf(self.children[tagIndex].id) > -1
}

//init buttons
self.disableSubmit = false;

self.submit = function(isValid) {
  self.disableSubmit = true;

  $http.post(ariaConstants.submitURL, {
    location: self.location,
    selected: self.selected
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