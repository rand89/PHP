<template>
    <div>
        <div class="w-96 mx-auto">

            <div class="mb-4">
                <input v-model="name" type="name" placeholder="name"
                       class="w-full p-2 border border-teal-600 rounded-lg">
            </div>
            <div class="mb-4">
                <input v-model="email" type="email" placeholder="email"
                       class="w-full p-2 border border-teal-600 rounded-lg">
            </div>
            <div class="mb-4">
                <input v-model="password" type="password" placeholder="password"
                       class="w-full p-2 border border-teal-600 rounded-lg">
            </div>
            <div class="mb-4">
                <input v-model="password_confirmation" type="password" placeholder="password_confirmation"
                       class="w-full p-2 border border-teal-600 rounded-lg">
            </div>
            <input @click.prevent="register" type="submit" value="register"
                   class="block float-right hover:bg-white hover:text-teal-600 block border border-teal-600
                  p-2 w-32 bg-teal-600 rounded-lg text-center text-white">
        </div>
    </div>
</template>

<script>
export default {
    name: "Registration",

    data() {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
        }
    },

    methods: {
        register() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation
                    })
                    .then( res => {
                        localStorage.setItem('x_xsrf_token', 'true')
                        this.$router.push({name: 'user.personal'})
                    })
                })
        }
    }
}
</script>

<style scoped>

</style>
