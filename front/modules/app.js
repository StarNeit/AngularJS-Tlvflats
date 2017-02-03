angular
    .module('tlvflats', [
        'tlvflats.base',
        'tlvflats.navbar',

        'tlvflats.auth',
        'tlvflats.search',
        'tlvflats.property',
        'tlvflats.about'
    ])
    .constant('CONFIG', CONFIG);