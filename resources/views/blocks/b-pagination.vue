<template>
    <div class="b-pagination" v-if="links.length">
        <Component
            v-for="link in links"
            :key="link.url"
            :class="{ active: link.active }"
            :is="link.url && !link.active ? 'Link' : 'span'"
            :href="link.url ? link.url.replace(/[\?\&]page\=1/, '') : ''"
        >
            {{ link.label }}
        </Component>
    </div>
</template>

<script>
export default {
    props: {
        links: Object,
    },
};
</script>

<style lang="scss">
/* Постраничная навигация */
.b-pagination {
    display: flex;
    line-height: 2.5rem;
    & > span,
    & > a {
        padding: 0.2rem 1.2rem;
        text-align: center;
        &.active {
            background-color: var(--primary);
            color: var(--white);
        }
    }
    & > a {
        &:hover {
            background-color: var(--bg);
        }
    }
}
</style>
