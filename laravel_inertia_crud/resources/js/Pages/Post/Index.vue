<script>
import {Link} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: "Index",

    layout: MainLayout,

    components: {
        Link
    },

    props: [
        'posts'
    ],

    methods: {
        deletePost(id) {
            this.$inertia.delete(`/posts/${id}`)
        }
    }
}

</script>

<template>

    <h1 class="text-lg mb-6">Posts</h1>
    <div class="mb-6">
        <Link class="hover:bg-white hover:text-teal-600 block border border-teal-600
                  p-2 w-32 bg-teal-600 rounded-lg text-center text-white"
              :href="route('post.create')">Add post
        </Link>
    </div>
    <div v-if="posts">
        <div class="mt-6 pt-6 border-t border-teal-600" v-for="post in posts">
            <div>title: {{ post.title }}</div>
            <div>content: {{ post.content }}</div>
            <div class="text-sm text-right">{{ post.date }}</div>
            <div class="text-sm text-right">
                <Link class="text-teal-600 pr-2" :href="route('post.show', post.id)">Show</Link>
                <Link class="text-teal-600 pr-2" :href="route('post.edit', post.id)">Edit</Link>
                <span @click="deletePost(post.id)" class="text-red-600 cursor-pointer ">Delete</span>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
