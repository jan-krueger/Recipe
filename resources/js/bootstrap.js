window._ = require('lodash');
window.Vue = require('vue');
window.M = require('materialize-css/dist/js/materialize');
window.noUiSlider = require('materialize-css/extras/noUiSlider/nouislider');
window.$ = require('jquery');


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

Vue.use(require('vue-cookies'));

Vue.component('recipe-component', require('./components/RecipeComponent').default);
Vue.component('experiment-component', require('./components/ExperimentComponent').default);

Vue.$cookies.config('7d');


const app = new Vue({
    el: '#app'
});

