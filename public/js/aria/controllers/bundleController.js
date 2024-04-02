angular.module('ariaApp.controllers', [])
.controller('bundleController', [ '$scope', '$http', 'Item', 'ariaConstants', '$modal', '$window', function($scope, $http, Item, ariaConstants, $modal, $window) {
    $scope.bundle = {}
    $scope.errors = []

    //init
    $scope.items = [];

    //init buttons
    $scope.disableSubmit = false;

    $scope.getItem = function(val) {
      return Item.autocomplete(val);
    }

    $scope.addRow = function(index) {
      if($scope.quantity > 0 && _.isObject($scope.item))
        if($scope.items.length < 31) {
          $scope.items.push({
            "id" : $scope.item.id,
            "name": $scope.item.name,
            "code": $scope.item.code,
            "quantity": $scope.quantity
          })
          $scope.item = null;
          $scope.quantity = 0;
        }
    }

    $scope.removeRow = function (index) {
      if ($scope.items.length > 1)
        $scope.items.splice(index, 1);
    }

    $scope.submit = function(isValid) {
      $scope.disableSubmit = true;

      var confirmModal = $modal.open({
        templateUrl: 'modalConfirm.html',
        controller: ModalInstanceCtrl,
      })

      confirmModal.result.then(function(selected) {
        if(!selected) return false;

        var file = $scope.myFile;

        var fd = new FormData();
        fd.append('file', $scope.myFile);
        fd.append('bundle', angular.toJson($scope.bundle));
        fd.append('items', angular.toJson($scope.items));

        $http.post(document.URL, fd, {
            transformRequest: function(data) { return data; },
            headers: {'Content-Type': undefined}
        })
        .success(function (res) {
//          $window.location.href = res.url;
          return false;
        })
        .error(function(data, status, headers, config) {
          $scope.errors = [];

          if(_.isString(data)) data = { error: [data] };
          var errors = _.flatten(_.values(data))

          _.forEach(errors, function(val, key) {
            $scope.errors.push({ name: 'error', msg: val});
          })

          $scope.disableSubmit = false;
          $scope.modal();
          return false;
        });


      }, function() {
        $scope.disableSubmit = false;
      });
    }

  $scope.modalError = function () {
    var modalInstance = $modal.open({
      templateUrl: 'modalError.html',
      controller: ModalInstanceCtrl,
    });
  }
}])

var ModalInstanceCtrl = function ($scope, $modalInstance) {
  $scope.ok = function () {
    $modalInstance.close(true);
  };

  $scope.cancel = function() {
    $modalInstance.dismiss();
  };
};