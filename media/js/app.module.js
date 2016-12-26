/*!
 * 
 * Angle - Bootstrap Admin App + AngularJS Material
 * 
 * Version: 3.0.0
 * Author: @themicon_co
 * Website: http://themicon.co
 * License: https://wrapbootstrap.com/help/licenses
 * 
 */

// APP START
// ----------------------------------- 

(function () {
    'use strict';

    angular
        .module('test', [
            'app.core',
            'app.routes',
            'app.colors',
            'app.notify',
            'app.material',
            'app.table',

            'app.images'
        ]);
})();

