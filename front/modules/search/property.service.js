var $http, $q;

class PropertyService {
    constructor($$http, $$q) {
        $http = $$http;
        $q = $$q;
    }

    getList(filters) {
        return $q(function (resolve, reject) {
            $http
                .get('/api/property/list',{
                    params: filters
                })
                .success(function (result) {
                    resolve(result)
                })
                .error(function (err) {
                    console.error(err);
                    reject(err)
                });
        })
    }

    getOne(id) {
        return $q(function (resolve, reject) {
            $http
                .get(`/api/property/${id}`)
                .success(function (result) {
                    resolve(result)
                })
                .error(function (err) {
                    console.error(err);
                    reject(err)
                });
        })
    }
}

PropertyService.$inject = ['$http', '$q'];

angular
    .module('tlvflats.search')
    .service('PropertyService', PropertyService);