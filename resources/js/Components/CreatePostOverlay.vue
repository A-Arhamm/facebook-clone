<script setup>
import { ref, reactive } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

import Image from 'vue-material-design-icons/Image.vue';
import Close from 'vue-material-design-icons/Close.vue';

import { useGeneralStore } from '@/stores/general';
import { storeToRefs } from 'pinia';
const useGeneral = useGeneralStore()
const { isPostOverlay } = storeToRefs(useGeneral)

const emit = defineEmits(['showModal'])
const user = usePage().props.auth.user

let imageDisplay = ref('')

const form = reactive({
    text: null,
    image: null,
    status: 'public',
})
let error = ref(null)

const createPost = () => {
    router.post('/post', form, {
        forceFormData: true,
        preserveScroll: true,
        onError: errors => {
            errors && errors.text ? error.value = errors.text : ''
            errors && errors.image ? error.value = errors.image : ''
        },
        onSuccess: () => {
            form.text = null
            form.image = null
            form.status = 'public'
            imageDisplay.value = null
            emit('showModal', false)
        }
    })
}

const getUploadedImage = (e) => {
    imageDisplay.value = URL.createObjectURL(e.target.files[0])
    form.image = e.target.files[0]
}

const clearImage = () => {
    imageDisplay.value = null
    form.image = null
}
</script>

<template>
    <div id="CreatePostOverlay" class="fixed z-50 top-0 left-0 w-full h-full bg-white bg-opacity-70">
        <div class="grid h-screen place-items-center pt-4">
            <div class="bg-white w-full max-w-[600px] mx-auto shadow-xl rounded-xl">
                <div class="flex items-center relative mx-1 my-3.5">
                    <div class="text-[22px] font-extrabold w-full text-center">
                        Create Post
                    </div>
                    <div @click="isPostOverlay = false"
                        class="absolute right-3 rounded-full p-1.5 bg-gray-200 hover:bg-gray-300 cursor-pointer">
                        <Close :size=28 fill-color="#5E6771" />
                    </div>
                </div>
                <div class="border-t border-t-gray-300">
                    <div class="p-4">
                        <div class="flex items-center">
                            <img :src="user.image" class="ml-1 rounded-full min-w-[45px] max-h-[45px]">
                            <div class="ml-4">
                                <div class="font-extrabold">{{ user.name }}</div>
                                <div
                                    class="flex items-center justify-between w-[115px] bg-gray-200 p-0.5 px-2 rounded-lg">
                                    <select v-model="form.status" class="border-0 bg-gray-200 focus:ring-0">
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                        <option value="friends">Friends</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="max-h-[350px] overflow-auto">
                            <textarea cols="30" rows="3" v-model="form.text"
                                class="w-full border-0 mt-4 focus:ring-0 text-[22px]"
                                placeholder="What's on your mind?"></textarea>
                            <div v-if="form.image" class="p-2 border border-gray-300 rounded-lg relative">
                                <Close class="absolute bg-white p-0.5 m-2 right-2 rounded-full border cursor-pointer"
                                    @click="clearImage" :size=24 fill-color="#5E6771" />
                                <img :src="imageDisplay" class="rounded-lg mx-auto">
                            </div>
                        </div>
                        <div class="border-2 rounded-xl mt-4 shadow-sm flex items-center justify-between">
                            <div class="p-4 font-extrabold">
                                Add to your post
                            </div>
                            <div class="flex items-center">
                                <label for="image" class="hover:bg-gray-200 p-2 rounded-full cursor-pointer">
                                    <Image :size=27 fill-color="#43BE62" />
                                </label>
                                <input type="file" id="image" class="hidden" @input="getUploadedImage($event)">
                            </div>
                        </div>
                        <div v-if="error">
                            <div class="w-full bg-red-100 text-red-700 rounded-lg mt-3 text-center">
                                <div class="p-0.5">
                                    {{ error }}
                                </div>
                            </div>
                        </div>
                        <button @click="createPost"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-extrabold p-1.5 mt-3 rounded-lg">
                            Post
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
