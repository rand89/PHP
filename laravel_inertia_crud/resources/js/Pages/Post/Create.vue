<script >
import {Link} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
export default {
    name: "Create",
    components: {
        Link
    },

    layout: MainLayout,

    data() {
        return {
            title: '',
            content: ''
        }
    },

    props: [
      'errors'
    ],

    methods: {
        store() {
            this.$inertia.post('/posts', {title: this.title, content: this.content})
        }
    }
}

</script>

<template>

    <h1 class="text-lg mb-6">Create Post</h1>
    <div class="mb-6">
        <Link :href="route('post.index')" class="text-teal-600"><- Back</Link>
    </div>
    <form @submit.prevent="store">
        <div class="mb-4">
            <input v-model="title" class="w-full border-teal-600 rounded-lg" type="text" placeholder="title">
            <div v-if="errors.title" class="text-red-800 text-sm">{{ errors.title }}</div>
        </div>
        <div class="mb-4">
            <textarea v-model="content" class="w-full border-teal-600 rounded-lg" placeholder="content"></textarea>
            <div v-if="errors.content" class="text-red-800 text-sm">{{ errors.content }}</div>
        </div>
        <div>
            <button class="hover:bg-white hover:text-teal-600 block border border-teal-600
                  p-2 w-32 bg-teal-600 rounded-lg text-center text-white" type="submit">Add</button>
        </div>
    </form>

</template>

<style scoped>

</style>
