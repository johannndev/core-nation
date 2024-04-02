angular.module('ariaApp.controllers')
.controller('transactionListController', function($http, ariaConstants, $window, Helper, $scope) {
  var self = this;
  self.helper = Helper;
  self.errors = []
  self.from = self.to = null;

  //init
  self.transactions = [{}];
  self.types = ariaConstants.types;
  self.type = 0;
  self.hide = ariaConstants.hide;

  //init transactions
  self.transactions = ariaConstants.paginator.data;

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
      from: self.from,
      to: self.to,
      invoice: self.invoice,
      type: self.type,
      total: self.total,
      page: self.currentPage
    })
    .success(function (res) {
      self.transactions = res.data;
      self.showProgress = false;
      self.currentPage = res.currentPage;
      self.lastPage = res.lastPage;
      self.togglePaginator();
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

  //start calling
  self.togglePaginator();
})