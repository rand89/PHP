<template>
    <div class="w-96 mx-auto">
        <div v-if="posts" class="mt-6 pt-4 border-t border-teal-600">
            <h1 class="text-xl">Posts</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from "@/components/Post.vue";
export default {
    name: "Feed",
    data() {
        return {
            posts: [],
        }
    },

    mounted() {
        this.getPosts()
    },

    components: {
        Post
    },

    methods: {
        getPosts() {
            axios.get(`/api/users/following_posts`)
                .then( r => {
                    this.posts = r.data.data
                })
        },
    }
}
</script>

<style scoped>

</style>
