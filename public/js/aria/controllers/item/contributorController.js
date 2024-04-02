angular.module('ariaApp.controllers')
.controller('contributorController', function($http, ariaConstants, ipCookie, Customer, $scope) {
var self = this;

self.brands = ariaConstants.brands;
self.sizes = ariaConstants.sizes;
self.genres = ariaConstants.genres;

//for transactions
self.data = [{}];
self.data = ariaConstants.data;
//console.log(self.data)
self.from = ariaConstants.links.from;
self.to = ariaConstants.links.to;

self.getCustomer = function(val) {
  return Customer.get(val);
}

//init buttons
self.showProgress = false;

self.displayBrand = function(val) {
  var selected = 'No Brand';
  _.forEach(self.brands, function(brand) {
    if(val == brand.id) { selected = brand.name;}
  })
  return selected;
}

self.displaySize = function(val) {
  return self.sizes[val];
}

self.displayGenre = function(val) {
  return self.genres[val];
}

self.submit = function(url) {
  self.showProgress = true;
  var customerId = brandId = false;
  if(self.customer) customerId = self.customer.id;
  if(self.brand) brandId = self.brand.id;

    $http.post(ariaConstants.submitURL, {
      from: self.from,
      to: self.to,
      customer_id: customerId,
      brand: brandId
    })
    .success(function (res) {
      self.data = res;
      self.showProgress = false;
      self.helper.scroll();
      return false;
    })
    .error(function(data, status, headers, config) {
      self.errors = self.helper.formatError(data);
      $scope.$parent.openErrorModal(self.errors)
      self.showProgress = false;
      return false;
    })
}
})