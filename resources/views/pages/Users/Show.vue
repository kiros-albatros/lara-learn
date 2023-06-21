<template>
    <Head :title="user.name" />
    <div class="g-row">
        <h1>{{ user.name }}</h1>
        <Link :href="`/users/${user.id}/edit/`" class="g-button outlined"> Редактировать </Link>
        <Link href="/users/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться к списку пользователей
        </Link>
    </div>
    <div class="g-row">
        <div class="size_6">
            <el-descriptions title="Личные данные" column="1">
                <el-descriptions-item label="Имя">
                    {{ user.name }}
                </el-descriptions-item>
                <el-descriptions-item label="Email">
                    {{ user.email }}
                </el-descriptions-item>
                <el-descriptions-item label="Телефон" v-if="user.phone">
                    {{ user.phone }}
                </el-descriptions-item>
                <el-descriptions-item label="Роль">
                    {{ user.role_title }}
                </el-descriptions-item>
                <el-descriptions-item label="Статус" v-if="user.is_blocked">
                    <el-tag type="danger">Заблокирован</el-tag>
                </el-descriptions-item>
                <el-descriptions-item label="День рождения" v-if="user.birthday">
                    {{ user.birthday }}
                </el-descriptions-item>
                <el-descriptions-item label="Дата регистрации">
                    {{ (user.created_at || "").split("T")[0] }}
                </el-descriptions-item>
                <el-descriptions-item label="Подпись в письмах" v-if="user.signature">
                    {{ user.signature }}
                </el-descriptions-item>
            </el-descriptions>
        </div>
        <div class="size_6">
            <div class="g-row middle">
                <h2>История</h2>
                <b-filter
                    v-model="filter"
                    :fields="{
                        daterange: {
                            type: 'daterange',
                        },
                    }"
                />
            </div>
            <el-timeline :class="{ loading: filter.loading }">
                <el-timeline-item timestamp="2022-08-16 07:51" placement="top">
                    <a>/shipping/</a>
                    : редактирование h1
                    <font-awesome-icon icon="code-compare" />
                </el-timeline-item>
                <el-timeline-item timestamp="2022-08-16 07:51" placement="top">
                    <a>/shiny/{season}/r{diameter}/</a>
                    : редактирование h1, title
                    <font-awesome-icon icon="code-compare" />
                </el-timeline-item>
            </el-timeline>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: Object,
        initialFilter: {
            type: Object,
            default() {
                return {};
            },
        },
    },
    data() {
        return {
            filter: {
                daterange: this.initialFilter?.daterange || "",
                loading: false,
            },
        };
    },
};
</script>

<style scoped></style>
