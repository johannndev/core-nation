angular.module('ariaApp.directives')
.directive('input', function() {
	function jump(elem, e) {
		var inputs = elem.closest('form').find("input:text:not(:disabled)");
		var nextBox = inputs[inputs.index(e) + 1];
		if(nextBox)
		{
			nextBox.focus();
			nextBox.select();
		}
	}
	return {
		restrict: 'E',
		priority: -1000,
		link: function($scope, elem, attrs) {
			elem.bind('keydown', function(e) {
				var code = e.keyCode || e.which;
				if(code != 13)
					return;

				if(elem.attr('add-row')) {
					$scope.$apply($scope.addRow(attrs.addRow));
				}

				jump(elem, this);
				e.preventDefault();
			})
		}
	}
})
.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);