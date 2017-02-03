angular
    .module('tlvflats.property')
    .controller('DialogController', DialogController);

DialogController.$inject = ['$scope', '$mdDialog', 'current'];
function DialogController($scope, $mdDialog, current) {
    var self = this;
    $scope.foo = 'bar';
    $scope.image = current;

    $scope.hide = function() {
        $mdDialog.hide();
    };

    $scope.cancel = function() {
        $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
        $mdDialog.hide(answer);
    };
}