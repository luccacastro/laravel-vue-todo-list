<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import TaskForm from '@/Components/Task/TaskForm.vue';
import TaskList from '@/Components/Task/TaskList.vue';
import TaskPage from './TaskPage.vue';

</script>

<template>
    <AppLayout title="Task Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Task Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             
                <TaskPage/>
            </div>
        </div>
    </AppLayout>
</template>
