import { toast } from 'vue3-toastify';

export function showToast(type, message) {
  const options = {
    success: { style: { color: '#4CAF50' } },
    error: { style:  {color: '#F44336' } },
    warning: { style: { color: '#FFA726' } },
  };

  toast[type](message, options[type]);
}

export function handleAxiosError(error, defaultMessage = 'An unexpected error occurred') {
  if (error.response && error.response.status === 400) {
    showToast('warning', 'Validation failed. Please check the input.');
  } else {
    showToast('error', defaultMessage);
  }
}


const getErrorMessage = (res) =>{
  return JSON.parse(res.requests)
}