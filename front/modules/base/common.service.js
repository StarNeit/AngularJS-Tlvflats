var $http, $q;

class CommonService {
    constructor($$http, $$q) {
        $http = $$http;
        $q = $$q;
    }

    geocode(address) {
        return $q(function (resolve, reject) {
            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({ address : address }, function (res, status) {
                if (status == "OK") {
                    resolve({
                        latitude : res[0].geometry.location.lat(),
                        longitude : res[0].geometry.location.lng()
                    })
                } else {
                    reject();
                }
            });
        });
    }

}

CommonService.$inject = ['$http', '$q'];

angular
    .module('tlvflats.base')
    .service('CommonService', CommonService);