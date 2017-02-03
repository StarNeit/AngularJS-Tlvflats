angular
    .module('tlvflats.navbar')
    .directive('sidenav', SidenavDirective);

function SidenavDirective() {
    return {
        restrict : 'E',
        templateUrl : 'navbar/sidenav.directive.html',
        controller : SidenavDirectiveController,
        controllerAs : 'self'
    }
}

SidenavDirectiveController.$inject = ['$scope', '$rootScope', '$mdMedia'];

function SidenavDirectiveController($scope, $rootScope, $mdMedia) {
    var self = this,
        date = moment().format("DD-MM-YY");

    self.$mdMedia = $mdMedia;

    self.filters = {
        dateFrom : date,
        dateTo : '',
        visitors : '1 guest'
    };

    self.dateOptions = {
        formatYear: 'yyyy',
        startingDay: 1
    };

    self.search = () => {
        $rootScope.$broadcast('search:fake')
    };

    self.changeDate = (date, datepicker, days) => {
        var new_date = moment(date, "DD-MM-YY");
        new_date.add(days, 'days');
        self.filters[datepicker] = new_date.format('DD-MM-YY');
    };
    self.changeDate(self.filters.dateFrom, 'dateTo', 1);

    self.themes = [{
        title : 'indigo',
        primary : '#3F51B5',
        accent : '#4CAF50'
    }, {
        title : 'lime',
        primary : '#CDDC39',
        accent : '#FF9800'
    }, {
        title : 'red',
        primary : '#C2185B',
        accent : '#E040FB'
    }, {
        title : 'yellow',
        primary : '#F57C00',
        accent : '#FFEB3B'
    }];

    self.changeTheme = function(theme) {
        $rootScope.theme = theme;
    };
}