<template>
  <div class="mt-10 task-list bg-gray-200 p-6 rounded-lg shadow-md w-full max-w-2xl mx-auto">
    <h2 class="mt-10 text-2xl font-bold mb-4 text-center">Your Tasks</h2>
    <div v-if="tasks.length > 0" class="space-y-4">
      <TaskItem
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        @mark-as-complete="markAsComplete"
        @task-deleted="deleteTask"
      />
    </div>
    <p v-else class="text-gray-500 text-center">No tasks available</p>
  </div>
</template>

<script>
import TaskItem from './TaskItem.vue';
import { mapActions, mapGetters } from 'vuex';

export default {
  components: { TaskItem },
  computed: {
    ...mapGetters(['tasks']),
  },
  methods: {
    ...mapActions(['markAsComplete', 'deleteTask']),
  },
};
</script>