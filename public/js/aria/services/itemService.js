angular.module('ariaApp.services')
.factory('Item', function($http, ariaConstants) {
	return {
		autocomplete: function(val, jump) {
						var j = jump ? true : false;
            return $http.get(ariaConstants.itemsUrl, {
            	params: {
            		q: val,
            		jump: j
            	}
            }).then(function(res){
				var items = [];
				angular.forEach(res.data, function(item){
					items.push(item);
				});
				return items;
			});
		},
		codeSearch: function(val, type) {
            return $http.get(ariaConstants.itemsUrl, {
            	params: {
            		q: val,
            		type: 'code'
            	}
            });
		},
		batchCode: function(val, quantity, price, type) {
			return $http.get(ariaConstants.itemsUrl, {
				params: {
					q: val,
					price: price,
					quantity: quantity,
					type: 'code'
				}
			});
		},
	}
})
