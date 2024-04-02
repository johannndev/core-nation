angular.module('ariaApp.controllers')
.controller('customerFormController', function($http, $filter, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.errors = []
self.customer = _.isEmpty(ariaConstants.customer) ? {} : ariaConstants.customer;

_.forEach(self.types, function(val) {
  if(val.id == self.customer.type)
   self.type = val;
})

self.toggleImages = function() {
  console.log(self.customer.ppn)
}

//init buttons
self.disableSubmit = false;

if(ariaConstants.types) {
  self.types = ariaConstants.types;
}

self.submit = function(isValid) {
  self.disableSubmit = true;
  if(self.type) self.customer.type = self.type.id;

  $http.post(document.URL, {
    customer: self.customer,
    initial: self.initial
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
