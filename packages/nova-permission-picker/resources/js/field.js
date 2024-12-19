import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-permission-picker', IndexField)
  app.component('detail-permission-picker', DetailField)
  app.component('form-permission-picker', FormField)
})
