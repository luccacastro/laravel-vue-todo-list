<template>
  <div class="task-form bg-gray-300 p-6 rounded-lg shadow-md w-full max-w-2xl mx-auto flex flex-col space-y-4">
    <div v-if="notification" class="bg-red-200 text-red-700 p-2 rounded-lg mb-4">
      {{ notification }}
    </div>

    <div class="flex-1">
      <input
        type="text"
        v-model="title"
        placeholder="Task Title"
        class="w-full border-none bg-transparent text-4xl font-semibold placeholder-gray-700 mb-4 focus:outline-none focus:ring-0"
      />
      <textarea
        v-model="description"
        placeholder="Task Description"
        class="w-full border-none bg-transparent text-xl placeholder-gray-600 resize-none h-24 focus:outline-none focus:ring-0"
      ></textarea>
    </div>

    <div class="flex justify-end">
      <button
        @click="submitTask"
        class="text-2xl text-gray-600 font-bold rounded-lg transition duration-200 ease-in-out flex items-center justify-center w-12 h-12"
        :class="{ 'opacity-50 cursor-not-allowed': !title.trim() || !description.trim() }"
        :disabled="!title.trim() || !description.trim()"
      >
        <PlusIcon />
      </button>
    </div>
  </div>
</template>

<script>
import { PlusIcon } from '@heroicons/vue/solid';
import { mapActions, mapState } from 'vuex';

export default {
  components: {
    PlusIcon,
  },
  data() {
    return {
      title: '',
      description: '',
    };
  },
  computed: {
    ...mapState({
      notification: state => state.tasks.notification, 
    }),
  },
  methods: {
    ...mapActions(['createTask']),
    submitTask() {
      if (this.title.trim() && this.description.trim()) {
        this.createTask({ title: this.title, description: this.description })
          .then(() => {
            this.resetForm();
          });
      }
    },
    resetForm() {
      this.title = '';
      this.description = '';
    },
  },
};
</script>
