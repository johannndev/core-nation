angular.module('ariaApp.services')
.factory('CustomerTransaction', function($http, ariaConstants) {
	return {
		get: function(val, type) {
            return $http.get(ariaConstants.customersUrl, {
            	params: {
            		q: val,
            		type: type
            	}
            }).then(function(res){
				var customers = [];
				angular.forEach(res.data, function(item){
					customers.push(item);
				});
				return customers;
			});
		}
	}
});