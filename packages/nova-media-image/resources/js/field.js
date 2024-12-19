import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-media-image', IndexField)
  app.component('detail-media-image', DetailField)
  app.component('form-media-image', FormField)
})
