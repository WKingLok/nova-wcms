import get from "lodash/get";

class Validation {
    constructor() {}

    valid(rule, value) {
        switch (rule) {
            case "required":
                return this.required(value);
            default:
                break;
        }
    }

    required(value) {
        if (
            (typeof value != "object" && value) ||
            (typeof value == "object" && get(value, "en")) ||
            (typeof value == "object" &&
                get(value, "extension") &&
                get(value, "size"))
        ) {
            return false;
        }

        return "This field is required.";
    }
}

export default new Validation();
