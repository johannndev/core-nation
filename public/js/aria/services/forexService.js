angular.module('ariaApp.services')
.factory('Forex', function($http) {

	var fixedEncodeURIComponent = function(str) {
		return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A").replace(/\"/g, "%22");
	};
	var format = '&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=JSON_CALLBACK';

	return {
		get: function() {
			var query = 'select * from yahoo.finance.xchange where pair in ("USDIDR","EURUSD","GBPUSD","USDJPY","CNYIDR")';
            var url = 'https://query.yahooapis.com/v1/public/yql?q=' + fixedEncodeURIComponent(query) + format;
            return $http.jsonp(url);
		}
	}
})
