
(function() {
    'use strict';
    // Used only for the BottomSheetExample
    angular
        .module('app.material')
        .config(materialConfig)
        ;
    materialConfig.$inject = ['$mdIconProvider'];
    function materialConfig($mdIconProvider){
      $mdIconProvider
        .icon('share-arrow', '/assets/img/icons/share-arrow.svg', 24)
        .icon('upload', '/assets/img/icons/upload.svg', 24)
        .icon('copy', '/assets/img/icons/copy.svg', 24)
        .icon('print', '/assets/img/icons/print.svg', 24)
        .icon('hangout', '/assets/img/icons/hangout.svg', 24)
        .icon('mail', '/assets/img/icons/mail.svg', 24)
        .icon('message', '/assets/img/icons/message.svg', 24)
        .icon('copy2', '/assets/img/icons/copy2.svg', 24)
        .icon('facebook', '/assets/img/icons/facebook.svg', 24)
        .icon('twitter', '/assets/img/icons/twitter.svg', 24);
    }
})();
