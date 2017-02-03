angular
    .module('tlvflats.property', [
        'tlvflats.base'
    ])
    .config(configure);

configure.$inject = ['$stateProvider'];
function configure($stateProvider) {
    $stateProvider
        .state('property', {
            url: '/property/:id',
            views: {
                '': {
                    controller: 'PropertyController',
                    controllerAs: 'self',
                    templateUrl: 'property/property.controller.html'
                }
            },
            resolve : {
                property : [ '$stateParams', 'PropertyService', function($stateParams, PropertyService) {
                    return PropertyService.getOne($stateParams.id);
                }]
            }
        })
}