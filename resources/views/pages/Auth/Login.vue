<template>
    <div class="b-login">
        <b-logo />
        <form class="b-form label_top" :class="{ loading: form.processing }" @submit.prevent="submit">
            <b-formrow title="Email" :error="errors.email">
                <el-input type="email" v-model="form.email" />
            </b-formrow>
            <b-formrow title="Пароль" :error="errors.password">
                <el-input type="password" v-model="form.password" />
            </b-formrow>
            <b-formrow>
                <button class="g-button">Войти</button>
            </b-formrow>
        </form>
        <p>
            Если у Вас ещё нет учетной записи партнера, Вы можете отправить заявку на подключение
            <a href="https://vsekolesa.ru/partner/">здесь</a>
            .
        </p>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";

export default {
    layout: false,
    props: {
        errors: {
            type: Object,
            default() {
                return {};
            },
        },
    },
    data() {
        return {
            form: useForm({
                email: "",
                password: "",
            }),
        };
    },
    methods: {
        submit() {
            this.form.post("/login");
        },
    },
};
</script>

<style lang="scss">
.b-login {
    display: flex;
    flex-direction: column;
    max-width: 400px;
    margin: auto;

    & > .b-logo {
        margin: var(--base-margin) auto;
    }
}
</style>
