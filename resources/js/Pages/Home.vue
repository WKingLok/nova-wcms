<template>
    <div class="text-center">Test SSR</div>
    <div>{{ $t("Hello") }}</div>
    <div>{{ side }}</div>
</template>
<script>
import { ref } from "vue";
import ssr from "@helper/ssr";

export default {
    props: {
        components: String,
    },
    setup(props) {
        const { clientOnly } = ssr();
        const side = ref("server side");

        // if client side
        if (clientOnly()) {
            side.value = "client side";
        }
        return { side };
    },
};
</script>
