<template>
    <draggable
        :list="items"
        :group="{ name: 'g1' }"
        item-key="uuid"
        :move="checkCanMove"
        :class="{
            'has-drag-area': level <= maxLevel,
        }"
    >
        <template #item="{ element }">
            <div>
                <div
                    class="hm-mb-4 hm-flex hm-items-center hm-justify-between hm-rounded-lg hm-bg-white hm-px-4 hm-py-3 hm-shadow-lg dark:hm-bg-gray-800"
                >
                    <div class="hm-mr-3 hm-font-bold">
                        {{ element.label }}
                    </div>
                    <Icon class="hm-h-5 hm-w-5" type="switch-vertical" />
                </div>

                <HeaderMenuDraggable
                    :data-level="level + 1"
                    class="hm-ml-8"
                    :items="element.subMenu"
                    :level="level + 1"
                    :maxLevel="maxLevel"
                />
            </div>
        </template>
    </draggable>
</template>
<script setup>
import draggable from "vuedraggable";
import remove from "lodash/remove";
import get from "lodash/get";

const props = defineProps({
    items: Array,
    level: Number,
    maxLevel: Number,
});

const checkCanMove = (evt, originalEvent) => {
    if (evt.to.dataset.level > props.maxLevel) {
        return false;
    }

    if (
        evt.to.dataset.level == props.maxLevel &&
        get(evt.draggedContext, "element.subMenu", []).length != 0
    ) {
        return false;
    }

    return true;
};
</script>

<style scoped>
.has-drag-area {
    padding-bottom: 56px;
    position: relative;
}

.has-drag-area::after {
    position: absolute;
    content: "Drag in here.";
    text-align: center;
    width: 100%;
    bottom: 8px;
    border-radius: 5px;
    background-color: #e5e7eb;
    padding: 14px 0px;
}
</style>
