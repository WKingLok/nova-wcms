import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-media-files', IndexField)
  app.component('detail-media-files', DetailField)
  app.component('form-media-files', FormField)
})
