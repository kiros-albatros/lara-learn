<template>
    <Head>
        <title>Редактирование магазина</title>
    </Head>
    <div class="g-titlebar">
        <h1>Редактирование магазина</h1>
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
            <button class="g-button">Сохранить изменения</button>
        </b-formrow>
    </form>
</template>

<script>
import { router } from "@inertiajs/vue3";

export default {
    props: {
        id: Number,
        values: Object,
        errors: Object,
    },
    data() {
        return {
            form: { ...this.values },
        };
    },
    methods: {
        submit() {
            router.put(`/shops/${this.id}`, this.form);
        },
    },
};
</script>

<style scoped></style>
