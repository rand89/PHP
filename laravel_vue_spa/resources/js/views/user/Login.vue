<template>
    <div class="w-96 mx-auto">
        <div class="mb-4">
            <input v-model="email" type="email" placeholder="email" class="w-full p-2 border border-teal-600 rounded-lg">
        </div>
        <div class="mb-4">
            <input v-model="password" type="password" placeholder="password" class="w-full p-2 border border-teal-600 rounded-lg">
        </div>
        <div>
            <input @click.prevent="login" type="submit" value="login" class="block float-right hover:bg-white hover:text-teal-600 block border border-teal-600
                  p-2 w-32 bg-teal-600 rounded-lg text-center text-white">
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",

    data() {
        return {
            email: null,
            password: null,
        }
    },

    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('/login', {email: this.email, password: this.password})
                        .then(r => {
                            localStorage.setItem('x_xsrf_token', 'true')
                            this.$router.push({name: 'user.personal'})
                        })
                        .catch(err => {
                        })
                })
        }
    }
}
</script>

<style scoped>

</style>
