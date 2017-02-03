angular
    .module('tlvflats.base')
    .filter('expandState', expandState);

function expandState() {
    return function(input) {
        let parts = input.split('.'),
            output = '',
            prefix = '';
        for (let i = 0; i < parts.length; i++) {
            output += prefix+parts[i]+' ';
            prefix += parts[i]+'-'
        }
        return output;
    }
}