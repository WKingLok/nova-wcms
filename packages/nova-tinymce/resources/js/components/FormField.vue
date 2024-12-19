<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <textarea
                v-model="value"
                :id="uuid"
                class="form-control form-input form-input-bordered w-full"
                :placeholder="field.name"
            ></textarea>
        </template>
    </DefaultField>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import { v4 as uuidv4 } from "uuid";

import { FormField, HandlesValidationErrors } from "laravel-nova";
import tinyMCE from "tinymce/tinymce";
require("tinymce/icons/default");
import "tinymce/themes/silver/theme.min.js";
import "tinymce/skins/ui/oxide/skin.min.css";
require("tinymce/plugins/advlist");
require("tinymce/plugins/anchor");
require("tinymce/plugins/autolink");
require("tinymce/plugins/autoresize");
require("tinymce/plugins/autosave");
require("tinymce/plugins/bbcode");
require("tinymce/plugins/charmap");
require("tinymce/plugins/code");
require("tinymce/plugins/codesample");
require("tinymce/plugins/colorpicker");
require("tinymce/plugins/contextmenu");
require("tinymce/plugins/directionality");
require("tinymce/plugins/emoticons");
require("tinymce/plugins/fullpage");
require("tinymce/plugins/fullscreen");
require("tinymce/plugins/help");
require("tinymce/plugins/hr");
require("tinymce/plugins/image");
require("tinymce/plugins/imagetools");
require("tinymce/plugins/importcss");
require("tinymce/plugins/insertdatetime");
require("tinymce/plugins/legacyoutput");
require("tinymce/plugins/link");
require("tinymce/plugins/lists");
require("tinymce/plugins/media");
require("tinymce/plugins/nonbreaking");
require("tinymce/plugins/noneditable");
require("tinymce/plugins/pagebreak");
require("tinymce/plugins/paste");
require("tinymce/plugins/preview");
require("tinymce/plugins/print");
require("tinymce/plugins/quickbars");
require("tinymce/plugins/save");
require("tinymce/plugins/searchreplace");
require("tinymce/plugins/spellchecker");
require("tinymce/plugins/tabfocus");
require("tinymce/plugins/table");
require("tinymce/plugins/template");
require("tinymce/plugins/textcolor");
require("tinymce/plugins/textpattern");
require("tinymce/plugins/toc");
require("tinymce/plugins/visualblocks");
require("tinymce/plugins/visualchars");
require("tinymce/plugins/wordcount");
export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ["resourceName", "resourceId", "field"],
    emits: ["input"],
    setup(props, { emit }) {
        const uuid = uuidv4();

        const value = ref(props.field.value);

        const init = () => {
            tinyMCE.init({
                selector: `textarea#${uuid}`,
                height: "32rem",
                path_absolute: "/",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak code media template paste table",
                ],
                image_advtab: true,
                image_dimensions: false,
                image_class_list: [{ title: "Responsive", value: "img-fluid" }],
                toolbar:
                    "fontsizeselect insertfile undo redo | styleselect | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image media template",
                fontsize_formats:
                    "14px 16px 18px 24px 36px 38px 40px 42px 44px 46px 48px",
                use_lfm: true,
                relative_urls: true,
                convert_urls: false,
                remove_script_host: false,
                paste_as_text: false,
                content_css: ["/css/ui.css"],
                file_picker_callback: filePickerCallback,
                init_instance_callback: initInstanceCallback,
                templates: this.field.templates || [],
            });
        };

        const filePickerCallback = (callback, value, meta) => {
            let x =
                window.innerWidth ||
                document.documentElement.clientWidth ||
                document.getElementsByTagName("body")[0].clientWidth;
            let y =
                window.innerHeight ||
                document.documentElement.clientHeight ||
                document.getElementsByTagName("body")[0].clientHeight;

            tinyMCE.activeEditor.windowManager.openUrl({
                url: "/file-manager/tinymce5",
                title: "File manager",
                width: x * 0.8,
                height: y * 0.8,
                onMessage: (api, message) => {
                    callback(message.content, { text: message.text });
                },
            });
        };

        const initInstanceCallback = (editor) => {
            editor
                .on("input", function (e) {
                    value.value = tinyMCE.activeEditor.getContent();
                    emit("input", tinyMCE.activeEditor.getContent());
                })
                .on("change", function (e) {
                    value.value = tinyMCE.activeEditor.getContent();
                    emit("input", tinyMCE.activeEditor.getContent());
                });
        };

        onMounted(() => {
            init();
        });

        return {
            uuid,
            value,
        };
    },
    methods: {
        fill(formData) {
            formData.append(this.field.attribute, this.value || "");
        },
    },
};
</script>
