angular.module('ariaApp.controllers')
.controller('customerItemController', function($http, ariaConstants, ipCookie, $scope) {
var self = this;

self.items = [{}];
self.items = ariaConstants.paginator.data;
self.showImages = ipCookie('customer_item_images') == true ? true : false;
self.showOnline = ipCookie('customer_item_images') == true ? true : false;

self.toggleImages = function() {
	ipCookie('customer_item_images', self.showImages);
}

self.toggleOnline = function() {
	ipCookie('customer_item_online', self.showOnline);
}

self.dir = 'desc';

//only works for name
self.toggleSort = function(type) {
  self.resetPage();
  self.sort = type;
  if(self.dir == 'asc') self.dir = 'desc';
    else self.dir = 'asc';
  self.submit();
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

self.submit = function() {
  self.showProgress = true;

  $http.post(ariaConstants.submitURL, {
    name: self.name,
    page: self.currentPage,
    sort: self.sort,
    dir: self.dir,
    show0: self.show0
  })
  .success(function (res) {
    self.items = res.data;
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
