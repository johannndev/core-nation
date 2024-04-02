angular.module('angular-js-xlsx', [])
  .directive('importSheetJs', function () {
    return {
      scope: {},
      link: function (scope, element, attrs) {
        element.on('change', function (changeEvent) {
          var reader = new FileReader();

          reader.onload = function (e) {
            /* read workbook */
            var bstr = e.target.result;
            var workbook = XLSX.read(bstr, {type:'binary'});
            console.log(workbook);
            /* DO SOMETHING WITH workbook HERE */
          };

          reader.readAsBinaryString(changeEvent.target.files[0]);
        });

      }
    };
  });
