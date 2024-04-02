angular.module('ariaApp.controllers')
.controller('customerSalesController', function($http, ariaConstants, ipCookie, Helper, $scope, Item) {
var self = this;
self.helper = Helper;
self.from = self.to = null;

self.data = [{}];
self.data = ariaConstants.paginator.data;
self.types = ariaConstants.types;

self.showImages = ipCookie('customer_sales') == true ? true : false;

self.toggleImages = function() {
	ipCookie('customer_sales', self.showImages);
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

self.getItem = function(val) {
  return Item.autocomplete(val);
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

self.submit = function() {
  self.showProgress = true;
  var itemId = 0;

  if(self.item) itemId = self.item.id
  $http.post(ariaConstants.submitURL, {
    from: self.from,
    to: self.to,
    name: self.name,
    type: self.type,
    item: itemId,
    page: self.currentPage
  })
  .success(function (res) {
    self.data = res.data;
    self.showProgress = false;
    self.currentPage = res.currentPage;
    self.lastPage = res.lastPage;
    self.togglePaginator();
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.disableSubmit = false;
    return false;
  });
}

self.togglePaginator();

})