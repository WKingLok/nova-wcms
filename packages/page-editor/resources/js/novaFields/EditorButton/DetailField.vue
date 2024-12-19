<template>
    <PanelItem :index="index" :field="field">
        <template v-slot:value>
            <ButtonInertiaLink
                :href="field.editorURL"
                class="toolbar-button"
                @click.stop
            >
                Editor
            </ButtonInertiaLink>
        </template>
    </PanelItem>
</template>

<script>
export default {
    props: ["resourceName", "field", "index"],
    setup(props) {
        props.field.name = "Editor";
    },
};
</script>
