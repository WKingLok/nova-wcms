import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-media-collection', IndexField)
  app.component('detail-media-collection', DetailField)
  app.component('form-media-collection', FormField)
})
