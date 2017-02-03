angular
    .module('tlvflats.search')
    .controller('SearchController', SearchController);

SearchController.$inject = ['$rootScope', 'PropertyService', 'uiGmapGoogleMapApi', 'anchorSmoothScroll', '$timeout', '$q'];
function SearchController($rootScope, PropertyService, uiGmapGoogleMapApi, anchorSmoothScroll, $timeout, $q) {
    var self = this,
        timer;

    self.showMap = false;
    self.loading = true;

    self.search = (place) => {
        PropertyService
            .getList(self.filters)
            .then(function (result) {
                self.list = result;
                return uiGmapGoogleMapApi;
            })
            .then(function (maps) {
                var geocoder = new google.maps.Geocoder(),
                    bounds = new maps.LatLngBounds(),
                    timer;

                var promises = self.list.map(function (apartment) {
                    var defer = $q.defer();

                    if (!apartment.latitude || !apartment.longitude) {
                        var address = apartment.address;
                        if (address.indexOf(",") <= -1) {
                            apartment.property_places.forEach(function (place) {
                                address = address + ' ' + place.place.name;
                            });
                        }

                        geocoder.geocode({address: address}, function (res, status) {
                            if (status == "OK") {
                                apartment.latitude = res[0].geometry.location.lat();
                                apartment.longitude = res[0].geometry.location.lng();
                            }
                            defer.resolve(apartment);
                        })

                    } else defer.resolve(apartment);

                    return defer.promise;
                });

                $q.all(promises).then(function () {
                    for (var i = 0; i < self.list.length; i++) {
                        self.list[i].show = false;
                        var latlng = new maps.LatLng(self.list[i].latitude, self.list[i].longitude);
                        bounds.extend(latlng);
                    }

                    self.map = {
                        center: {
                            latitude: bounds.getCenter().lat(),
                            longitude: bounds.getCenter().lng()
                        },
                        zoom: 7,
                        events: {
                            mouseover: function (marker, event, model) {
                                self.map.window.model = model;
                                self.map.window.show = true;
                                model.hover = true;

                                timer = $timeout(function () {
                                    anchorSmoothScroll.scrollInCont('.property-list', model.id);
                                }, 1500);
                            },
                            mouseout: function (marker, event, model) {
                                $timeout.cancel(timer);
                                self.map.window.show = false;
                                model.hover = false;
                            },
                            click: function (marker, event, model) {
                                anchorSmoothScroll.scrollInCont('.property-list', model.id);
                            }
                        },
                        bounds: {
                            northeast: {
                                latitude: bounds.getNorthEast().lat(),
                                longitude: bounds.getNorthEast().lng()
                            },
                            southwest: {
                                latitude: bounds.getSouthWest().lat(),
                                longitude: bounds.getSouthWest().lng()
                            }
                        },
                        window: {
                            model: {},
                            show: false,
                            options:{
                                pixelOffset: {width:-1,height:-20}
                            }
                        },
                        fit: true,
                        options: {
                            scrollwheel: false,
                            icon:'/images/marker-sm.png'
                        }
                    };
                    self.loading = false;
                    self.showMap = true;
                })
            });
    };
    self.search();

    self.mouseover = (model) => {
        $timeout.cancel(timer);
        model.hover = true;
        timer = $timeout(function () {
            self.map.window.model = model;
            self.map.window.show = true;
        }, 1000);
    };
    self.mouseleave = (model) => {
        self.map.window.show = false;
        model.hover = false;
    };

    $rootScope.$on('search:fake', function(e){
        self.loading = true;
        $timeout(function(){
            self.loading = false;
        }, 1500)
    });

    self.clear = function () {
        self.filters = {};
        // TODO: update search result
    };

    self.scrollTo = (el_id) => {
        anchorSmoothScroll.scrollTo(el_id, 54);
    };
}