<template>
    <Head>
        <title>Создание пользователя</title>
    </Head>
    <div class="g-titlebar">
        <h1>Создание пользователя</h1>
        <Link href="/users/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться к списку пользователей
        </Link>
    </div>
    <form class="b-form" :class="{ loading: form.processing }" @submit.prevent="submit">
        <b-formrow title="Имя" class="type_text" :error="errors.name">
            <el-input type="text" v-model="form.name" />
        </b-formrow>
        <b-formrow title="Email" class="type_text" :error="errors.email">
            <el-input type="text" v-model="form.email" />
        </b-formrow>
        <b-formrow title="Пароль" class="type_text" :error="errors.password">
            <el-input type="password" v-model="form.password" />
        </b-formrow>
        <b-formrow title="Телефон" class="type_text" :error="errors.phone">
            <el-input type="text" v-model="form.phone" />
        </b-formrow>
        <b-formrow title="Роль" class="type_text" :error="errors.role">
            <el-radio-group v-model="form.role">
                <el-radio-button v-for="(oTitle, oValue) in userRoles" :key="oValue" :label="oValue">
                    {{ oTitle }}
                </el-radio-button>
            </el-radio-group>
        </b-formrow>
        <b-formrow
            title="Подпись"
            hint="Будет подставляться в письмах, отправляемых автоматически"
            class="type_textarea"
            :error="errors.signature"
            v-if="teamRoles.includes(form.role)"
        >
            <el-input type="textarea" v-model="form.signature" />
        </b-formrow>
        <b-formrow
            title="День рождения"
            class="type_date"
            :error="errors.birthday"
            v-if="teamRoles.includes(form.role)"
        >
            <el-date-picker v-model="form.birthday" type="date" />
        </b-formrow>
        <b-formrow>
            <button class="g-button">Создать пользователя</button>
        </b-formrow>
    </form>
</template>

<script>
import { router } from "@inertiajs/vue3";

export default {
    props: {
        userRoles: Object,
        teamRoles: Array,
        errors: Object,
    },
    data() {
        return {
            form: {},
        };
    },
    methods: {
        submit() {
            router.post("/users", this.form);
        },
    },
};
</script>

<style scoped></style>
