<script setup>
import {defineComponent, ref} from "vue";
import {router} from "@inertiajs/vue3";
import Layout from "@layouts/AdminLayout.vue";
import {Notyf} from "notyf";
import {Bold, ClassicEditor, Essentials, Heading, Italic, Paragraph, Undo} from "ckeditor5";
import CKEditor from '@ckeditor/ckeditor5-vue';

import 'ckeditor5/ckeditor5.css';

defineComponent(
    {
        ckeditor: CKEditor.component
    }
)
const props = defineProps({
    news: Object,
});

const newsType = ref([
    "News",
    "Event",
    "Patch",
    "Promotion",
    "Information",
    "Maintenance",
]);

const ckEditorConfig = {
    editor: ClassicEditor,
    editorData: props.news.description,
    editorConfig: {
        plugins: [Bold, Essentials, Italic, Paragraph, Undo, Heading],
        toolbar: ['undo', 'redo', '|', 'bold', 'italic', '|', 'heading'],
    }
};

const news_content = ref(props.news.Texte_news);
const form = ref({
    id: props.news.id,
    title: props.news.title,
    description: props.news.description,
    type: props.news.type - 1,
    display: props.news.display,
});

const isSubmitting = ref(false);
const submitForm = () => {
    isSubmitting.value = true;
    const formData = new FormData();
    for (const key in form.value) {
        formData.append(key, form.value[key]);
    }

    if (form.value.IMGLink) {
        formData.append("IMGLink", form.value.IMGLink);
    }

    formData.append("Content", ckEditorConfig.editorData);
    
    axios
        .post(`/administrator-panel/edit-news/${props.news.id}`, formData)
        .then((res) => {
            isSubmitting.value = false;

            if (res.status === 200) {
                router.visit("/administrator-panel/news");
                new Notyf().success("News has been updated successfully");
            }
        })
        .catch((err) => {
            isSubmitting.value = false;
            console.log(err);
        });
};

const handleImageUpload = (e) => {
    form.value.IMGLink = e.target.files[0];
};
</script>

<template>
    <layout>
      <div class="mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
          <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <div class="mb-4">
              <label for="Title" class="block text-sm font-medium text-gray-700">Title:</label>
              <input
                type="text"
                id="Title"
                v-model="form.title"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2"
                required
              />
            </div>
            <div class="mb-4">
              <label for="content" class="block text-sm font-medium text-gray-700">Content:</label>
              <ckeditor :editor="ckEditorConfig.editor" v-model="ckEditorConfig.editorData"
                        :config="ckEditorConfig.editorConfig"></ckeditor>
            </div>
            <div class="mb-4">
              <label for="IMGLink" class="block text-sm font-medium text-gray-700">Image:</label>
              <input
                type="file"
                @change="handleImageUpload"
                accept="image/*"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2"
              />
            </div>
            <div class="mb-4">
              <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
              <select v-model="form.type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2">
                <option v-for="(type, index) in newsType" :key="index" :value="index">
                  {{ type }}
                </option>
              </select>
            </div>
            <div class="mb-4">
              <label for="display" class="block text-sm font-medium text-gray-700">Display:</label>
              <select v-model="form.display" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                :disabled="isSubmitting"
              >
                <span v-if="isSubmitting">
                  <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                <span v-else>Submit News</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </layout>
  </template>
  
  <style scoped>
  </style>


