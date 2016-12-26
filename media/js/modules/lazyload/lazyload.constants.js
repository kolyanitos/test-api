(function () {
    'use strict';

    angular
        .module('app.lazyload')
        .constant('APP_REQUIRES', {
            // jQuery based and standalone scripts
            scripts: {

            },
            // Angular based script (use the right module name)
            modules: [
                {name: 'st', files: ['/assets/vendor/angular-smart-table/dist/smart-table.min.js']},
                {name: 'ngFileUpload', files: ['/assets/vendor/ng-file-upload/ng-file-upload.min.js']}
            ]
        })
    ;

})();
