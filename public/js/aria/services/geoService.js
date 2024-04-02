angular.module('ariaApp.services')
.factory('Geo', function($http, ariaConstants) {

	var Geo = {
		getCity: function(val) {
			var data = $http.get(ariaConstants.cityUrl, {
				params: {
					p: val
				}
			}).then(function(response) {
				return response.data;
			})

			return data;
		}
	}
	return Geo;
});