(function() {
    'use strict';
    // Used only for the BottomSheetExample
    angular
        .module('app.material')
        .run(materialRun)
        ;
    materialRun.$inject = ['$http', '$templateCache'];
    function materialRun($http, $templateCache){
        var urls = [
            '/assets/img/icons/share-arrow.svg',
            '/assets/img/icons/upload.svg',
            '/assets/img/icons/copy.svg',
            '/assets/img/icons/print.svg',
            '/assets/img/icons/hangout.svg',
            '/assets/img/icons/mail.svg',
            '/assets/img/icons/message.svg',
            '/assets/img/icons/copy2.svg',
            '/assets/img/icons/facebook.svg',
            '/assets/img/icons/twitter.svg'
        ];

        angular.forEach(urls, function(url) {
            $http.get(url, {cache: $templateCache});
        });
    }

})();
