angular.module('ariaApp.directives')
.directive('focus', function() {
	function jump(elem, e, distance) {
		var inputs = elem.closest('form').find("[focus]");
		var nextBox = $(inputs[inputs.index(e) + distance]);

		if(nextBox) {
			nextBox.focus();
			nextBox.select();
		}
	}
	return {
		restrict: 'A',
		priority: -1000,
		link: function($scope, elem, attrs) {
			elem.bind('keydown', function(e) {
				var code = e.keyCode || e.which;
				var jumpDistance = 1;
				if(code != 13)
					return;
				if(elem.attr('code-search')) {
					$scope.$apply($scope.$parent.cc.codeSearch(elem[0].value, attrs.codeSearch));
					if(!_.isEmpty(elem[0].value))
						jumpDistance = 2;
				}

				if(elem.attr('add-row')) {
					$scope.$apply($scope.$parent.cc.addRow(attrs.addRow));
				}

				jump(elem, this, jumpDistance);
				e.preventDefault();
			})
		}
	}
})
.directive('checkWarehouse', function() {
	return {
		restrict: 'A',
		require: 'ngModel',
		link: function($scope, elem, attrs, ngModel) {
			$scope.$watch(function() {
				return { sender: ngModel.$modelValue.id, receiver: attrs.checkWarehouse };
			}, function(val) {
				if(val.sender == val.receiver)
					elem.addClass('ng-invalid');
				else
					elem.removeClass('ng-invalid');
			}, true)
		}
	}
})