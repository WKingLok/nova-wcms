<template>
    <VueFinalModal
        class="pe-flex pe-items-center pe-justify-center"
        :modal-id="modalId"
        :z-index-fn="() => 2000"
        :escToClose="false"
        contentClass="pe-absolute pe-inset-0"
    >
        <div
            class="pe-absolute pe-inset-0 pe-flex pe-h-full pe-overflow-auto pe-py-12"
            @click.self="closeModal"
        >
            <div
                class="pe-m-auto pe-w-[1000px] pe-max-w-[80vw] pe-rounded-lg pe-bg-gray-100 pe-p-8 pe-shadow-lg dark:pe-bg-gray-800"
            >
                <slot />
            </div>
        </div>
    </VueFinalModal>
</template>

<script setup>
import { defineProps } from "vue";
import { VueFinalModal, useVfm } from "vue-final-modal";

const props = defineProps({
    modalId: String,
    clickToClose: {
        type: Boolean,
        default: true,
    },
});

const vfm = useVfm();

const closeModal = () => {
    if (!props.clickToClose) {
        return;
    }
    vfm.close(props.modalId);
};
</script>
