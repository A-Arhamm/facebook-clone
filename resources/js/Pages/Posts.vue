<script setup>
import { Head, usePage, Link } from '@inertiajs/vue3';
import MainNavLayout from '@/Layouts/MainNavLayout.vue';
import CreatePostBox from '@/Components/CreatePostBox.vue';
import Post from '@/Components/Post.vue';

import Magnify from 'vue-material-design-icons/Magnify.vue';
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import VideoImage from 'vue-material-design-icons/VideoImage.vue';
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue';

defineProps({ posts: Object })
const user = usePage().props.auth.user
</script>

<template>

    <Head title="Posts" />


    <MainNavLayout>
        <div class="fixed w-full h-[100%] bg-[#F1F2F5]">
            <div
                class="grid grid-rows-3 grid-flow-col w-full max-w-[1600px] mt-[56px] mx-auto px-4 h-[calc(100%-56px)]">
                <div id="LeftSection" class="xl:w-[345px] lg:block hidden">
                    <div class="pt-4 max-w-[320px] mr-4">
                        <Link :href="route('user.show', { id: user.id })"
                            class="flex items-center justify-start w-full cursor-pointer p-2 rounded-md hover:bg-[#E5E69]">
                        <img :src="user.image" class="ml-1 rounded-full min-w-[38px] max-h-[38px]">
                        <div class="text-[15px] text-gray-800 font-extrabold pl-3">{{ user.name }}</div>
                        </Link>
                        <button
                            class="flex items-center justify-start w-full cursor-pointer hover:bg-[#E5E6E9] px-2 py-1.5 rounded-md">
                            <AccountMultiple :size=40 fill-color="#5BD7C6" />
                            <div class="text-[15px] text-gray-800 pl-3 font-extrabold">Friends</div>
                        </button>
                    </div>
                </div>

                <div id="PostSection" class="row-span-6 max-w-[600px] lg:mx-0 mx-auto overflow-auto">
                    <CreatePostBox :image="user.image" :placeholder="'What\'s on your mind ' + user.name" />

                    <div v-for="post in posts.data" :key="post">
                        <Post :user="post.user" :post="post" :comments="post.comments" />
                    </div>
                </div>

                <div id="RightSection" class="pl-4 md:block hidden">
                    <div class="max-w-[340px] min-w-[250px] mx-auto pt-4">
                        <div class="flex items-center justify-between border-b border-b-gray-300">
                            <div class="font-semibold">Contacts</div>
                            <div class="flex items-center">
                                <div class="p-2 rounded-full cursor-pointer hover:bg-gray-300">
                                    <VideoImage :size=23 fill-color="#050505" />
                                </div>
                                <div class="p-2 rounded-full cursor-pointer hover:bg-gray-300">
                                    <Magnify :size=23 fill-color="#050505" />
                                </div>
                                <div class="p-2 rounded-full cursor-pointer hover:bg-gray-300">
                                    <DotsHorizontal :size=23 fill-color="#050505" />
                                </div>
                            </div>
                        </div>
                        <div class="h-[calc(100vh-115px)] overflow-auto pt-2">
                            <div
                                class="flex items-center justify-start cursor-pointer py-2 rounded-md hover:bg-[#E5E6E9]">
                                <img src="https://picsum.photos/id/140/300/320"
                                    class="rounded-full ml-1 min-w-[38px] max-h-[38px]">
                                <div class="text-[15px] text-gray-800 font-semibold pl-3">Nafay</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainNavLayout>
</template>
