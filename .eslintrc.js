module.exports = {
    root: true,
    parser: "vue-eslint-parser",
    /*    parserOptions: {
        parser: "@typescript-eslint/parser",
    },*/
    extends: [
        "plugin:vue/strongly-recommended",
        "eslint:recommended",
        "plugin:prettier/recommended",
        /*        "@vue/typescript/recommended",*/
        "prettier",
    ],
    plugins: ["@typescript-eslint", "prettier"],
    rules: {
        indent: ["error", 4],
        // not needed for vue 3
        "vue/no-multiple-template-root": "off",
        // Хорошо для чистых Vue3-приложений. Не бьется с именованием InertiaJs -> Vue3
        "vue/multi-word-component-names": "off",
        // Используются для Vue3, не актуальны в Vue3
        "vue/no-v-for-template-key": "off",
        // Редко где реально нужно: если для всех объектов проставлять дефолтные значения, то просто раздувает код
        "vue/require-default-prop": "off",
        "vue/script-indent": ["error", 4],
        "vue/html-indent": ["warn", 4],
        // Настройки prettier
        "prettier/prettier": "error",
    },
    env: {
        node: true,
    },
};
