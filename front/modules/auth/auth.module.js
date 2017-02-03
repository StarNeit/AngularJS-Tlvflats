angular
    .module('tlvflats.auth', [
        'tlvflats.base',

        'satellizer',
        'permission'
    ])
    .config(configure)
    .run(run);

configure.$inject = ['$stateProvider'];
function configure($stateProvider) {
    $stateProvider
        .state('auth', {
            url: '/auth',
            controller : 'LoginController',
            controllerAs : 'self',
            templateUrl : 'auth/auth.controller.html'
        });
}

run.$inject = [];
function run() {
}