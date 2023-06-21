<template>
    <Head title="Пользователи" />
    <div class="g-titlebar">
        <h1>Пользователи</h1>
        <Link href="/users/create/" class="g-button outlined"> Создать пользователя </Link>
    </div>
    <b-filter
        v-model="filter"
        :fields="{
            role: {
                type: 'radio',
                options: {
                    '': 'Все',
                    buyer: 'Покупатели',
                    seller: 'Продавцы',
                    freelancer: 'Фрилансеры',
                    staff: 'Сотрудники',
                    admin: 'Админы',
                },
            },
            q: {
                type: 'text',
                placeholder: 'Поиск по email, имени или телефону',
            },
        }"
    />
    <el-table :data="users.data" table-layout="auto" :class="{ loading: filter.loading }">
        <el-table-column label="Имя">
            <template #default="{ row }">
                <Link :href="`/users/${row.id}/`">{{ row.name }}</Link>
                <el-tag type="danger" v-if="row.is_blocked"> Заблокирован </el-tag>
            </template>
        </el-table-column>
        <el-table-column label="Роль" prop="role_title" />
        <el-table-column label="Email" prop="email" />
        <el-table-column label="Телефон" prop="phone" />
        <el-table-column label="Зарегистрирован">
            <template #default="{ row }">
                {{ (row.created_at || "").split("T")[0] }}
            </template>
        </el-table-column>
        <el-table-column align="right">
            <template #default="{ row }">
                <a
                    @click="deleteUser(row.id, row.name)"
                    title="Удалить"
                    class="g-actionicon"
                    v-if="row.role !== 'admin'"
                >
                    <font-awesome-icon icon="trash" />
                </a>
                <Link :href="`/users/${row.id}/edit/`" class="g-actionicon">
                    <font-awesome-icon icon="pen-to-square" />
                </Link>
            </template>
        </el-table-column>
    </el-table>
    <b-pagination :links="users.links" />
</template>

<script>
import { router } from "@inertiajs/vue3";

export default {
    props: {
        users: Object,
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
                role: this.initialFilter?.role || "",
                q: this.initialFilter?.q || "",
                loading: false,
            },
        };
    },
    methods: {
        deleteUser(id, name) {
            if (!confirm(`Вы действительно хотите удалить пользователя «${name}»?`)) return;
            router.delete(`/users/${id}`);
        },
    },
};
</script>
