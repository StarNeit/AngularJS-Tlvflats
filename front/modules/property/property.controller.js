angular
    .module('tlvflats.property')
    .controller('PropertyController', PropertyController);

PropertyController.$inject = ['$scope', '$window', '$sce', 'property', 'uiGmapGoogleMapApi','anchorSmoothScroll'];
function PropertyController($scope, $window, $sce, property, uiGmapGoogleMapApi, anchorSmoothScroll) {
    var self = this,
        date = moment().format("DD-MM-YY");

    self.showMap = false;
    self.property = property;
    self.frameUrl = $sce.trustAsResourceUrl("https://www.beds24.com/booking2.php?propid=" + property.propid + "&amp;numdisplayed=0&amp;numnight=1&amp;numadult=2-1&amp;referer=iframe&amp;wmode=transparent");

    self.property.long_description = $sce.trustAsHtml(self.property.long_description);

    self.filters = {
        dateFrom : date,
        dateTo : '',
        visitors : '1 guest'
    };

    self.changeDate = (date, datepicker, days) => {
        var new_date = moment(date, "DD-MM-YY");
        new_date.add(days, 'days');
        self.filters[datepicker] = new_date.format('DD-MM-YY');
    };

    self.changeDate(self.filters.dateFrom, 'dateTo', 1);

    uiGmapGoogleMapApi.then(function(maps) {
        setTimeout(function () {
            self.showMap = true;
            $scope.$apply();
        }, 100);

        var geocoder = new google.maps.Geocoder(),
            bounds = new maps.LatLngBounds(),
            latlng;

        var address = self.property.address;
        if (address.indexOf(",") <= -1) {
            self.property.property_places.forEach(function(place){
                address = address + ' ' + place.place.name;
            });
        }

        self.map = {
            zoom: 3,
            events : {
                mouseover: function (marker, event, model) {
                    model.show = true;
                },
                mouseout: function (marker, event, model) {
                    model.show = false;
                }
            },
            fit : true,
            options: {
                scrollwheel: false,
                icon:'/images/marker-sm.png'
            }
        };

        if (!self.property.latitude && !self.property.longitude) {
            geocoder.geocode({ address: address }, function (res, status) {
                self.property.latitude = res[0].geometry.location.lat();
                self.property.longitude = res[0].geometry.location.lng();

                latlng = new maps.LatLng(self.property.latitude, self.property.longitude);
                bounds.extend(latlng);

                angular.extend(self.map, {
                    center: {
                        latitude: bounds.getCenter().lat(),
                        longitude: bounds.getCenter().lng()
                    },
                    bounds : {
                        northeast: {
                            latitude: bounds.getNorthEast().lat(),
                            longitude: bounds.getNorthEast().lng()
                        },
                        southwest: {
                            latitude: bounds.getSouthWest().lat(),
                            longitude: bounds.getSouthWest().lng()
                        }
                    },
                    markers : [self.property]
                });
            });
        } else {
            latlng = new maps.LatLng(self.property.latitude, self.property.longitude);
            bounds.extend(latlng);

            angular.extend(self.map, {
                center: {
                    latitude: bounds.getCenter().lat(),
                    longitude: bounds.getCenter().lng()
                },
                bounds : {
                    northeast: {
                        latitude: bounds.getNorthEast().lat(),
                        longitude: bounds.getNorthEast().lng()
                    },
                    southwest: {
                        latitude: bounds.getSouthWest().lat(),
                        longitude: bounds.getSouthWest().lng()
                    }
                },
                markers : [self.property]
            });
        }
    });

    self.scrollTo = (el_id) => {
        anchorSmoothScroll.scrollTo(el_id, 124);
    };
}