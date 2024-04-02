angular.module('ariaApp.services')
//.factory('Helper', function($mdDialog, $mdToast, $http, ariaGlobal, ipCookie, $location, $anchorScroll) {
.factory('Helper', function($http, ariaGlobal, ipCookie, $location, $anchorScroll) {
	return {
    scroll: function(val) {
      if(!val) val = 'scrollTo';
      $anchorScroll($location.hash(val));
    },
		parseFloat: function(val) {
      return parseFloat(val) | 0;
		},
    getToday: function() {
      function pad(s) { return (s < 10) ? '0' + s : s; }
      var d = new Date();
      return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
    },
    getLastWeek : function() {
      function pad(s) { return (s < 10) ? '0' + s : s; }
      var today = new Date();
      var lw = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
      return [pad(lw.getDate()), pad(lw.getMonth()+1), lw.getFullYear()].join('/');
    },
    formatError: function(data) {
      var errors = {title: 'error', msg: []};

      if(_.isString(data)) data = { error: [data] };
      var errorsArray = _.flatten(_.values(data))

      _.forEach(errorsArray, function(val, key) {
        errors.msg.push(val);
      })

      return errors;
    },
    getMonths: function() {
      return [1,2,3,4,5,6,7,8,9,10,11,12]
    },
    getYears: function() {
      var years = []
      var start = new Date().getFullYear();
      for(i = start - 5; i <= start; i++)
        years.push(i)

      return years;
    },
    getImageCookie: function(cookie) {
      if(!cookie) cookie = 'aria-showImages';
      return ipCookie(cookie) == true ? true : false;
    },
    setImageCookie: function(value, cookie) {
      if(!cookie) cookie = 'aria-showImages';
      ipCookie(cookie, value);
      //reload blazy
      if(value == true)
      {
        var bLazy = new Blazy({
            src: 'data-blazy' // Default is data-src
        });
      }
    }
	}
})
.filter('html', function($sce) {
  return function(val) {
    return $sce.trustAsHtml(val);
  }
})
