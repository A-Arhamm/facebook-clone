<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Link, Head } from '@inertiajs/vue3';
import MainNavLayout from '@/Layouts/MainNavLayout.vue';
import CreatePostBox from '@/Components/CreatePostBox.vue';
import Post from '@/Components/Post.vue';

import Camera from 'vue-material-design-icons/Camera.vue';
import Pen from 'vue-material-design-icons/Pen.vue';

import { useGeneralStore } from '@/stores/general';
import { storeToRefs } from 'pinia';
const useGeneral = useGeneralStore()
const { isImageDisplay, isCropperModal } = storeToRefs(useGeneral)

const props = defineProps({
    posts: Object,
    user: Object,
    auth: Object,
});

const isFriend = ref(false);
const isFollow = ref(false);
onMounted(() => {
    checkFriendStatus();
    checkFollowStatus();
});

const checkFriendStatus = async () => {
    try {
        const response = await axios.get(`/friend/status/${props.user.id}`);
        isFriend.value = response.data.isFriend;
    } catch (error) {
        console.error('Error fetching friend status', error);
    }
};

const checkFollowStatus = async () => {
    try {
        const response = await axios.get(`/follow/status/${props.user.id}`);
        isFollow.value = response.data.isFollow;
    } catch (error) {
        console.error('Error fetching follow status', error);
    }
};

const addFriend = async () => {
    try {
        await axios.post(`/friend/add/${props.user.id}`);
        isFriend.value = true;
    } catch (error) {
        console.error('Error adding friend', error);
    }
};

const removeFriend = async () => {
    try {
        await axios.post(`/friend/remove/${props.user.id}`);
        isFriend.value = false;
    } catch (error) {
        console.error('Error removing friend', error);
    }
};

const follow = async () => {
    try {
        await axios.post(`/follow/${props.user.id}`);
        isFollow.value = true;
    } catch (error) {
        console.error('Error follow', error);
    }
};

const unfollow = async () => {
    try {
        await axios.post(`/unfollow/${props.user.id}`);
        isFollow.value = false;
    } catch (error) {
        console.error('Error unfollow', error);
    }
};

const filterPosts = computed(() => {
    if (props.auth.user.id === props.user.id) {
        return props.posts.data;
    }
    else if (isFriend.value) {
        return props.posts.data;
    }
    else {
        return props.posts.data.filter(post => post.status === 'public');
    }
});
</script>

