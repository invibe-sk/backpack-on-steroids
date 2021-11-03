window.Vue = require('vue').default;

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('files', require('./components/Files').default);

let elements = document.getElementsByClassName('vue');

for (let el of elements) {

    new Vue({
        el,
    });

}
