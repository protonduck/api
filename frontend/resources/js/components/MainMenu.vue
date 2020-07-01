<template>
    <nav>
        <template v-if="isLoggedIn">
            <router-link to="/">Boards</router-link>
            <span> | <a @click="logout">Logout</a></span>
        </template>
        <template v-else>
            <router-link to="/login">Login</router-link>
            <router-link to="/signup">Signup</router-link>
        </template>
    </nav>
</template>

<script>
    export default {
        computed: {
            isLoggedIn: function () {
                return this.$store.getters.isLoggedIn
            }
        },
        methods: {
            logout: function () {
                this.$store.dispatch('logout')
                    .then(() => {
                        this.$router.push('/login')
                    })
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
