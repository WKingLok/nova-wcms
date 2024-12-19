import DetailField from "./components/DetailField";
import FormField from "./components/FormField";

Nova.booting((app, store) => {
  app.component("detail-tinymce", DetailField);
  app.component("form-tinymce", FormField);
});
