import axios from 'axios';
window.axios = axios;

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const options = {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
};

window.Echo = new Echo(options as any);

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
