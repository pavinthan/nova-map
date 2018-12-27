Nova.booting((Vue, router) => {
    Vue.component('index-nova-map', require('./components/IndexField'));
    Vue.component('detail-nova-map', require('./components/DetailField'));
    Vue.component('form-nova-map', require('./components/FormField'));
})
