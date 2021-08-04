Nova.booting((Vue, router, store) => {
  Vue.component('index-excursion-recapitulation', require('./components/IndexField'))
  Vue.component('detail-excursion-recapitulation', require('./components/DetailField'))
  Vue.component('form-excursion-recapitulation', require('./components/FormField'))
})
