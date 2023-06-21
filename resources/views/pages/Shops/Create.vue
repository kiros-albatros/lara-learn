<template>
    <Head>
        <title>Создание магазина</title>
    </Head>
    <div class="g-titlebar">
        <h1>Создание магазина</h1>
        <Link href="/shops/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться к списку магазинов
        </Link>
    </div>
    <form class="b-form" :class="{ loading: form.processing }" @submit.prevent="submit">
        <b-formrow title="Название магазина" class="type_text" :error="errors.title">
            <el-input type="text" v-model="form.title" />
        </b-formrow>
        <b-formrow title="URL" class="type_text" :error="errors.url">
            <el-input type="text" v-model="form.url" />
        </b-formrow>
        <b-formrow>
            <button class="g-button">Создать магазин</button>
        </b-formrow>
    </form>
</template>

<script>
import { router } from "@inertiajs/vue3";

export default {
    props: {
        errors: Object,
    },
    data() {
        return {
            form: {},
        };
    },
    methods: {
        submit() {
            router.post("/shops", this.form);
        },
    },
};
</script>

<style scoped></style>
