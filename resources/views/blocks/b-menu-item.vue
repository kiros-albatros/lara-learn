<template>
    <span v-html="message()"></span>
    <font-awesome-icon icon="fa-triangle-exclamation" class="color_gold" v-if="changedIconOn && is_changed" />
</template>

<script>
import { compile } from "vue/dist/vue.esm-bundler.js";

export default {
    props: {
        template: String,
    },
    data() {
        return {
            changedIconOn: false,
        };
    },
    methods: {
        message() {
            return compile(this.template)(this.$.ctx);
        },
    },
};
</script>

<style>
.color_gold {
    color: gold;
}
</style>
