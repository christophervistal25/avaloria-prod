<script setup>
import { defineComponent, ref, onMounted } from 'vue'
import Layout from '@layouts/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { Bold, ClassicEditor, Essentials, Heading, Italic, Paragraph, Undo } from 'ckeditor5'
import CKEditor from '@ckeditor/ckeditor5-vue'
import 'ckeditor5/ckeditor5.css'

const props = defineProps({
  wikipedia: {
    type: Object,
    required: true
  }
})

defineComponent({
  ckeditor: CKEditor.component
})

const ckEditorConfig = {
  editor: ClassicEditor,
  editorData: props.wikipedia.description || '',
  editorConfig: {
    plugins: [Bold, Essentials, Italic, Paragraph, Undo, Heading],
    toolbar: ['undo', 'redo', '|', 'bold', 'italic', '|', 'heading'],
  }
}

// Initialize itemEditors with existing data
const itemEditors = ref(
  props.wikipedia.items.map(item => ({
    editorData: item.description || ''
  }))
)

const form = useForm({
  title: props.wikipedia.title,
  description: props.wikipedia.description,
  image: null,
  _method: 'PUT',
  is_published: props.wikipedia.is_published,
  items: props.wikipedia.items.map(item => ({
    title: item.title,
    description: item.description
  }))
})

const addItem = () => {
  form.items.push({
    title: '',
    description: ''
  })
  itemEditors.value.push({ editorData: '' })
}

const removeItem = (index) => {
  if (form.items.length > 1) {
    form.items.splice(index, 1)
    itemEditors.value.splice(index, 1)
  }
}

const submit = () => {
  // Update main description
  form.description = ckEditorConfig.editorData
  
  // Update each item's description from its editor
  form.items = form.items.map((item, index) => ({
    ...item,
    description: itemEditors.value[index].editorData
  }))

  form.post(`/administrator-panel/wikipedia/${props.wikipedia.id}`)
}
</script>

<template>
  <Layout>
    <div class="min-h-screen py-8">
      <div class="">
        <!-- Enhanced Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
          <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl flex items-center">
              <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              Edit Wikipedia Entry
            </h1>
            <p class="mt-2 text-sm text-gray-600">Update your existing wiki documentation.</p>
          </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <form @submit.prevent="submit" class="divide-y divide-gray-200">
            <!-- Main Information Section -->
            <div class="p-6 space-y-8">
              <div class="grid grid-cols-1 lg:grid-cols-1">
                <div class="space-y-6">
                  <!-- Title Input -->
                  <div>
                    <label class="block text-sm font-semibold text-gray-700">Entry Title</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <input
                        v-model="form.title"
                        type="text"
                        class="block w-full pr-10 pl-4 py-2.5 border-gray-300 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 
                               hover:border-blue-400 transition-colors duration-200
                               placeholder-gray-400 text-gray-900 
                               shadow-sm sm:text-sm"
                        placeholder="Enter title"
                        required
                      />
                      <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                      </div>
                    </div>
                    <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</div>
                  </div>

                  <!-- Image Upload -->
                  <div class="w-full">
                    <label class="block text-sm font-semibold text-gray-700">
                      Featured Image
                      <span v-if="props.wikipedia.image" class="text-sm text-gray-500">
                        (Leave empty to keep current image)
                      </span>
                    </label>
                    <div class="mt-1 flex justify-center w-full px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                      <div class="space-y-1 text-center w-full">
                        <img v-if="props.wikipedia.image" 
                             :src="`/storage/${props.wikipedia.image}`" 
                             class="mx-auto h-32 w-auto object-cover rounded-md mb-4"
                             alt="Current image"/>
                        <svg v-else class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                          <label class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Upload a new file</span>
                            <input type="file" @input="form.image = $event.target.files[0]" accept="image/*" class="sr-only">
                          </label>
                          <p class="pl-1">or keep existing</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF</p>
                      </div>
                    </div>
                    <div v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</div>
                  </div>

                  <!-- Published Status -->
                  <div class="flex items-center space-x-3">
                    <button type="button" 
                            @click="form.is_published = !form.is_published"
                            :class="[form.is_published ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']">
                      <span :class="[form.is_published ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-900">Published</span>
                  </div>
                </div>
              </div>

              <!-- Description Editor -->
              <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">Description</label>
                <div class="border rounded-lg overflow-hidden">
                  <ckeditor
                    :editor="ckEditorConfig.editor"
                    v-model="ckEditorConfig.editorData"
                    :config="ckEditorConfig.editorConfig"
                    class="prose max-w-none"
                  />
                </div>
                <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</div>
              </div>
            </div>

            <!-- Items Section -->
            <div class="p-6 space-y-6">
              <div class="flex justify-between items-center">
                <div>
                  <h2 class="text-lg font-medium text-gray-900">Items</h2>
                  <p class="text-sm text-gray-500">Manage related items for this wiki entry.</p>
                </div>
                <button
                  type="button"
                  @click="addItem"
                  class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                  Add Item
                </button>
              </div>

              <!-- Item Cards -->
              <div class="space-y-4">
                <div v-for="(item, index) in form.items" :key="index" 
                     class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                  <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-semibold text-gray-900">Item {{ index + 1 }}</h3>
                    <button
                      type="button"
                      @click="removeItem(index)"
                      class="inline-flex items-center p-1.5 border border-transparent rounded-full text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                      :disabled="form.items.length === 1"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </div>

                  <div class="grid grid-cols-1 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Item Title</label>
                      <div class="mt-1 relative rounded-md shadow-sm">
                        <input
                          v-model="item.title"
                          type="text"
                          class="block w-full pr-10 pl-4 py-2.5 border-gray-300 rounded-lg 
                                 focus:ring-blue-500 focus:border-blue-500 
                                 hover:border-blue-400 transition-colors duration-200
                                 placeholder-gray-400 text-gray-900 
                                 shadow-sm sm:text-sm"
                          placeholder="Enter item title"
                          required
                        />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                          <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                          </svg>
                        </div>
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700">Item Description</label>
                      <div class="mt-1 border rounded-lg overflow-hidden">
                        <ckeditor
                          :editor="ckEditorConfig.editor"
                          v-model="itemEditors[index].editorData"
                          :config="ckEditorConfig.editorConfig"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-3">
              <Link
                href="/administrator-panel/wikipedia"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Cancel
              </Link>
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <span>{{ form.processing ? 'Updating...' : 'Update Entry' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Layout>
</template>