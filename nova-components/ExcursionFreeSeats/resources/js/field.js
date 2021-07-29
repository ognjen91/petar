Nova.booting((Vue, router, store) => {
  Vue.component('index-excursion-free-seats', require('./components/IndexField'))
  Vue.component('detail-excursion-free-seats', require('./components/DetailField'))
  Vue.component('form-excursion-free-seats', require('./components/FormField'))
})
