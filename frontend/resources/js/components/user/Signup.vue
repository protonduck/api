<template>
    <div>
        <h1>{{ $t('menu.signup') }}</h1>
        <form @submit.prevent="register">
            <div class="form-group">
                <label for="name">{{ $t('form.username') }}</label>
                <input id="name" type="text" class="form-control" v-model="name" required autofocus>
            </div>
            <div class="form-group">
                <label for="email">{{ $t('form.email') }}</label>
                <input id="email" type="email" class="form-control" v-model="email" required>
            </div>
            <div class="form-group">
                <label for="password">{{ $t('form.password') }}</label>
                <input id="password" type="password" class="form-control" v-model="password" required>
            </div>
            <div class="form-group">
                <label for="password-confirm">{{ $t('form.passwordConfirm') }}</label>
                <input id="password-confirm" type="password" class="form-control" v-model="password_confirmation"
                       required>
            </div>

            <button type="submit" class="btn btn-success">{{ $t('menu.signup') }}</button>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                is_admin: null,
            }
        },
        methods: {
            register: function () {
                let data = {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    is_admin: this.is_admin,
                }
                this.$store.dispatch('register', data)
                    .then(() => this.$router.push('/'))
                    .catch(err => console.log(err))
            },
        },
    }
</script>

<style scoped lang="scss">

</style>
