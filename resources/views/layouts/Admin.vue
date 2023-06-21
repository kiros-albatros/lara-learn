<template>
    <div class="b-canvas">
        <header class="b-header">
            <b-logo type="white" />
            <b-navselect :links="navLinks" />
            <b-navselect
                :placeholder="switcherLinksPlaceholder"
                :links="switcherLinks"
                v-if="switcherLinks"
                :product-type="productType"
            />
            <div class="b-navselect has_dropdown right">
                <Link :href="`/users/${user.id}/`">
                    <span>{{ user.name }}</span>
                    <font-awesome-icon icon="caret-down" />
                </Link>
                <div class="b-navselect-list">
                    <Link :href="`/users/${user.id}/`">Аккаунт</Link>
                    <Link @click="logout">Выйти</Link>
                </div>
            </div>
        </header>
        <div class="b-subheader" v-if="menuItems">
            <b-menu :links="menuItems" />
        </div>
        <div class="b-main">
            <section class="b-section">
                <div class="b-section-h">
                    <aside class="b-sidebar"></aside>
                    <div class="b-content">
                        <slot />
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="b-footer">
        <div class="b-footer-h">
            <span>&copy; ВсеКолёса, 2023</span>
            <a href="/legal/terms/" rel="nofollow" target="_blank"> Условия использования </a>
            <a href="/legal/privacy/" rel="nofollow" target="_blank"> Приватность </a>
            <a href="/contacts/" rel="nofollow" target="_blank"> Обратная связь </a>
        </div>
    </div>
</template>

<script>
import { router } from "@inertiajs/vue3";
import BLogo from "@/views/blocks/b-logo.vue";
import BNavselect from "@/views/blocks/b-navselect.vue";

export default {
    components: { BNavselect, BLogo },
    props: {
        navLinks: {
            type: [Object, null],
            default: null,
        },
        switcherLinks: {
            type: [Array, null],
            default: null,
        },
        switcherLinksPlaceholder: {
            type: String,
            default: "Перейти к",
        },
        productType: {
            type: [String, null],
            default: null,
        },
    },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        menuItems() {
            for (let link of this.navLinks) {
                if (link.active && link.children) return link.children;
            }
            return null;
        },
    },
    methods: {
        logout() {
            router.post("/logout/");
        },
    },
};
</script>

<style lang="scss">
.b-body {
    min-height: 100%;
}
.b-body #app {
    display: flex;
    flex-direction: column;
    min-height: 100%;
    background: var(--white);
}

.b-canvas {
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    width: 100%;
}

.b-header {
    display: flex;
    z-index: 2;
    align-items: center;
    justify-content: start;
    line-height: 60px;
    height: 80px;
    background-color: var(--primary);
    color: var(--white);
    width: 100%;
    margin: 0 auto;
    padding: 0 2rem;
    box-sizing: border-box;

    .b-logo {
        margin-right: 20px;
    }

    a {
        color: inherit;
    }

    @include mobile_tablet() {
        line-height: 30px;
        height: 40px;
    }
}

.b-subheader {
    display: flex;
    align-items: center;
    justify-content: start;
    height: 40px;
    line-height: 40px;
    background-color: var(--primary);
    color: var(--white);
    width: 100%;
    padding: 0 2rem;
    box-sizing: border-box;

    a {
        color: var(--white);

        &:hover {
        }
    }
}

.b-section {
    position: relative;
    margin: 0 auto;
    padding: 0 2rem;

    &-h {
        position: relative;
        z-index: 1;
        margin: 0 auto;
        padding: 2rem 0;
        display: flex;

        &::after {
            content: "";
            display: block;
            clear: both;
        }
    }
}

.b-sidebar {
    &:empty {
        display: none;
    }
}

.b-content {
    flex-grow: 1;
}

.b-footer {
    overflow: hidden;
    font-size: 0.85rem;
    padding: 0 1.5rem;
    line-height: 2rem;
    background-color: var(--white);

    &-h {
        margin: 0 auto;
        max-width: 1200px;
        text-align: center;

        &:last-child > * {
            margin: 0.5rem;
        }
    }
}
</style>
