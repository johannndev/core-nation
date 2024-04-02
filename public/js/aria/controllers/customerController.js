angular.module('ariaApp.controllers', [])
.controller('customerController', [ '$scope', '$http', '$filter' ,'dateFilter', 'Geo', 'ariaConstants', '$modal', '$window', function($scope, $http, $filter,dateFilter, Geo, ariaConstants, $modal, $window) {
    $scope.customer = {}
    $scope.errors = []

    //init
    $scope.provinces = ariaConstants.provinces;
    $scope.cities = ariaConstants.cities;
    $scope.customer = ariaConstants.customer;
    $scope.categories = ariaConstants.categories;

    _.forEach($scope.provinces, function(val) {
      if(val.id == $scope.customer.province_id)
        $scope.province = val;
    })

    _.forEach($scope.cities, function(val) {
      if(val.id == $scope.customer.city_id)
        $scope.city = val;
    })

    //init buttons
    $scope.disableSubmit = false;

    if(ariaConstants.types) {
      $scope.types = ariaConstants.types;
    }

    $scope.getCity = function() {
      Geo.getCity($scope.province.id).then(function(res) {
        $scope.cities = res;
        $scope.city = $scope.cities[0]
      });
    }

    $scope.openDate = function($event) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.dateOpen = true;
    }

    $scope.openContract = function($event) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.contractOpen = true;
    }

    $scope.submit = function(isValid) {
      $scope.disableSubmit = true;
      $scope.customer.date = dateFilter($scope.date, 'd/M/yyyy');
      if($scope.province) $scope.customer.province_id = $scope.province.id;
      if($scope.city) $scope.customer.city_id = $scope.city.id;

      if($scope.contract)
        $scope.customer.contract_ends = dateFilter($scope.contract, 'd/M/yyyy');

      $http.post(document.URL, {
          customer: $scope.customer,
          initial: $scope.initial
      })
      .success(function (res) {
        $window.location.href = res.url;
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
        $scope.modalError();

        return false;
      });
    }

  $scope.modalError = function () {
    var modalInstance = $modal.open({
      templateUrl: 'modalError.html',
      controller: ModalInstanceCtrl,
    });
  }

}]);

var ModalInstanceCtrl = function ($scope, $modalInstance) {
  $scope.ok = function () {
    $modalInstance.close();
  };
};