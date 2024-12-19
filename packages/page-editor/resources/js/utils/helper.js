import { v4 as uuidv4 } from "uuid";
import map from "lodash/map";
import pick from "lodash/pick";
import get from "lodash/get";
import forEach from "lodash/forEach";
import transform from "lodash/transform";
import cloneDeep from "lodash/cloneDeep";
import find from "lodash/find";
import validation from "@/utils/validation";
import set from "lodash/set";
class PageEditorHelper {
    constructor() {}

    /**
     * for frontend data handling
     */
    async newComponent(config) {
        let test = {
            ...config,
            uuid: uuidv4(),
            fields: map(get(config, "fields"), (field) => ({
                ...field,
                value: this.fieldFormat(null, field),
            })),
            callback: await Promise.all(
                map(get(config, "callback"), async (callback) => ({
                    ...callback,
                    value: await this.getCallbackData(callback, config),
                })),
            ),
        };

        return {
            ...config,
            uuid: uuidv4(),
            fields: map(get(config, "fields"), (field) => ({
                ...field,
                value: this.fieldFormat(null, field),
            })),
            callback: await Promise.all(
                map(get(config, "callback"), async (callback) => ({
                    ...callback,
                    value: await this.getCallbackData(callback, config),
                })),
            ),
        };
    }

    repeatableAdd(config) {
        return this.fieldFormat(null, config);
    }

    nestedFormat(data, config) {
        let temp = {};

        forEach(config, (field) => {
            temp[get(field, "name")] = this.fieldFormat(data, field);
        });

        return temp;
    }

    fieldFormat(data, config) {
        let fieldName = get(config, "name");
        let repeatable = get(config, "repeatable");
        let nested = get(config, "nested");

        if (repeatable) {
            let _data = get(data, fieldName);
            return Array.isArray(_data) ? _data : [];
        } else if (nested) {
            return this.nestedFormat(get(data, fieldName, {}), nested);
        } else {
            let orgValue = get(
                data,
                fieldName,
                get(config, "translatable") ? { en: null } : null,
            );

            return orgValue;
        }
    }

    /**
     * for data submit & preview
     */
    dataFormatting(data, locales, locale = "en", preview = false) {
        return map(data, (orgComponent) => {
            let value = {};
            let component = cloneDeep(orgComponent);

            forEach(get(component, "fields", []), (field) => {
                value[field.name] = this.recursive(
                    field.value,
                    locales,
                    locale,
                    preview,
                );
            });

            if (preview) {
                forEach(get(component, "callback", []), (callback) => {
                    if (callback.name == "props") {
                        value = { ...value, ...get(callback, "value") };
                    } else {
                        value[callback.name] = get(callback, "value");
                    }
                });

                if (get(component, "type") == "share_widget") {
                    component.key = get(component, "component");
                }
            }

            return {
                ...pick(component, ["uuid", "key", "type"]),
                data: value,
            };
        });
    }

    recursive(value, locales, locale = "en", preview = false) {
        //Array
        if (Array.isArray(value)) {
            return map(value, (val) =>
                this.recursive(val, locales, locale, preview),
            );
        }

        // Object
        if (value != null && typeof value === "object") {
            // translatable
            if (
                Object.keys(value).find((key) =>
                    Object.keys(locales).includes(key),
                )
            ) {
                if (preview) {
                    return this.recursive(
                        get(value, locale),
                        locales,
                        locale,
                        preview,
                    );
                }

                return value;
            }

            //image
            if (
                value.hasOwnProperty("original_url") &&
                value.hasOwnProperty("extension")
            ) {
                return preview
                    ? {
                          path: get(value, "original_url"),
                          alt: get(value, "custom_properties.alt"),
                      }
                    : value;
            }

            return transform(
                value,
                (result, value, key) =>
                    (result[key] = this.recursive(
                        value,
                        locales,
                        locale,
                        preview,
                    )),
                {},
            );
        }

        return value;
    }

    /**
     * check data from backend
     */
    checkDataStructure(data, locales) {
        const tempData = cloneDeep(data);

        return map(tempData, (component) => {
            component["fields"] = map(component["fields"], (field) => {
                field["value"] = this.checkDataStructureLoop(field, locales);
                return field;
            });

            if (!get(component, "uuid")) {
                component["uuid"] = uuidv4();
            }

            return component;
        });
    }

    checkDataStructureLoop(config, locales) {
        let value = get(config, "value");

        //nested
        if (get(config, "nested") && !get(config, "repeatable")) {
            //check if value is not an object
            if (
                (typeof value === "object" &&
                    !Array.isArray(value) &&
                    !value) ||
                Array.isArray(value)
            ) {
                return {};
            }

            //check if value is an object & translatable
            if (typeof value === "object" && value) {
                return transform(
                    value,
                    (result, value, key) => {
                        if (
                            get(
                                find(get(config, "nested", []), ["name", key]),
                                "translatable",
                                false,
                            ) &&
                            !value
                        ) {
                            let _temp = cloneDeep(locales);

                            forEach(_temp, (val, key) => {
                                _temp[key] = null;
                            });

                            result[key] = _temp;
                            return;
                        }

                        result[key] = value;
                    },
                    {},
                );
            }
        }

        //repeatable
        if (
            (get(config, "repeatable") && !Array.isArray(value)) ||
            (get(config, "repeatable") && value != [] && !Array.isArray(value))
        ) {
            return [];
        }

        //translatable
        if (
            get(config, "translatable") &&
            (!value ||
                !Object.keys(value).find((key) =>
                    Object.keys(locales).includes(key),
                ))
        ) {
            let _temp = cloneDeep(locales);

            forEach(_temp, (val, key) => {
                _temp[key] = null;
            });

            return _temp;
        }

        //image
        if (
            get(config, "type") == "image" &&
            !get(config, "translatable") &&
            (!value ||
                !(
                    value.hasOwnProperty("original_url") &&
                    value.hasOwnProperty("extension")
                ))
        ) {
            return null;
        }

        return value;
    }

    /**
     * get callback data
     */
    async getCallbackData(config, data) {
        const response = await fetch("/nova-api/page-editors/widget-callback", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                config,
                data,
            }),
        });

        return await response.json();
    }

    /**
     * data validation
     */
    validateData(data) {
        let hasError = false;

        map(data, (field) => {
            this.validateLoop(
                field,
                field.value,
                null,
                [],
                () => (hasError = true),
            );
        });

        return {
            data: data,
            hasError: hasError,
        };
    }

    validateLoop(field, value, index, errors, errorCallback) {
        if (get(field, "repeatable")) {
            return map(get(field, "nested", []), (subField) => {
                forEach(value, (val, index) => {
                    this.validateLoop(
                        subField,
                        get(val, subField.name),
                        index,
                        errors,
                        errorCallback,
                    );
                });
            });
        }

        if (get(field, "nested")) {
            return map(get(field, "nested", []), (subField) =>
                this.validateLoop(
                    subField,
                    get(value, subField.name),
                    index,
                    [],
                    errorCallback,
                ),
            );
        }

        if (!field.validate) {
            return field;
        }

        forEach(field.validate.split("|"), (rule) => {
            let errorMsg = validation.valid(rule, value);
            if (errorMsg) {
                if (index != null) {
                    if (errors[index] == undefined) {
                        set(errors, index, []);
                    }
                    errors[index].push(errorMsg);
                } else {
                    errors.push(errorMsg);
                }
            }
        });

        if (errors.length > 0) {
            field["errors"] = errors;
            errorCallback();
        } else {
            field["errors"] = null;
        }

        return field;
    }
}

export default new PageEditorHelper();
