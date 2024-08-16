<template>
    <div class="mt-4 pt-6 border-t border-teal-600">
        <h2 class="text-xl">{{ post.title }}</h2>

        <router-link class="text-teal-600 text-sm"  :to="{name: 'user.show', params: {id: post.user.id}}">by {{ post.user.name }}</router-link>

        <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="my-3 mx-auto">
        <p>{{ post.content }}</p>

        <div v-if="post.reposted_post" class="bg-teal-50 p-4 my-4 border border-teal-200">
            <h2 class="text-xl">{{ post.reposted_post.title }}</h2>

            <router-link class="text-teal-600 text-sm" :to="{name: 'user.show', params: {id: post.reposted_post.user.id}}">by {{ post.reposted_post.user.name }}</router-link>

            <img v-if="post.reposted_post.image_url" :src="post.reposted_post.image_url" :alt="post.reposted_post.title" class="my-3 mx-auto">
            <p>{{ post.reposted_post.content }}</p>
        </div>

        <div class="flex justify-between items-center mt-2 mb-6">
            <div class="flex">

                <div class="flex mr-2">
                    <svg @click.prevent="toggleLike(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         stroke-width="1.5"
                         stroke="currentColor"
                         :class="['mr-1 stroke-teal-600 cursor-pointer hover:fill-teal-600 w-6 h-6', post.is_liked ? 'fill-teal-600' : 'fill-white']">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                    <p>{{ post.likes_count }}</p>
                </div>
                <div class="flex">
                    <svg @click.prevent="openRepost" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor"
                         :class="['mx-2 stroke-teal-600 cursor-pointer hover:fill-teal-600 w-6 h-6 fill-white']">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                    </svg>
                    <p>{{ post.reposted_by_posts_count }}</p>
                </div>

            </div>
            <p class="mt-2 text-right text-sm text-slate-600">{{ post.date }}</p>
        </div>

        <div v-if="isRepost" class="mb-4">
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
            <div>
                <button @click.prevent="repost(post)" class="hover:bg-white hover:text-teal-600  border border-teal-600
                            p-2 w-32 bg-teal-600 rounded-lg text-center text-white block ml-auto" type="submit">Repost
                </button>
            </div>
        </div>

        <div v-if="post.comments_count > 0" class="mb-4">
            <p v-if="!isOpen" @click="getComments(post)" class="cursor-pointer">Read {{ post.comments_count }} comments</p>
            <p v-if="isOpen" @click="isOpen = false" class="cursor-pointer">Close</p>
            <div v-if="isOpen && comments">
                <div v-for="comment in comments" class="mt-4 pt-4 border-t border-gray-300">
                    <div class="flex mb-2">
                        <p class="text-sm mr-2">{{ comment.user.name }}</p>
                        <p @click="setParentId(comment)" class="text-teal-600 text-sm cursor-pointer">Answer</p>
                    </div>
                    <p><span v-if="comment.answered_for_user" class="text-teal-600">{{ comment.answered_for_user }}, </span> {{ comment.body }}</p>
                    <p class="text-right text-sm">{{ comment.date }}</p>
                </div>

            </div>
        </div>

        <div>
            <div class="mb-4">
                <div v-if="comment" class="flex items-center">
                    <p class="mr-2" >Answered for {{ comment.user.name }}</p>
                    <p @click="comment = null" class="cursor-pointer text-sm text-teal-600">Cancel</p>
                </div>
                <input v-model="body" type="text" placeholder="comment"
                       class="w-full p-2 border border-teal-600 rounded-lg">
                <div v-if="errors.body">
                    <p v-for="error in errors.body" class="text-red-600 text-sm mt-2">{{ error }}</p>
                </div>
            </div>
            <div>
                <button @click.prevent="storeComment(post)" class="hover:bg-white hover:text-teal-600  border border-teal-600
                  p-2 w-32 bg-teal-600 rounded-lg text-center text-white block ml-auto" type="submit">Add comment
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Post",

    props: [
        'post'
    ],

    data() {
        return {
            title: '',
            content: '',
            body: '',
            comments: [],
            isOpen: false,
            isRepost: false,
            repostedId: null,
            comment: null,
            errors: []
        }
    },

    methods: {
        toggleLike(post) {
            axios.post(`/api/posts/${post.id}/toggle_like`)
                .then(r => {
                    post.is_liked = r.data.is_liked
                    post.likes_count = r.data.likes_count
                })
        },

        storeComment(post) {
            const commentId = this.comment ? this.comment.id : null
            axios.post(`/api/posts/${post.id}/comment`, {body: this.body, parent_id: commentId})
                .then(r => {
                    this.body = ''
                    this.comments.push(r.data.data)
                    this.comment = null
                    post.comments_count++
                    this.isOpen = true
                })
                .catch(e => {
                    this.errors = e.response.data.errors
                })
        },

        getComments(post) {
            axios.get(`/api/posts/${post.id}/comment`)
                .then(r => {
                    this.comments = r.data.data
                    this.isOpen = true
                })
        },

        setParentId(comment) {
            this.comment = comment
        },

        openRepost() {
            if (this.isPersonal()) {
                return
            }
            this.isRepost = !this.isRepost
        },

        repost(post) {
            if (this.isPersonal()) {
                return
            }
            axios.post(`/api/posts/${post.id}/repost`, {title: this.title, content: this.content})
                .then(r => {
                    this.title = ''
                    this.content = ''
                })
                .catch(e => {
                    this.errors = e.response.data.errors
                })
        },

        isPersonal() {
            return this.$route.name === 'user.personal'
        }
    }
}
</script>

<style scoped>

</style>
