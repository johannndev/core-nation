angular.module('ariaApp.services')
.factory('News', function($http) {

	var fixedEncodeURIComponent = function(str) {
		return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A").replace(/\"/g, "%22");
	};
	var format = '&callback=JSON_CALLBACK';

	return {
		get: function() {
			var query = 'https://news.google.com/news/feeds?output=rss';
            var url = 'https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&q=' + fixedEncodeURIComponent(query) + format;
            return $http.jsonp(url);
		}
	}
})
