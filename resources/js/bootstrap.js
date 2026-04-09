import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
