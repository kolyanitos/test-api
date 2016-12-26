/**=========================================================
 * Module: UIGridController
 =========================================================*/

(function () {
    'use strict';

    angular
        .module('app.table')
        .controller('smartTableController', smartTableController)
        .directive('pageSelect', function() {
            return {
                restrict: 'E',
                template: '<input type="text" class="select-page" ng-model="inputPage" ng-change="selectPage(inputPage)">',
                link: function(scope, element, attrs) {
                    scope.$watch('currentPage', function(c) {
                        scope.inputPage = c;
                    });
                }
            }
        })
        .factory('Resource', ['$http', function ($http) {
            //fake call to the server, normally this service would serialize table state to send it to the server (with query parameters for example) and parse the response
            //in our case, it actually performs the logic which would happened in the server
            function getPage(table, attrs) {
                var f = '';
                if (table.search.predicateObject && ('tags' in table.search.predicateObject)) {
                    f = table.search.predicateObject['tags'];
                }

                var data = {
                    page: (table.pagination.start / table.pagination.number),
                    rows: table.pagination.number,
                    filter: f
                };

                if (table.sort.predicate) {
                    data.sort = table.sort.predicate;
                    data.sortOrder = (table.sort.reverse ? 'desc' : 'asc');
                }

                return $http({
                    method: 'GET',
                    url: attrs.tableUrl,
                    params: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                });
            }

            return {
                getPage: getPage
            };

        }])
    ;

    smartTableController.$inject = ['$scope', '$attrs', '$http', 'Resource', 'Notify'];
    function smartTableController($scope, $attrs, $http, r, Notify) {
        var vm = this;
        vm.actionUrl = $attrs.actionUrl;
        vm.itemsByPage = $attrs.byPage;
        vm.rows = [];
        vm.tableState = {};

        // Fetch data from server
        vm.callServer = function callServer(tableState) {
            vm.tableState = tableState;
            vm.isLoading = true;

            tableState.pagination.number = vm.itemsByPage;

            r.getPage(tableState, $attrs).then(function (response) {
                vm.rows = response.data.results;
                tableState.pagination.numberOfPages = response.data.total;
                tableState.pagination.totalItemCount = response.data.records;

                vm.isLoading = false;
            });
        };

        vm.remove = function(id) {
            vm.isLoading = true;

            $http({
                method: 'DELETE',
                url: vm.actionUrl + id,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function(response) {
                Notify.alert(
                    '<em class="fa fa-check"></em> Deleted',
                    {status: 'success'}
                );

                vm.tableState.pagination.start = 0;

                vm.callServer(vm.tableState);
            });
        };

        // Reload table page
        $scope.$on('reloadTable', function(event) {
            vm.callServer(vm.tableState);

            //vm.rows.push(row);
        });
    };
})();
