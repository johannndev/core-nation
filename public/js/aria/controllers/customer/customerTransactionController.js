angular.module('ariaApp.controllers')
.controller('customerTransactionController', function($http, ariaConstants, ipCookie, $scope) {
var self = this;

self.types = ariaConstants.types;
self.type = 0;

self.tracker = ariaConstants.tracker;

//for transactions
self.transactions = [{}];
self.transactions = ariaConstants.paginator.data;

self.hide = ariaConstants.hide;

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

  $http.post(ariaConstants.submitURL, {
    page: self.currentPage,
    from: self.from,
    to: self.to,
    type: self.type
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

self.track = function() {
  _.forEach(self.transactions, function(t, index) {
    if(_.indexOf(self.tracker.transaction_ids, "" + t.id) >= 0)
      self.transactions[index].class = 'red lighten-5';
    if(t.id == self.tracker.partial_id)
      self.transactions[index].partial = self.tracker.partial_balance;
  })
}

self.due = function($index) {
  return Math.abs(self.transactions[$index].total) - self.transactions[$index].partial;
}

self.track();
self.togglePaginator();
})