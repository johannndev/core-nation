angular.module('ariaApp.services')
.factory('GeoData', function($http, ariaConstants) {

	var GeoData = {
		getCustomer: function(type) {
			var data = $http.get(ariaConstants.geoDataUrl, {
				params: {
					type: type
				}
			}).then(function(response) {
				return response.data;
			})

			return data;
		}
	}
	return GeoData;
});