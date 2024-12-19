<template>
    <div class="text-right">
        <ButtonInertiaLink
            :href="field.editorURL"
            class="toolbar-button"
            @click.stop
        >
            Editor
        </ButtonInertiaLink>
    </div>
</template>

<script>
export default {
    props: ["resourceName", "field"],
};
</script>
