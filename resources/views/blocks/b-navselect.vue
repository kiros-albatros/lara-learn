<template>
    <div class="b-navselect" :class="[hasMultipleLinks ? 'has_dropdown' : '']">
        <Link :href="activeUrl">
            <span>{{ activeLabel }}</span>
            <font-awesome-icon icon="caret-down" />
        </Link>
        <div class="b-navselect-list" v-if="hasMultipleLinks">
            <Link v-for="link in links" :key="link.label" :href="link.url" :class="{ active: link.active }">
                {{ link.label }}
            </Link>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        // В формате [{label: '', url: '', active: ''}]
        links: Object,
        productType: String,
        placeholder: {
            type: String,
            default() {
                return "Перейти к";
            },
        },
    },
    computed: {
        hasMultipleLinks() {
            return this.links.length > 1;
        },
        activeLabel() {
            for (let link of this.links) {
                if (link.active) return link.label;
            }
            return this.placeholder;
        },
        activeUrl() {
            for (let link of this.links) {
                if (link.active) return link.url;
            }
            return this.$page.url;
        },
    },
};
</script>

<style lang="scss">
.b-navselect {
    flex-shrink: 0;
    position: relative;
    border-radius: 6px;
    a {
        display: block;
        text-decoration: none;
        white-space: nowrap;
        svg {
            margin-left: 20px;
        }
        &:hover {
            color: inherit;
        }
    }
    > a {
        display: inline-block;
        font-size: 1.1rem;
        padding: 0 20px;
        vertical-align: top;
    }
    &-list {
        position: absolute;
        overflow: hidden;
        visibility: hidden;
        min-width: 100%;
        opacity: 0;
        border-radius: 0 0 6px 6px;
        & > a {
            padding: 0 20px;
            line-height: 3rem;
            &:hover {
                background-color: var(--primary-darkest);
            }
        }
    }
    &.active,
    &:hover {
        background-color: var(--primary-dark);
        border-radius: 6px 6px 0 0;
        .b-navselect-list {
            visibility: visible;
            opacity: 1;
            background-color: var(--primary-dark);
        }
    }
    &.right {
        margin-left: auto;
    }
}
</style>
