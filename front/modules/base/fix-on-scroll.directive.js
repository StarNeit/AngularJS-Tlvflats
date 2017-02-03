angular
    .module('tlvflats.base')
    .directive("fixOnScroll", fixOnScroll);

fixOnScroll.$inject = ['$window', '$timeout'];
function fixOnScroll($window, $timeout) {
    var $w = angular.element($window);

    return function(scope, element, attr) {
        var offsetT, offsetL,
            width,
            padding_top = attr.fixPdTop,
            fix_when = attr.fixWhen ? attr.fixWhen : null;

        var el = element.children(':first');

        var fix = () => {
            if (fix_when && $w.innerWidth() < fix_when) { return; }

            // TODO: after changing state offset top is wrong
            offsetT = element.offset().top-60;
            offsetL = element.offset().left;
            width = element.width();

            if ($window.pageYOffset >= offsetT) {
                el
                    .css('position', 'fixed')
                    .css('width', width + 'px')
                    .css('left', offsetL + 'px')
                    .css('top', padding_top + 'px');
            } else {
                el
                    .css('position', 'initial')
                    .css('width', '100%');
            }
        };

        $timeout(fix, 500);

        $w.on('resize', fix);
        $w.on('scroll', fix);

        scope.$on('$destroy', function () {
            $w.off('resize', fix);
            $w.off('scroll', fix);
        });
    };
}