angular.module('ariaApp.controllers')
.controller('customerSearchController', function($http, ariaConstants, ipCookie, Helper, $scope) {
var self = this;
self.helper = Helper;

self.items = [{}];

self.showImages = ipCookie('invoice-showImages') == true ? true : false;

self.toggleImages = function() {
	ipCookie('invoice-showImages', self.showImages);
}

//init buttons
self.showProgress = false;
self.showNext = false;
self.showPrevious = false;
self.currentPage = 1;
self.lastPage = 1;

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
    page: self.currentPage
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