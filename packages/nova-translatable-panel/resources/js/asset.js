import Form from "./components/Form";
import Detail from "./components/Detail";

Nova.booting((app) => {
  app.component("form-translatable-panel", Form);
  app.component("detail-translatable-panel", Detail);
});
