angular
    .module('tlvflats.base', [
        'ui.router',
        'ngMaterial',
        'angular-carousel',
        'uiGmapgoogle-maps',
        '720kb.datepicker'
    ])
    .config(configure)
    .run(run);

configure.$inject = ['$locationProvider', '$stateProvider', '$urlRouterProvider', 'uiGmapGoogleMapApiProvider', '$mdThemingProvider'];
function configure($locationProvider, $stateProvider, $urlRouterProvider, GoogleMapApiProviders, $mdThemingProvider) {
    $locationProvider.html5Mode({
        enabled : true,
        requireBase : true,
        rewriteLinks : true
    });

    $stateProvider
        .state('front', {
            url : '/',
            template: '',
            controller : ['$state', function ($state){ $state.go('search') }]
        })
        .state('403', {
            url : '/403',
            template : '<h1>Unauthorised access</h1>'
        })
        .state('404', {
            url : '/404',
            template : '<h1>Page not found</h1>'
        })
        .state('500', {
            url : '/500',
            template : '<h1>Internal error</h1>'
        });

    $urlRouterProvider.otherwise('/');

    GoogleMapApiProviders.configure({
        v: '3.17',
        libraries: '',
        china: true,
        key: 'AIzaSyA0LgN5tWhnX-DYnbsiRPrd3QsaxlsGiC4'
    });

    $mdThemingProvider.theme('indigo')
        .primaryPalette('indigo', {
            'default': '500',
            'hue-1': '400',
            'hue-2': '600',
            'hue-3': 'A100'
        })
        .accentPalette('green', {
            'default': '500',
            'hue-1': '100',
            'hue-2': '600',
            'hue-3': 'A100'
        })
        .warnPalette('orange');

    $mdThemingProvider.theme('lime')
        .primaryPalette('lime')
        .accentPalette('orange')
        .warnPalette('blue');

    $mdThemingProvider.theme('red')
        .primaryPalette('pink')
        .accentPalette('purple')
        .warnPalette('orange');

    $mdThemingProvider.theme('yellow')
        .primaryPalette('orange')
        .accentPalette('yellow')
        .warnPalette('blue');

    //$mdThemingProvider.alwaysWatchTheme(true);
}

run.$inject = ['$state', '$rootScope'];
function run($state, $root) {
    $root.$state = $state;


    // Take theme from locastorage
    $root.theme = 'indigo';
}