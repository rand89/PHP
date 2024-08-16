<template>
    <div class="w-96 mx-auto">
        <div v-if="users">
            <div v-for="user in users" class="flex justify-between items-center mt-4 pt-6 border-t border-teal-600">
                <router-link :to="{name: 'user.show', params: {id: user.id}}">
                    <p>{{ user.id }}</p>
                    <p>{{ user.name }}</p>
                    <p>{{ user.email }}</p>
                </router-link>
                <button @click.prevent="toggleFollowing(user)" :class="['border border-teal-600 p-2 w-32 rounded-lg text-center ml-auto ',
                    user.is_followed ? 'text-teal-600 bg-white' : 'text-white bg-teal-600']" type="submit">
                    {{ user.is_followed ? 'Unfollow' : 'Follow'}}
                </button>


            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "Index",
//class="hover:bg-white hover:text-teal-600  border border-teal-600
    //p-2 w-32 bg-teal-600 rounded-lg text-center text-white ml-auto"
    data() {
        return {
            users: []
        }
    },

    mounted() {
        this.getUsers()
    },

    methods: {
        getUsers() {
            axios.get('/api/users')
                .then(r => {
                    this.users = r.data.data
                })
        },

        toggleFollowing(user) {
            axios.post(`/api/users/${user.id}/toggle_following`)
                .then( r => {
                    user.is_followed = r.data.is_followed
                })
        }
    }
}
</script>

<style scoped>

</style>
