<template>
    <Modal :show="show" role="alertdialog" size="sm">
        <form
            @submit.prevent="handleConfirm"
            class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
        >
            <slot>
                <ModalHeader v-text="`Delete Widget`" />
                <ModalContent>
                    <p class="leading-normal">
                        {{
                            __(
                                "Are you sure you want to delete the selected widget?",
                            )
                        }}
                    </p>
                </ModalContent>
            </slot>

            <ModalFooter>
                <div class="ml-auto">
                    <LinkButton
                        type="button"
                        dusk="cancel-delete-button"
                        @click.prevent="handleClose"
                        class="mr-3"
                    >
                        {{ __("Cancel") }}
                    </LinkButton>

                    <NovaUiButton
                        type="submit"
                        ref="confirmButton"
                        dusk="confirm-delete-button"
                        :loading="working"
                        state="danger"
                        label="Delete"
                    />
                </div>
            </ModalFooter>
        </form>
    </Modal>
</template>

<script setup>
import { watch, defineEmits, defineProps, ref } from "vue";

const props = defineProps({
    show: Boolean,
    previewConfig: Object,
});

const emit = defineEmits(["confirm", "close"]);

const working = ref(false);

const handleClose = () => {
    emit("close");
    working.value = false;
};

const handleConfirm = () => {
    emit("confirm");
    working.value = true;
};

watch(
    () => props.show,
    (showing) => {
        if (showing === false) {
            working.value = false;
        }
    },
);
</script>
