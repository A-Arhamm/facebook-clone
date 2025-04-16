<script setup>
import { toRefs, reactive, toRef, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';

import { useGeneralStore } from '@/stores/general';
import { storeToRefs } from 'pinia';
const useGeneral = useGeneralStore()
const { isImageDisplay } = storeToRefs(useGeneral)

const form = reactive({ comment: null })

const props = defineProps({
    user: Object,
    post: Object,
    comments: Object,
    auth: Object,
});
const { user, post, comments, auth } = toRefs(props)

const isLiked = ref(post.is_liked_by_auth_user);

const createComment = () => {
    router.post('/comment', {
        post_id: post.value.id,
        text: form.comment
    }, {
        preserveScroll: true,
    })
}

const deleteComment = (id) => {
    router.delete('/comment/' + id, {
        preserveScroll: true,
    })
}

const deletePost = (id) => {
    router.delete('/post/' + id, {
        preserveScroll: true,
    })
}

const isUser = () => {
    router.get('/user/' + user.value.id)
}

const toggleLike = () => {
    if (isLiked.value) {
        router.delete(`/posts/${post.id}/like`, {
            onSuccess: () => {
                isLiked.value = false;
            }
        });
    } else {
        router.post(`/posts/${post.id}/like`, {}, {
            onSuccess: () => {
                isLiked.value = true;
            }
        });
    }
};
</script>

<template>
    <div id="Post" class="w-full bg-white rounded-lg my-4 shadow-sm">
        <div class="flex items-center py-3 px-3">
            <button @click="isUser" class="mr-2">
                <img :src="user.image" class="ml-1 rounded-full min-w-[42px] max-h-[42px]">
            </button>
            <div class="flex items-center justify-between p-2 rounded-full w-full">
                <div>
                    <div class="font-extrabold text-[15px]">{{ user.name }}</div>
                    <div class="flex items-center text-xs text-gray-600">
                        {{ post.created_at }}
                        <AccountMultiple :size=15 class="ml-1" fill-color="#64676B" />
                    </div>
                </div>
                <div class="flex items-center">
                    <button @click="deletePost(post.id)" class="rounded-full p-1.5 cursor-pointer hover:bg-[#F2F2F2]">
                        <Delete v-if="$page.props.auth.user.id === user.id" fill-color="#64676B" />
                    </button>
                </div>
            </div>
        </div>
        <div class="px-5 pb-2 text-[17px] font-semibold">
            {{ post.text }}
        </div>
        <img @click="isImageDisplay = post.image" :src="post.image" class="mx-auto cursor-pointer">
        <div id="Likes" class="px-5">
            <div class="flex items-center justify-between py-3 border-b">
                <button @click="toggleLike" class="flex items-center">
                    <ThumbUp :size="16" :fill-color="isLiked ? '#1D72E2' : '#64676B'" />
                </button>
                <div class="text-sm text-gray-600 font-semibold">{{ comments.length }} comments</div>
            </div>
        </div>
        <div id="Comments" class="px-3">
            <div id="CreateComment" class="flex items-center justify-between py-2">
                <div class="flex items-center w-full">
                    <Link :href="route('user.show', { id: $page.props.auth.user.id })" class="mr-2">
                    <img :src="$page.props.auth.user.image" class="rounded-full ml-1 min-w-[36px] max-h-[36px]">
                    </Link>
                    <div class="flex items-center justify-center p-2 rounded-full w-full bg-[#EFF2F5]">
                        <input type="text" v-model="form.comment"
                            class="w-full mx-1 border-none p-0 text-sm bg-[#EFF2F5] placeholder-[#64676B] ring-0 focus:ring-0"
                            placeholder="Write a public comment...">
                        <button @click="createComment" type="button"
                            class="flex items-center text-sm pl-2 pr-3.5 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check fill-color="#FFFFFF" :size=20 /> Send
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="comments" id="Comment" class="max-h-[120px] overflow-auto pb-2 px-4">
                <div v-for="comment in comments" :key="comment" class="flex items-center justify-between pb-2">
                    <div class="flex items-center w-full mb-1">
                        <Link class="mr-2" :href="route('posts.index')">
                        <img :src="comment.user.image" class="rounded-full ml-1 min-w-[36px] max-h-[36px]">
                        </Link>
                        <div class="flex items-center w-full">
                            <div class="flex items-center bg-[#EFF2F5] text-xs p-2 w-full rounded-lg">
                                {{ comment.text }}
                            </div>
                            <button v-if="$page.props.auth.user.id === comment.user.id"
                                @click="deleteComment(comment.id)"
                                class="ml-2 p-1.5 rounded-full cursor-pointer hover:bg-[#F2F2F2]">
                                <Delete fill-color="#64676B" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
