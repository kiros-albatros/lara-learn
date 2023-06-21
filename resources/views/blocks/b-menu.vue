<template>
    <div class="b-menu">
        <Link v-for="link in links" :key="link.label" :href="link.url" :class="{ active: link.active }">
            <subMenuItem :template="link.label" />
        </Link>
    </div>
</template>

<script>
import subMenuItem from "./b-menu-item.vue";

export default {
    components: { subMenuItem },
    props: {
        // В формате [{label: '', url: '', active: ''}]
        links: Object,
    },
};
</script>

<style lang="scss">
.b-menu {
    display: flex;
    align-items: center;
    justify-content: start;
    background-color: var(--primary);
    a {
        color: var(--white);
        padding: 0 1.5rem;
        border-radius: 6px 6px 0 0;
        &:hover {
            background-color: var(--primary-dark);
            color: var(--white);
        }
        &.active {
            background-color: var(--white);
            color: var(--black);
            &:hover {
                background-color: var(--primary-lightest);
                color: var(--black);
            }
        }
    }
}
</style>
