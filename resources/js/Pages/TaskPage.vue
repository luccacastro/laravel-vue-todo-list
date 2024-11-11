<template>
    <div>
      <TaskForm />
      <TaskList />
    </div>
  </template>
  
  <script>
  import TaskForm from '@/Components/Task/TaskForm.vue';
  import TaskList from '@/Components/Task/TaskList.vue';
  import { mapActions } from 'vuex';
  import { showToast } from '../utils/notifications';
  
  export default {
    components: { TaskForm, TaskList },
    methods: {
      ...mapActions(['fetchTasks']),
    },
    mounted() {
      this.fetchTasks().then(() => showToast('success', 'Tasks loaded successfully!'));
    },
  };
  </script>
  