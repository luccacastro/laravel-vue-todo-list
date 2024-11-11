import { createStore } from 'vuex';
import axios from 'axios';
import { handleAxiosError, showToast } from '../utils/notifications';

export default createStore({
  state: {
    tasks: [],
  },
  mutations: {
    setTasks(state, tasks) {
      state.tasks = tasks;
    },
  },
  actions: {
    fetchTasks({ commit }) {
      return axios.get('/tasks')
        .then(response => {
          commit('setTasks', response.data);
          return response.data;
        })
        .catch(error => handleAxiosError(error, error.response.data.error));
    },
    createTask({ dispatch }, taskData) {
      return axios.post('/tasks', taskData)
        .then((res) => {
          dispatch('fetchTasks');
          console.log(res)
          showToast('success', res.data.message);
        })
        .catch(error => handleAxiosError(error, error.response.data.error));
    },
    deleteTask({ dispatch }, taskId) {
      return axios.delete(`/tasks/${taskId}`)
        .then(() => {
          dispatch('fetchTasks'); 
          showToast('success', 'Task deleted successfully!');
        })
        .catch((error) =>{ 
          handleAxiosError(error, error.response.data.error)
        });
    },
    markAsComplete({ dispatch }, taskId) {
      return axios.patch(`/tasks/${taskId}/complete`, { is_completed: true })
        .then((res) => {
          dispatch('fetchTasks');
          showToast('success', res.data.message);
        })
        .catch(error => handleAxiosError(error, error.response.data.error));
    },
  },
  getters: {
    tasks: state => state.tasks,
  },
});
