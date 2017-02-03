angular
    .module('tlvflats.auth')
    .provider('Authentication', AuthenticationProvider);

function AuthenticationProvider() {
    this.$get = $get;

    $get.$inject = ['$q', 'Storage', '$auth', '$http'];
    function $get($q, Storage, $auth, $http) {
        class Authentication {
            constructor() {
                $auth.provider = this;
                this.user = null;
            }

            initialize() {
            }

            setUser(user) {
                if (!this.user) {
                    this.user = user;
                } else {
                    this.user = angular.extend(this.user, user);
                }
                this.user.version = Date.now();
                Storage.set('user', this.user);
            }

            update(data) {
            }

            clearUser() {
                this.user = null;
                Storage.remove('user');
            }

            isAuthenticated() {
                return (!! this.user);
            }

            logout() {
            }
        }

        return new Authentication();
    }
}