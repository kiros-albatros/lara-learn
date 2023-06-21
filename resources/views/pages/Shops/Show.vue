<template>
    <Head :title="shop.title" />
    <div class="g-row">
        <h1>{{ shop.title }}</h1>
        <Link :href="`/shops/${shop.id}/edit/`" class="g-button outlined"> Редактировать </Link>
        <Link href="/shops/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться к списку пользователей
        </Link>
    </div>
    <div class="g-row">
        <div class="size_6">
            <el-descriptions title="Личные данные" column="1">
                <el-descriptions-item label="Название магазина">
                    {{ shop.title }}
                </el-descriptions-item>
                <el-descriptions-item label="URL">
                    {{ shop.url }}
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
        shop: Object,
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
