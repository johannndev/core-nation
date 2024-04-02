angular.module('ariaApp.controllers')
.controller('tagDetailController', function($http, ariaConstants, Helper, $scope) {
var self = this;
self.helper = Helper;
self.errors = []
self.readonly = true;

//init
self.items = [{}];
self.items = ariaConstants.paginator.data;

//init buttons
self.showProgress = false;
self.showNext = false;
self.showPrevious = false;
self.currentPage = ariaConstants.paginator.currentPage;
self.lastPage = ariaConstants.paginator.lastPage;

self.showImages = self.helper.getImageCookie();

self.toggleImages = function() {
  self.helper.setImageCookie(self.showImages)
}

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
    self.showProgress = false;
    return false;
  })
}

//start calling
self.togglePaginator();
})