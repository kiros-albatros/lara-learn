<template>
    <Head title="Магазины" />
    <div class="g-titlebar">
        <h1>Магазины</h1>
        <Link href="/shops/create/" class="g-button outlined"> Создать магазин </Link>
    </div>
    <b-filter
        v-model="filter"
        :fields="{
            q: {
                type: 'text',
                placeholder: 'Поиск по названию',
            },
        }"
    />

    <el-table :data="shops.data" table-layout="auto" class="{ loading: filter.loading }">
        <el-table-column label="Название">
            <template #default="{ row }">
                <Link :href="`/shops/${row.id}/`">{{ row.title }}</Link>
            </template>
        </el-table-column>
        <el-table-column label="URL" prop="url" />
        <el-table-column label="Зарегистрирован">
            <template #default="{ row }">
                {{ (row.created_at || "").split("T")[0] }}
            </template>
        </el-table-column>
        <el-table-column align="right">
            <template #default="{ row }">
                <a
                    @click="deleteShop(row.id, row.title)"
                    title="Удалить"
                    class="g-actionicon"
                    v-if="row.role !== 'admin'"
                >
                    <font-awesome-icon icon="trash" />
                </a>
                <Link :href="`/shops/${row.id}/edit/`" class="g-actionicon">
                    <font-awesome-icon icon="pen-to-square" />
                </Link>
            </template>
        </el-table-column>
    </el-table>
    <b-pagination :links="shops.links" />
</template>

<script>
import { router } from "@inertiajs/vue3";

export default {
    props: {
        shops: Object,
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
                q: this.initialFilter?.q || "",
                loading: false,
            },
        };
    },
    methods: {
        deleteShop(id, name) {
            if (!confirm(`Вы действительно хотите удалить магазин «${name}»?`)) return;
            router.delete(`/shops/${id}`);
        },
    },
};
</script>
