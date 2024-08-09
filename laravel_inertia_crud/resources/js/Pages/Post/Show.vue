<script>
import {Link} from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'

export default {
    name: "Index",

    layout: MainLayout,

    components: {
        Link
    },

    props: [
        'post'
    ]
}

</script>

<template>

        <h1 class="text-lg mb-6">Post</h1>
        <div class="mb-6">
            <Link :href="route('post.index')" class="text-teal-600"><- Back</Link>
        </div>
        <div v-if="post">
            <div class="mt-6 pt-6 border-t border-teal-600" >
                <div>title: {{ post.title }}</div>
                <div>content: {{ post.content }}</div>
                <div class="text-sm text-right">{{ post.date }}</div>
            </div>
        </div>

</template>

<style scoped>

</style>
