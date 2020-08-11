<template>
    <nav>
        <template v-if="isLoggedIn">
            <router-link :to="{name: 'home'}">{{ $t('menu.boards') }}</router-link>
            <span> | <a href="#" @click.prevent="logout">{{ $t('menu.logout') }}</a></span>
        </template>
        <template v-else>
            <router-link :to="{name: 'login'}">{{ $t('menu.login') }}</router-link>
            <router-link :to="{name: 'signup'}">{{ $t('menu.signup') }}</router-link>
        </template>
        <div class="locale-changer">
            <select v-model="selectedLanguage" @change="changeLang($event.target.value)">
                <option v-for="(lang, i) in langs" :key="`Lang${i}`" :value="lang">
                    {{ lang }}
                </option>
            </select>
        </div>
    </nav>
</template>

<script>
    import {loadLanguageAsync, localeParamName} from '../lang/i18n-setup';

    export default {
        data() {
            return {
                selectedLanguage: 'en',
                langs: ['en', 'ru'],
            }
        },
        computed: {
            isLoggedIn: function () {
                return this.$store.getters.isLoggedIn;
            },
        },
        methods: {
            logout: function () {
                this.$store.dispatch('logout')
                    .then(() => {
                        this.$router.push('/login');
                    });
            },
            changeLang(lang) {
                loadLanguageAsync(lang).then();
            },
        },
        created() {
            const selectedLocale = localStorage.getItem(localeParamName);
            if (selectedLocale) {
                this.selectedLanguage = selectedLocale;
            }
        },
    }
</script>

<style scoped lang="scss">
    nav {
        text-align: center;

        ul {
            list-style: none;
        }
    }
</style>
