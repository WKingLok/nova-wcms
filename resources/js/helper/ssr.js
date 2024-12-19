export default function () {
  function clientOnly() {
    return typeof window !== "undefined";
  }
  return {
    clientOnly,
  };
}
