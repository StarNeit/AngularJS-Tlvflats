angular
    .module('tlvflats.search', [
        'tlvflats.base'
    ])
    .config(configure);

configure.$inject = ['$stateProvider'];
function configure($stateProvider) {
    $stateProvider
        .state('search', {
            url: '/search/',
            views: {
                '': {
                    controller: 'SearchController',
                    controllerAs: 'search',
                    templateUrl: 'search/search.controller.html'
                }
            }
        })
}