angular.module('ariaApp.services')
.factory('Customer', function($http, ariaConstants) {
	return {
		get: function(val, type) {
            return $http.get(ariaConstants.customersUrl, {
            	params: {
            		q: val,
            		type: type
            	}
            }).then(function(res){
				return res.data;
			});
		}
	}
});