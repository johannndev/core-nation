angular.module('ariaApp.controllers')
.controller('locationDetailController', function($http, ariaConstants, $window, Helper, Customer, $scope) {
var self = this;
self.helper = Helper;
self.details = ariaConstants.details.data;

//init buttons
self.disableSubmit = false;
self.showProgress = false;
self.showNext = false;
self.showPrevious = false;
self.currentPage = ariaConstants.details.currentPage;
self.lastPage = ariaConstants.details.lastPage;

self.togglePaginator = function() {
  if(self.currentPage == 1) self.showPrevious = false;
  else self.showPrevious = true;
  if(self.currentPage == self.lastPage) self.showNext = false;
  else self.showNext = true;
}

self.getCustomer = function(val) {
  return Customer.get(val, 'loc');
}

self.nextPage = function() {
  self.currentPage++;
  self.submit();
}

self.prevPage = function() {
  self.currentPage--;
  self.submit();
}

self.resetPage = function() {
  self.currentPage = 1;
}

self.assign = function() {
  self.showProgress = true;
  $http.post(ariaConstants.assignUrl, {
    customer: self.customer
  })
  .success(function (res) {
    self.showProgress = false;
    alert(res.success)
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.showProgress = false;
    return false;
  })
}

self.submit = function() {
  self.showProgress = true;

  $http.post(ariaConstants.currentUrl, {
    name: self.name,
    page: self.currentPage
  })
  .success(function (res) {
    self.details = res.data;
    self.showProgress = false;
    self.currentPage = res.currentPage;
    self.lastPage = res.lastPage;
    self.togglePaginator();
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.showProgress = false;
    return false;
  })
}

//start calling
self.togglePaginator();
});