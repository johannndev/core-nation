angular.module('ariaApp.controllers', [])
.controller('geoController', [ '$scope', '$http', 'dateFilter', 'GeoCoder', 'GeoData', 'ariaConstants', function($scope, $http, dateFilter, GeoCoder, GeoData,ariaConstants) {
$scope.map;
$scope.provinceData = []
$scope.data = []
$scope.grandTotal = { sell: 0, cashIn: 0, return: 0, revenue: 0, customers: 0}

$scope.customerTypes = ariaConstants.customerTypes;
$scope.defaults = ariaConstants.defaults;

$scope.loaded = {
  province: [],
  city: []
}

//init provinces, so any data not returned by db is set to 0
$scope.initData = function(type) {
  $scope.data[type] = {
    province: {
      markers: [], infos: [], data: []
    },
    city: []
  }

  _.forEach(ariaConstants.provinces, function(p) {
    var data = {id: p.id, lat: p.lat, lng: p.lng, name: p.name, total_customers: 0, total_sell: 0, total_return: 0, total_buy: 0, total_cash_in: 0, total_cash_out: 0, revenue: 0}
    //init provice data
    $scope.data[type].province.data.push(data);
    //init city in provice data
    $scope.data[type].city = {
      pId: p.id, markers: [], infos: [], data: []
    }
  })
}

$scope.clearTotal = function() {
  $scope.grandTotal = { sell: 0, cashIn: 0, return: 0, revenue: 0, customers: 0}
}

$scope.calculateTotal = function() {
  $scope.clearTotal();
  _.forEach($scope.provinceData, function(data, index) {
    $scope.grandTotal.sell += parseFloat(data.total_sell);
    $scope.grandTotal.cashIn += parseFloat(data.total_cash_in);
    $scope.grandTotal.return += parseFloat(data.total_return);
    $scope.grandTotal.revenue += parseFloat(data.revenue);
    $scope.grandTotal.customers += parseFloat(data.total_customers);
  })
}

//init data
_.forEach($scope.customerTypes, function(c) {
  if($scope.defaults.customerType == c.id)
    $scope.customerType = c;

  $scope.initData(c.id);
})
$scope.currentCustomer = $scope.customerType.id;

//initial data
_.forEach(ariaConstants.provinceData, function(p) {
  var index = _.findIndex($scope.data[$scope.customerType.id].province.data, function(res) {
    return res.id == p.id
  })
  if(index >= 0) {
    $scope.data[$scope.customerType.id].province.data[index] = p;
    $scope.data[$scope.customerType.id].province.data[index].revenue = p.total_sell - p.total_return;
  }
})
$scope.provinceData = $scope.data[$scope.customerType.id].province.data;
$scope.tableData = [].concat($scope.provinceData); //for smart table
$scope.loaded.province.push($scope.customerType.id);
$scope.calculateTotal();

//first time initialized map
$scope.$on('mapInitialized', function(evt, evtMap) {
  $scope.map = evtMap;
  var zoomTo;
  var i = 0;

  _.forEach($scope.provinceData, function(province) {
    var position = $scope.addMarker(province.lat, province.lng, province.name, province.total_customers);
    zoomTo = position;
    i++;
  })
  $scope.map.panTo(zoomTo);

});

$scope.setAllMap = function(map, type) {
  console.log('testing variable reference')
  //province only! need more code for cities
  console.log('setting map for: ' + type)
  var markers = $scope.data[type].province.markers;
  for(var i = 0; i < markers.length; i++)
    markers[i].setMap(map);
}

$scope.clearMarkers = function(type) {
  $scope.setAllMap(null, type);
}

$scope.showMarkers = function(type) {
  $scope.setAllMap($scope.map, type);
}

$scope.addMarker = function(lat, lng, name, total) {
  var position = new google.maps.LatLng(lat, lng);
  var customerId = $scope.customerType.id;

  var index = $scope.data[customerId].province.markers.length;
  var marker = new google.maps.Marker({position: position, map: $scope.map});
  var info = new google.maps.InfoWindow({content: name + ": " + total });

  info.open($scope.map, marker);
  google.maps.event.addListener(marker, 'click', function() {
    info.open($scope.map, marker);
  });

  $scope.data[customerId].province.infos[index] = info;
  $scope.data[customerId].province.markers[index] = marker;

  return position;
}

$scope.changeCustomer = function() {
  //clear old markers
  $scope.clearMarkers($scope.currentCustomer);
  //set the current customer
  $scope.currentCustomer = $scope.customerType.id;

  //clear current data
  $scope.provinceData = $scope.data[$scope.customerType.id].province.data;
  $scope.calculateTotal();

  var index = _.findIndex($scope.loaded.province, function(p) {
    return $scope.currentCustomer == p
  })

  //if loaded, don't need to call ajax just set the marker
  if(index >= 0) {
    console.log('customer type: ' + $scope.currentCustomer + ' found in index: ' + index)
    _.forEach($scope.provinceData, function(data) {
      $scope.addMarker(data.lat, data.lng, data.name, data.total);
    })
    return true;
  }

  //get the data
  GeoData.getCustomer($scope.customerType.id).then(function(res) {
    var customerId = $scope.customerType.id;
    $scope.clearTotal();

    _.forEach(res, function(data) {
      var index = _.findIndex($scope.provinceData, function(p) {
        return p.id == data.id
      })
      console.log('setting data for: ' + index)

      if(index >= 0) {
        //store to local var
        $scope.data[customerId].province.data[index] = data;
        $scope.provinceData[index] = data;
      }

      $scope.addMarker(data.lat, data.lng, data.name, data.total_customers);
    });


    $scope.calculateTotal();
    $scope.loaded.province.push($scope.customerType.id);
  });
}

$scope.loadCities = function(provinceId) {

}
}])