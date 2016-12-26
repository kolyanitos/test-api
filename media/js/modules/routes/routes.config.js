/**=========================================================
 * Module: config.js
 * App routes and resources configuration
 =========================================================*/


(function() {
    'use strict';

    angular
        .module('app.routes')
        .config(routesConfig);

    routesConfig.$inject = ['$stateProvider', '$locationProvider', '$urlRouterProvider', 'RouteHelpersProvider'];
    function routesConfig($stateProvider, $locationProvider, $urlRouterProvider, helper){

        // Set the following to true to enable the HTML5 Mode
        // You may have to set <base> tag in index and a routing configuration in your server
        $locationProvider.html5Mode(false);

        // defaults
        $urlRouterProvider.otherwise('/');

        //
        // Application Routes
        // -----------------------------------
        $stateProvider
            .state('images', {
                url: '/',
                title: 'test',
                templateUrl: '/templates/images.html',
                resolve: helper.resolveFor('st', 'ngFileUpload')
            })

          ;

    } // routesConfig

})();

