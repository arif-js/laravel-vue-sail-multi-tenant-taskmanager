module.exports = {
    root: true,
    env: {
        node: true,
    },
    extends: [
        'eslint:recommended',
        'plugin:vue/vue3-recommended',
        '@vue/eslint-config-typescript',
        'prettier', // always last
    ],
    rules: {
        // Don't let ESLint enforce template linebreaks — Prettier does that.
        'vue/html-indent': 'off',
        'vue/max-attributes-per-line': 'off',
        'vue/first-attribute-linebreak': 'off',
        'vue/multi-word-component-names': 'off',
    },
};
