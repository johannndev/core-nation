angular.module('ariaApp.controllers')
.controller('setoranListController', function($http, ariaConstants, $window, Helper, $scope, Item) {
var self = this;
self.helper = Helper;
self.errors = []
self.from = self.to = null;

//init
self.setoran = [{}];
self.jahits = ariaConstants.jahits;
self.potongs = ariaConstants.potongs;
self.statuses = ariaConstants.statuses;
//init transactions
self.setoran = ariaConstants.paginator.data;

//init buttons
self.showProgress = false;
self.showNext = false;
self.showPrevious = false;
self.currentPage = ariaConstants.paginator.currentPage;
self.lastPage = ariaConstants.paginator.lastPage;

self.getTotal = function() {
  var total = 0;
  _.forEach(self.setoran, function(val) {
    total += val.quantity;
  })
  return total;
}

self.getClass = function(status) {
  switch(status) {
    case 3: return 'class3'; break;
    case 5: return 'class5'; break;
    case 15: return 'class15'; break;
    default: return 'class1'; break;
  }
}

self.printSerial = function(index) {
  if(!self.setoran[index]) return false;
  var txt = self.setoran[index].serial;
  if(self.setoran[index].original) txt = txt + ' - ('+ self.setoran[index].original +')';
  return txt;
}

self.toggleItemInput = function (index) {
  if(!self.setoran[index].itemInput) self.setoran[index].itemInput = true;
  else self.setoran[index].itemInput = false;

  if(self.setoran[index].item_id) self.setoran[index].item_form = self.setoran[index].item.name;
  else self.setoran[index].item_form = self.setoran[index].temp_name;

  self.setoran[index].item_form_old_id = self.setoran[index].item_id;

  return self.setoran[index].itemInput;
}

self.itemInput = function(index) {
  return self.setoran[index].itemInput;
}

self.canEditItem = function(index) {
  if(self.setoran[index].invoice)
    return false;
  return true;
}

self.getItem = function(val) {
  return Item.autocomplete(val, false);
}

self.displayItemName = function(index) {
  if(!self.setoran[index]) return false;
  if(self.setoran[index].item) return self.setoran[index].item.name;
  return self.setoran[index].temp_name;
}

self.gudangInput = function(index) {
  if(!self.setoran[index]) return false;
  return self.setoran[index].invoice;
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

self.onSelectItem = function ($model, $index) {
  self.setoran[$index].item_id = $model.id;
  self.setoran[$index].item = $model;
  self.setoran[$index].item_form_id = $model.id;
}

self.isValid = function(index) {
  if(!self.setoran[index])
    return false;

  //selecting new item?
  if(self.setoran[index].item_form_id && self.setoran[index].item_form_old_id != self.setoran[index].item_form_id) return true;
  return false;
}

self.isValidGudang = function(index) {
  if(!self.setoran[index])
    return false;

  if(!self.setoran[index].invoice_form || !self.setoran[index].item_id) return false;
  return true;
}

self.saveItem = function(index) {
  self._save(index, ariaConstants.saveURL)
}

self.gudangRow = function(index) {
  self._save(index, ariaConstants.gudangURL)
}

self._save = function(index, saveURL) {
  if(!self.setoran[index])
  return false;

  self.setoran[index].disabled = true;
  self.setoran[index].itemInput = false;

  $http.post(saveURL, {
    data: self.setoran[index],
    id: self.setoran[index].id,
    invoice: self.setoran[index].invoice_form
  })
  .success(function (res) {
    $scope.$parent.openToast(res.msg)
    self.clearForm(index);
    //only works for invoice
    self.setoran[index].transaction_link = res.data.transaction_link ? res.data.transaction_link : false;
    self.setoran[index].invoice = res.data.invoice ? res.data.invoice : false;
    self.setoran[index].status = res.data.status ? res.data.status : 1; //any number is fine, css defined
    return false;
  })
  .error(function(data, status, headers, config) {
    self.errors = self.helper.formatError(data);
    $scope.$parent.openErrorModal(self.errors)
    self.showProgress = false;
    return false;
  })
}

self.submit = function() {
  self.showProgress = true;
  var potong_id = self.potong? self.potong.id : 0;
  var jahit_id = self.jahit? self.jahit.id : 0;
  var status_id = self.status? self.status.id : false;

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
    status: status_id,
    invoice: self.invoice,
    surat_jalan_potong: self.surat_jalan_potong
  })
  .success(function (res) {
    self.setoran = res.data;
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

self.rowDisabled = function(index) {
  if(!self.setoran[index]) return true;
  return self.setoran[index].disabled;
}

self.clearForm = function(index) {
  self.setoran[index].invoice_form = '';
  self.setoran[index].item_form = '';
  self.setoran[index].itemInput = false;
  self.setoran[index].item_form_id = false;
}

self.openDeleteModal = function(index) {
  self.delete_serial = self.setoran[index].serial;
  self.delete_id = self.setoran[index].id;
  self.delete_index = index;
  $('#deleteModal').modal('open');
}

self.closeModal = function() {
  self.delete_serial = false;
  self.delete_id = false;
  self.delete_index = false;
  $('#deleteModal').modal('close');
}

self.delete = function() {
  self.showProgress = true;

  $http.post(ariaConstants.deleteURL, {
    id: self.delete_id
  })
  .success(function (res) {
    //remove delete_id from array
    if(res.success)
      self.setoran.splice(self.delete_index, 1);
    self.closeModal();
    self.showProgress = false;
    var modal = {}
    modal.msg = res.msg;
    $scope.$parent.openSuccessModal(modal)
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