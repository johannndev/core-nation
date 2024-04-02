angular.module('ariaApp.controllers')
.controller('spgListController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.errors = []
self.from = self.to = null;

//init
self.spg = [{}];
//init transactions
self.spg = ariaConstants.paginator.data;
console.log(self.spg)

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

self.displayItemName = function(index) {
  if(!self.produksi[index]) return false;
  if(self.produksi[index].item) return self.produksi[index].item.name;
  return self.produksi[index].temp_name;
}

self.submit = function() {
  self.showProgress = true;
  var potong_id = self.potong? self.potong.id : 0;
  var jahit_id = self.jahit? self.jahit.id : 0;

  $http.post(ariaConstants.submitURL, {
    from: self.from,
    to: self.to,
    serial: self.serial,
    kode: self.kode,
    potong_id: potong_id,
    jahit_id: jahit_id,
    warna: self.warna,
    customer: self.customer,
    page: self.currentPage,
    original: self.original,
    original_id: self.original_id,
  })
  .success(function (res) {
    self.produksi = res.data;
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