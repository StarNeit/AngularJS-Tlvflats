angular
    .module('tlvflats.navbar')
    .directive('navbar', NavbarDirective);

function NavbarDirective() {
    return {
        restrict : 'E',
        templateUrl : 'navbar/navbar.directive.html',
        controller : NavbarDirectiveController,
        controllerAs : 'self'
    }
}

NavbarDirectiveController.$inject = ['$scope', '$timeout', '$mdSidenav', '$log', '$mdMedia'];

function NavbarDirectiveController($scope, $timeout, $mdSidenav, $log, $mdMedia) {
    var self = this;

    self.$mdMedia = $mdMedia;

    self.toggleSidenav = buildDelayedToggler('left');

    self.isOpenSidenav = () => {
        return $mdSidenav('left').isOpen();
    };

    function debounce(func, wait, context) {
        var timer;

        return function debounced() {
            var context = $scope,
                args = Array.prototype.slice.call(arguments);
            $timeout.cancel(timer);
            timer = $timeout(function() {
                timer = undefined;
                func.apply(context, args);
            }, wait || 10);
        };
    }

    function buildDelayedToggler(navID) {
        return debounce(function() {
            $mdSidenav(navID)
                .toggle()
                .then(function () {
                    $log.debug("toggle " + navID + " is done");
                });
        }, 200);
    }

    self.close = function () {
        $mdSidenav('left').close()
            .then(function () {
                $log.debug("close LEFT is done");
            });

    };
}