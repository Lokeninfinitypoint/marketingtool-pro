import js from "@eslint/js";
import pluginVue from "eslint-plugin-vue";
import vueParser from "vue-eslint-parser";

export default {
  files: ["**/*.vue", "**/*.js"],
  languageOptions: {
    ecmaVersion: "latest",
    sourceType: "module",
    parser: vueParser,
  },
  plugins: { vue: pluginVue },
  rules: {
    ...js.configs.recommended.rules,
    ...pluginVue.configs["flat/recommended"].rules,
    "no-unused-vars": "warn",
    "vue/multi-word-component-names": "off"
  }
};
