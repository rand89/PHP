<script>
import {Link} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: "Index",
    components: {
        Link
    },

    layout: MainLayout,

    data() {
        return {
            id: this.post.id,
            title: this.post.title,
            content: this.post.content
        }
    },

    props: [
        'post'
    ],

    methods: {
        update() {
            this.$inertia.patch(`/posts/${this.id}`, {title: this.title, content: this.content})
        }
    }
}

</script>

<template>

    <h1 class="text-lg mb-6">Edit Post</h1>
    <div class="mb-6">
        <Link :href="route('post.index')" class="text-teal-600"><- Back</Link>
    </div>
    <form @submit.prevent="update">
        <div class="mb-4">
            <input v-model="title" class="w-full border-teal-600 rounded-lg" type="text" placeholder="title">
        </div>
        <div class="mb-4">
            <textarea v-model="content" class="w-full border-teal-600 rounded-lg" placeholder="content"></textarea>
        </div>
        <div>
            <button class="hover:bg-white hover:text-teal-600 block border border-teal-600
                  p-2 w-32 bg-teal-600 rounded-lg text-center text-white" type="submit">Update
            </button>
        </div>
    </form>

</template>

<style scoped>

</style>
