<script setup>
import { toRefs } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

import VideoImage from 'vue-material-design-icons/VideoImage.vue';
import Image from 'vue-material-design-icons/Image.vue';
import EmoticonOutline from 'vue-material-design-icons/EmoticonOutline.vue';

import { useGeneralStore } from '@/stores/general';
import { storeToRefs } from 'pinia';
const useGeneral = useGeneralStore()
const { isPostOverlay } = storeToRefs(useGeneral)

const props = defineProps({
    image: String,
    placeholder: String
});
const { image, placeholder } = toRefs(props)

const user = usePage().props.auth.user
</script>

<template>
    <div id="CreatePostBox" class="w-full bg-white rounded-lg px-3 mt-4 shadow-sm">
        <div class="flex items-center py-3 border-b">
            <Link :href="route('user.show', { id: user.id })" class="mr-2">
            <img :src="image" class="ml-1 rounded-full min-w-[36px] max-h-[36px]">
            </Link>
            <div @click="isPostOverlay = true"
                class="flex items-center justify-start p-2 rounded-full w-full cursor-pointer bg-[#EFF2F5]">
                <div class="text-left pl-2">{{ placeholder }}</div>
            </div>
        </div>

        <div class="flex items-center py-3 border-b">
            <button
                class="flex items-center justify-center p-1 w-full rounded-lg hover:bg-[#F2F2F2] mx-1 cursor-pointer">
                <VideoImage :size=35 fill-color="#F12848" />
                <div class="text-[#6F7275] font-bold">Live Video</div>
            </button>
            <button
                class="flex items-center justify-center p-1 w-full rounded-lg hover:bg-[#F2F2F2] mx-1 cursor-pointer">
                <Image :size=35 fill-color="#43BE62" />
                <div class="text-[#6F7275] font-bold">Photo/Video</div>
            </button>
            <button
                class="flex items-center justify-center p-1 w-full rounded-lg hover:bg-[#F2F2F2] mx-1 cursor-pointer">
                <EmoticonOutline :size=35 fill-color="#F8B927" />
                <div class="text-[#6F7275] font-bold">Feeling/Activity</div>
            </button>
        </div>
    </div>
</template>
