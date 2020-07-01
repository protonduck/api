<template>
    <div>
        <main-menu></main-menu>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import MainMenu from "./MainMenu";

    export default {
        components: {
            MainMenu
        },
        created: function () {
            this.$http.interceptors.response.use(undefined, function (err) {
                return new Promise(function (resolve, reject) {
                    if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
                        this.$store.dispatch("logout")
                    }
                    throw err;
                });
            });
        }
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
