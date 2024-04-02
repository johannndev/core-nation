angular.module('ariaApp.controllers')
.controller('itemTransactionController', function($http, ariaConstants, ipCookie, Customer, $scope) {
var self = this;

self.types = ariaConstants.types;
self.type = 0;

//for transactions
self.transactions = [{}];
self.transactions = ariaConstants.paginator.data;

self.getCustomer = function(val) {
  return Customer.get(val);
}

//init buttons
self.showProgress = false;
self.showNext = false;
self.showPrevious = false;
self.currentPage = ariaConstants.paginator.currentPage;
self.lastPage = ariaConstants.paginator.lastPage;

self.togglePaginator = function() {
  if(self.currentPage == 1) self.showPrevious = false;
  else self.showPrevious = true;
  if(self.currentPage == self.lastPage) self.showNext = false;
  else self.showNext = true;
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

self.submit = function(url) {
  self.showProgress = true;
  var customerId = false;
  if(self.customer) customerId = self.customer.id;

  $http.post(ariaConstants.submitURL, {
    type: self.type,
    customer_id: customerId,
    page: self.currentPage
  })
  .success(function (res) {
    self.transactions = res.data;
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
self.togglePaginator();
})