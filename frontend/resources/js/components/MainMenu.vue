<template>
    <nav>
        <router-link to="/">Boards</router-link>
        <router-link to="/login">Login</router-link>
        <router-link to="/signup">Signup</router-link>
        <span v-if="isLoggedIn"> | <a @click="logout">Logout</a></span>
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

<style scoped>
    nav {
        text-align: center;
    }

    nav ul {
        list-style: none;
    }
</style>
