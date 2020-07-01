<template>
    <div>
        <h1>Sign in</h1>
        <form @submit.prevent="login">
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" required v-model="email" type="email" placeholder="Name" class="form-control"/>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" required v-model="password" type="password" placeholder="Password"
                       class="form-control"/>
            </div>

            <button class="btn btn-success" type="submit">Login</button>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: "",
                password: ""
            }
        },
        methods: {
            login: function () {
                let email = this.email
                let password = this.password
                this.$store.dispatch('login', {email, password})
                    .then(() => this.$router.push('/'))
                    .catch(err => console.log(err))
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
