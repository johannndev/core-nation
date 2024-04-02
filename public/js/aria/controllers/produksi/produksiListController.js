angular.module('ariaApp.controllers')
.controller('produksiListController', function($http, ariaConstants, $window, Helper, $scope) {
var self = this;
self.helper = Helper;
self.errors = []
self.from = self.to = null;

//init
self.produksi = [{}];
self.jahits = ariaConstants.jahits;
self.potongs = ariaConstants.potongs;
//init transactions
self.produksi = ariaConstants.paginator.data;

//init buttons
self.showProgress = false;
self.showNext = false;
self.showPrevious = false;
self.currentPage = ariaConstants.paginator.currentPage;
self.lastPage = ariaConstants.paginator.lastPage;

self.printSerial = function(index) {
  if(!self.produksi[index]) return false;
  var txt = self.produksi[index].serial;
  if(self.produksi[index].original) txt = txt + ' - ('+ self.produksi[index].original +')';
  return txt;
}

self.jahitInput = function(index) {
  if(!self.produksi[index]) return false;
  return (self.produksi[index].jahit_id);
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

self.isValid = function(index) {
  if(!self.produksi[index] || !self.produksi[index].jahit_form)
    return false;
  return self.produksi[index].jahit_form.id;
}

self.isValidSetor = function(index) {
  if(self.produksi[index].jahit_date)
    return true;
  return false;
}

self.saveRow = function (index) {
  if(!self.produksi[index])
    return false;

  self.produksi[index].disabled = true;

  $http.post(ariaConstants.saveURL, {
    data: self.produksi[index]
  })
  .success(function (res) {
    self.produksi[index] = res.data;
    if(res.split)
      self.produksi.unshift(res.split)
    $scope.$parent.openToast(res.msg)
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.showProgress = false;
    return false;
  })
}

self.setorRow = function (index) {
  if(!self.produksi[index])
    return false;

  self.produksi[index].disabled = true;

  $http.post(ariaConstants.setorURL, {
    data: self.produksi[index]
  })
  .success(function (res) {
    self.produksi.splice(index, 1);
    $scope.$parent.openToast(res.msg)
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.showProgress = false;
    return false;
  })
}

self.displayItemName = function(index) {
  if(!self.produksi[index]) return false;
  if(self.produksi[index].item) return self.produksi[index].item.name;
  return self.produksi[index].temp_name;
}

self.rowDisabled = function(index) {
  if(!self.produksi[index]) return true;
  return self.produksi[index].disabled;
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
    surat_jalan_potong: self.surat_jalan_potong
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