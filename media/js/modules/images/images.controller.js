(function () {
    'use strict';

    angular
        .module('app.images')
        .controller('imagesController', imagesController);


    imagesController.$inject = ['$scope', '$http', '$mdDialog', 'Notify'];
    function imagesController($scope, $http, $mdDialog, Notify) {
        var vm = this;

        vm.hide = function() {
            $mdDialog.hide(false);
        };

        vm.cancel = function() {
            $mdDialog.cancel();
        };

        vm.addModal = function(ev) {
            $mdDialog.show({
                    controller: addModalInstanceCtrl,
                    templateUrl: '/templates/image_modal.tmpl.html',
                    targetEvent: ev,
                    escapeToClose: true,
                    clickOutsideToClose: true
                })
                .then(function(reloadTable) {
                    if (reloadTable) {
                        $scope.$broadcast('reloadTable');
                    }
                }, function() {

                });
        };
        addModalInstanceCtrl.$inject = ['$scope', '$mdDialog', 'Upload', 'Notify'];
        function addModalInstanceCtrl($scope, $mdDialog, Upload, Notify) {
            $scope.hide = vm.hide;
            $scope.cancel = vm.cancel;
            $scope.actionType = 'Add';
            $scope.item = {
                tags: []
            };

            $scope.submit = function (form) {
                // Trigger validation flag.
                $scope.submitted = true;

                // If form is invalid, return and let AngularJS show validation errors.
                if (form.$invalid) {
                    return;
                }

                Upload.upload({
                    url: '/api/images',
                    data: $scope.item
                }).then(function(r) {
                    Notify.alert(
                        '<em class="fa fa-check"></em> Added',
                        {status: 'success'}
                    );

                    $mdDialog.hide(true);
                }, function (r) {
                    Notify.alert(
                        '<em class="fa fa-exclamation-triangle"></em> Error status: '  + r.status,
                        {status: 'danger'}
                    );
                });
            };
        }

        vm.editModal = function(ev, url, id) {
            vm.editId = id;
            vm.editUrl = url;

            $http({
                method: 'GET',
                url: url + id
            }).then(function (r) {
                vm.row = r.data;
                if (r.data.tags.length) {
                    var tags = [];
                    for (var i in r.data.tags) {
                        tags.push(r.data.tags[i].tag_name);
                    }

                    r.data.tags = tags;
                }

                $mdDialog.show({
                        controller: editModalInstanceCtrl,
                        templateUrl: '/templates/image_modal.tmpl.html',
                        targetEvent: ev,
                        escapeToClose: true,
                        clickOutsideToClose: true
                    })
                    .then(function() {
                        $scope.$broadcast('reloadTable');
                    }, function() {

                    });
            });
        };
        editModalInstanceCtrl.$inject = ['$scope', '$mdDialog', 'Upload', 'Notify'];
        function editModalInstanceCtrl($scope, $mdDialog, Upload, Notify) {
            $scope.hide = vm.hide;
            $scope.cancel = vm.cancel;
            $scope.item = vm.row;
            $scope.actionType = 'Edit';

            $scope.submit = function (form) {
                // Trigger validation flag.
                $scope.submitted = true;

                // If form is invalid, return and let AngularJS show validation errors.
                if (form.$invalid) {
                    return;
                }

                $scope.item._method = 'PUT';

                Upload.upload({
                    url: vm.editUrl + vm.row.id,
                    data: $scope.item,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(r) {
                    Notify.alert(
                        '<em class="fa fa-check"></em> Saved',
                        {status: 'success'}
                    );

                    $mdDialog.hide();
                }, function (r) {
                    Notify.alert(
                        '<em class="fa fa-exclamation-triangle"></em> Error status: '  + r.status,
                        {status: 'danger'}
                    );
                });
            };
        }
    }
})();
