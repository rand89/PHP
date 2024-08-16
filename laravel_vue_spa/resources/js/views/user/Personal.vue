<template>
    <div class="w-96 mx-auto">
        <Stat :stats="stats"></Stat>
        <div class="mb-4">
            <div class="mb-4">
                <input v-model="title" type="text" placeholder="title"
                       class="w-full p-2 border border-teal-600 rounded-lg">
                <div v-if="errors.title">
                    <p v-for="error in errors.title" class="text-red-600 text-sm mt-2">{{ error }}</p>
                </div>
            </div>
            <div class="mb-4">
                <textarea v-model="content" placeholder="content"
                          class="w-full p-2 border border-teal-600 rounded-lg"></textarea>
                <div v-if="errors.content">
                    <p v-for="error in errors.content" class="text-red-600 text-sm mt-2">{{ error }}</p>
                </div>
            </div>
            <div class="flex mb-4 items-center">
                <div>
                    <input @change="storeImage" ref="file" type="file" class="hidden">
                    <button class="hover:bg-white hover:text-teal-500  border border-teal-500
                  p-2 w-32 bg-teal-500 rounded-lg text-center text-white " @click.prevent="selectFile">Image
                    </button>
                </div>
                <div v-if="image">
                    <button class="ml-3" @click.prevent="image = null">Cancel</button>
                </div>
            </div>
            <div v-if="image" class="mb-4">
                <img :src="image.url" alt="imagePreview">
            </div>
            <div>
                <button @click.prevent="store" class="hover:bg-white hover:text-teal-600  border border-teal-600
                  p-2 w-32 bg-teal-600 rounded-lg text-center text-white block ml-auto" type="submit">Add
                </button>
            </div>
        </div>

        <div v-if="posts" class="mt-6 pt-4 border-t border-teal-600">
            <h1 class="text-xl">My posts</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>

    </div>
</template>

<script>
import Post from "@/components/Post.vue"
import Stat from "@/components/Stat.vue";
export default {
    name: "Personal",
    data() {
        return {
            title: '',
            content: '',
            image: null,
            posts: [],
            errors: [],
            stats: []
        }
    },

    components: {
        Post,
        Stat
    },

    mounted() {
        this.getPosts()
        this.getStats()
    },

    methods: {
        getPosts() {
            axios.get('/api/posts')
                .then( r => {
                    this.posts = r.data.data
                })
        },

        store() {
            const id = this.image ? this.image.id : null
            axios.post('/api/posts', {title: this.title, content: this.content, image_id: id})
                .then(r => {
                    this.title = ''
                    this.content = ''
                    this.image = null
                    this.posts.unshift(r.data.data)
                })
                .catch(e => {
                    this.errors = e.response.data.errors
                })
        },

        selectFile() {
            this.fileInput = this.$refs.file
            this.fileInput.click()
        },

        storeImage(e) {
            const file = e.target.files[0]
            const data = new FormData()
            data.append('image', file)
            axios.post('/api/post_images', data)
                .then(r => {
                    this.image = r.data.data
                })
        },

        getStats() {
            axios.post('/api/users/stats', {user_id: null})
                .then( r => {
                    this.stats = r.data.data
                })
        }
    }


}
</script>

<style scoped>

</style>
