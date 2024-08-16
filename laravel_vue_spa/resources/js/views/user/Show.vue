<template>
    <div class="w-96 mx-auto">
        <Stat :stats="stats"></Stat>
        <div v-if="posts" class="mt-6 pt-4 border-t border-teal-600">
            <h1 class="text-xl">Posts</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from "@/components/Post.vue"
import Stat from "@/components/Stat.vue"
export default {
    name: "Show",
    data() {
        return {
            posts: [],
            userId: this.$route.params.id,
            stats: []
        }
    },

    mounted() {
        this.getPosts()
        this.getStats()
    },

    components: {
        Post,
        Stat
    },

    watch: {
        $route() {
            this.userId = this.$route.params.id
            this.getPosts()
            this.getStats()
        }
    },

    methods: {
        getPosts() {
            axios.get(`/api/users/${this.userId}/posts`)
                .then( r => {
                    this.posts = r.data.data
                })
        },

        getStats() {
            axios.post('/api/users/stats', {user_id: this.userId})
                .then( r => {
                    this.stats = r.data.data
                })
        }
    }
}
</script>

<style scoped>

</style>
