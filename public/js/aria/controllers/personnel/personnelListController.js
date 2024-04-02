angular.module('ariaApp.controllers')
.controller('personnelListController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.errors = []

//init
self.personnels = [{}];
self.depts = ariaConstants.depts;
self.status = ariaConstants.status;
self.dept = self.s = 0;

//init personnels
self.personnels = ariaConstants.paginator.data;

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
    department: self.dept.id,
    status: self.s.id,
    deleted: self.deleted
  })
  .success(function (res) {
    self.personnels = res.data;
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

//start calling
self.togglePaginator();
})
.filter('age', function() {
  return function(bdate) {
    return moment().diff(bdate, 'years', true);
  }
})