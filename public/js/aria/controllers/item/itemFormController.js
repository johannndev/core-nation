angular.module('ariaApp.controllers')
.controller('itemFormController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.item = _.isEmpty(ariaConstants.item) ? {} : ariaConstants.item;

self.tags = ariaConstants.tags;
self.selected = ariaConstants.selected;
self.update = ariaConstants.update;
self.errors = []

self.toggleTag = function(typeIndex, tagIndex) {
  var item = self.tags[typeIndex].data[tagIndex].id;
  var index = self.selected.indexOf(item);
  if (index > -1)
    self.selected.splice(index, 1);
  else
    self.selected.push(item);

  //only allow 1 in the same typeindex
  if(self.update) {
    _.forEach(self.tags[typeIndex].data, function(data) {
      if(data.id == item) return;
      var index = self.selected.indexOf(data.id);
      if(index > -1)
        self.selected.splice(index,1);
    })
  }
}

self.addTag = function(tagIndex) {
  var item = self.tags[tagIndex].id;
  var index = self.selected.indexOf(item);
  if (index > -1)
    self.selected.splice(index, 1);
  else
    self.selected.push(item);
}

self.checkSelected = function(typeIndex, tagIndex) {
  return self.selected.indexOf(self.tags[typeIndex].data[tagIndex].id) > -1
}

self.checkSelectedNormal = function(tagIndex) {
  return self.selected.indexOf(self.tags[tagIndex].id) > -1
}

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
    self.disableSubmit = false;
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