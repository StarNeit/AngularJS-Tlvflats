angular
    .module('tlvflats.property')
    .directive("highlightOnScroll", highlight);

highlight.$inject = ['$window', '$timeout'];
function highlight($window, $timeout) {
    var $w = angular.element($window);

    return function(scope, element, attr) {
        var fix = () => {
            var navigation = element.children();
            for (var i = 0; i < navigation.length; i++) {
                var el = angular.element(navigation[i]);
                var theID = '#' + navigation[i].innerText.toLowerCase();
                var divPos = $(theID).offset().top - 42;
                var divHeight = $(theID).height();

                if ($window.pageYOffset >= divPos && $window.pageYOffset < (divPos + divHeight)) {
                    el.addClass(attr.highlightOnScroll);
                } else {
                    el.removeClass(attr.highlightOnScroll);
                }
            }
        };

        $timeout(fix, 300);

        $w.on('scroll', fix);

        scope.$on('$destroy', function () {
            $w.off('scroll', fix);
        });
    };
}