<template>

    <Head title="User" />

    <MainNavLayout>
        <div class="w-full pb-20 min-h-[100vh] bg-[#F1F2F5]">
            <div class="w-full bg-white">
                <div class="max-w-[1100px] mx-auto pt-[56px] pb-1">
                    <img src="https://picsum.photos/id/45/2000/320" class="rounded-b-xl">
                    <div id="ProfileInfo" class="flex md:flex-row flex-col items-center justify-between px-4">
                        <div class="flex md:flex-row flex-col gap-4 md:-mt-6 -mt-16 items-center">
                            <div class="relative">
                                <img :src="user.image" class="rounded-full w-[165px] h-[165px] border-white border-4">
                                <button v-if="$page.props.auth.user.id === user.id" @click="isCropperModal = true"
                                    class="absolute right-0 top-[100px] bg-gray-200 hover:bg-gray-300 p-1.5 rounded-full cursor-pointer">
                                    <Camera :size=25 />
                                </button>
                            </div>
                            <div class="md:mt-4 text-center md:text-left -mt-3">
                                <div class="pt-1 text-[28px] font-extrabold">
                                    {{ user.name }}
                                </div>
                                <div class="text-[17px] font-bold text-gray-600 text-center mb-1.5 md:text-left">
                                    234 Friends</div>
                                <div class="flex md:justify-start justify-center md:ml-1">
                                    <img src="https://picsum.photos/id/141/300/300"
                                        class="w-[40px] h-[40px] rounded-full z-[10] -ml-1 border-2 border-white">
                                    <img src="https://picsum.photos/id/142/300/300"
                                        class="w-[40px] h-[40px] rounded-full z-[10] -ml-3 border-2 border-white">
                                </div>
                            </div>
                        </div>
                        <Link v-if="$page.props.auth.user.id === user.id" :href="route('profile.edit')"
                            class="flex justify-center w-7/12 md:w-[160px] my-4 items-baseline md:my-0 bg-gray-200 hover:bg-gray-300 rounded-lg cursor-pointer">
                        <button class="flex items-center px-5 py-2 font-bold">
                            <Pen class="mr-1 -ml-2" :size=22 /> Edit Profile
                        </button>
                        </Link>
                        <div v-if="$page.props.auth.user.id !== user.id && !isFriend"
                            class="flex justify-center w-7/12 md:w-[160px] my-4 items-baseline md:my-0 bg-gray-200 hover:bg-gray-300 rounded-lg cursor-pointer">
                            <button @click="addFriend" class="flex items-center px-5 py-2 font-bold">
                                Add Friend
                            </button>
                        </div>
                        <div v-if="$page.props.auth.user.id !== user.id && isFriend"
                            class="flex justify-center w-7/12 md:w-[160px] my-4 items-baseline md:my-0 bg-red-200 hover:bg-red-300 rounded-lg cursor-pointer">
                            <button @click="removeFriend" class="flex items-center px-5 py-2 font-bold">
                                Remove Friend
                            </button>
                        </div>
                    </div>
                    <div class="flex border-t h-[50px] py-[4px]">
                        <button class="w-[85px]">
                            <div
                                class="flex items-center justify-center text-[15px] h-[45px] text-blue-500 hover:bg-[#F2F2F2] w-full font-bold rounded-lg cursor-pointer">
                                Posts
                            </div>
                            <div class="border-b-4 border-b-blue-400 rounded-md"></div>
                        </button>
                        <button
                            class="flex items-center justify-center text-[15px] p-1 h-[48px] hover:bg-[#F2F2F2] w-[85px] font-bold rounded-lg mx-1 cursor-pointer">
                            About
                        </button>
                        <button
                            class="flex items-center justify-center text-[15px] p-1 h-[48px] hover:bg-[#F2F2F2] w-[85px] font-bold rounded-lg mx-1 cursor-pointer">
                            Friends
                        </button>
                        <button
                            class="flex items-center justify-center text-[15px] p-1 h-[48px] hover:bg-[#F2F2F2] w-[85px] font-bold rounded-lg mx-1 cursor-pointer">
                            Videos
                        </button>
                        <button
                            class="flex items-center justify-center text-[15px] p-1 h-[48px] hover:bg-[#F2F2F2] w-[85px] font-bold rounded-lg mx-1 cursor-pointer">
                            Photos
                        </button>
                        <div v-if="$page.props.auth.user.id !== user.id && !isFollow">
                            <button @click="follow"
                                class="flex items-center justify-center text-[15px] p-1 h-[48px] hover:bg-[#F2F2F2] w-[85px] font-bold rounded-lg mx-1 cursor-pointer">
                                Follow
                            </button>
                        </div>
                        <div v-if="$page.props.auth.user.id !== user.id && isFollow">
                            <button @click="unfollow"
                                class="flex items-center justify-center text-[15px] p-1 h-[48px] hover:bg-[#F2F2F2] w-[85px] font-bold rounded-lg mx-1 cursor-pointer">
                                Unfollow
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex-cols md:flex w-full max-w-[1100px] justify-between h-[calc(100%-56px)] md:px-0 px-2 mx-auto">
                <div id="LeftSection" class="w-full md:w-5/12 mt-4 mr-4">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <div class="font-extrabold pb-2 text-xl">Intro</div>
                        <div class="pb-5">
                            <button class="w-full bg-gray-200 hover:bg-gray-300 rounded-lg p-2 font-bold">
                                Add bio
                            </button>
                        </div>
                        <div class="pb-5">
                            <button class="w-full bg-gray-200 hover:bg-gray-300 rounded-lg p-2 font-bold">
                                Edit details
                            </button>
                        </div>
                        <div class="pb-5">
                            <button class="w-full bg-gray-200 hover:bg-gray-300 rounded-lg p-2 font-bold">
                                Add hobbies
                            </button>
                        </div>
                        <div class="pb-5">
                            <button class="w-full bg-gray-200 hover:bg-gray-300 rounded-lg p-2 font-bold">
                                Add feature
                            </button>
                        </div>
                    </div>
                </div>
                <div id="PostsSection" class="w-full md:w-7/12 overflow-auto">
                    <CreatePostBox v-if="$page.props.auth.user.id === user.id" :image="user.image"
                        :placeholder="'What\'s on your mind ' + user.name" />
                    <div v-for="post in filterPosts" :key="post.id">
                        <Post :user="post.user" :post="post" :comments="post.comments" />
                    </div>
                </div>
            </div>
        </div>
    </MainNavLayout>
</template>