angular
    .module('tlvflats.base')
    .directive("resizeCarousel", scroll);

scroll.$inject = ['$window', '$timeout'];
function scroll($window, $timeout) {
    return function (scope, element) {
        var resizing = function() {
            element
                .css('height', element.width()*0.66+'px');

            // TODO: This is for gallery on property page. Move to separate directive or to gallery directive.
            $('#photos')
                .css('max-height', element.width()*0.66 + 135 + 'px')
                .css('min-height', element.width()*0.66 + 135 + 'px');
        };

        $timeout(function(){
            resizing();
        }, 0);

        angular.element(window).bind('resize', function () {
            resizing();
        });
    }
}