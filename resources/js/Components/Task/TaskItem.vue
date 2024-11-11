<template>
  <div class="task-item bg-white p-4 rounded-lg shadow flex items-center justify-between">
    <span :class="{ 'text-gray-400 line-through': task.is_completed }" class="text-lg font-semibold text-gray-700">
      {{ task.title }}
    </span>

    <div class="flex space-x-2 items-center">
      <div
        :class="task.is_completed 
          ? 'bg-green-100 border-green-600 text-green-700' 
          : 'bg-yellow-100 border-yellow-600 text-yellow-700'"
        class="flex items-center space-x-2 px-2 py-1 rounded-sm border font-bold text-xs uppercase"
      >
        <component :is="task.is_completed ? CheckIcon : ExclamationIcon" class="w-3 h-3" />
        <span>{{ task.is_completed ? 'Completed' : 'Pending' }}</span>
      </div>

      <button
        v-if="!task.is_completed"
        @click="$emit('markAsComplete', task.id)"
        class="text-gray-600 hover:bg-gray-100 rounded-full p-2 transition duration-200 ease-in-out"
        title="Mark as Completed"
      >
        <CheckIcon class="w-5 h-5" />
      </button>

      <button
        @click="$emit('taskDeleted', task.id)"
        class="text-red-600 hover:bg-red-100 rounded-full p-2 transition duration-200 ease-in-out"
        title="Delete Task"
      >
        <TrashIcon class="w-5 h-5" />
      </button>
    </div>
  </div>
</template>

<script>
import { CheckIcon, ExclamationIcon, TrashIcon } from '@heroicons/vue/solid';

export default {
  props: {
    task: {
      type: Object,
      required: true,
    },
  },
  components: {
    CheckIcon,
    ExclamationIcon,
    TrashIcon,
  },
};
</script>
