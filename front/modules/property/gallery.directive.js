angular
    .module('tlvflats.property')
    .directive('gallery', Gallery);

function Gallery() {
    return {
        restrict : 'A',
        scope : {
            photos : "=photos"
        },
        templateUrl : '/property/gallery.directive.html',
        controller : GalleryController
    }
}

GalleryController.$inject = ['$scope', '$timeout', '$rootScope', '$mdDialog'];

function GalleryController($scope, $timeout, $rootScope, $mdDialog) {
    $scope.current = 0;

    $scope.changeCurrent = (i) => {
        if (i < 0) return;
        $scope.current = i;
        change();
    };

    $scope.openFullscreen = function(ev) {
        $mdDialog.show({
                controller: 'DialogController',
                templateUrl: './partials/gallery.fullscreen.html',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose: true,
                fullscreen: false,
                resolve: {
                    current: function () {
                        return $scope.photos[$scope.current]
                    }
                }
            });
    };

    var change = () => {
        var previewsWidth = angular.element($('.previews .images')).width();
        var visblock = angular.element($('.previews .images .vis-block')).width();
        var imageWidth = angular.element($('.previews .img')).width();

        var offset = $scope.current * imageWidth;

        if ((visblock - previewsWidth) < 0) {
            $scope.offset = 0;
            return;
        }

        if (offset > (visblock - previewsWidth)) {
            $scope.offset = -(visblock - previewsWidth) - 10;
        } else {
            $scope.offset = -$scope.current * imageWidth;
        }
    };

    $timeout(function(){
        change();
        $rootScope.$emit('gallery:ready')
    }, 0);

    angular.element(window).bind('resize', function () {
        change();
    });
}