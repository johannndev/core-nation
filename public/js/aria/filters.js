angular.module('aria-filters', [] )
.filter('trim',[function(){
	return function(val) {
		return _.trim(val)
	}
}])