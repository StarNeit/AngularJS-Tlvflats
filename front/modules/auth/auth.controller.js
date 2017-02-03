angular
    .module('tlvflats.auth')
    .controller('LoginController', LoginController);

LoginController.$inject = ['$auth', 'toastr', '$state'];

function LoginController($auth, toastr, $state) {
    var self = this;
    this.Authentication = $auth;

    this.signUp = () => {
        $auth.signup(self.user)
            .then(function(result) {
                var user = result.data.user;

                self.Authentication.provider.setUser(user);
                toastr.success(`Welcome, ${user.first_name}`, 'Success');
                $state.go('main')
            })
            .catch(function(error) {
                toastr.error(error.data.message);
            });
    };

    this.login = () => {
        $auth.login(self.user)
            .then(function(result) {
                var user = result.data.user;

                self.Authentication.provider.setUser(user);
                toastr.success(`Welcome, ${user.first_name}`, 'Success');

                mixpanel.identify(user._id);

                $state.go('main')
            })
            .catch(function(error) {
                toastr.error(error.data.message);
            });
    };

    this.authenticate = (provider) => {
        $auth.authenticate(provider)
            .then(function(result) {
                console.log(result);
            })
            .catch(function(err) {
                toastr.error(err, 'Error');
            });
    };
}