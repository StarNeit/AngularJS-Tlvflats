angular
    .module('tlvflats.about', [
        'tlvflats.base'
    ])
    .config(configure);

configure.$inject = ['$stateProvider'];
function configure($stateProvider) {
    $stateProvider
        .state('about', {
            url: '/about',
            controller : function(){},
            controllerAs : 'self',
            templateUrl : 'about/about.controller.html'
        });
}