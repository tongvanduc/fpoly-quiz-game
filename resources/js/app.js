import Alpine from 'alpinejs';

import io from 'socket.io-client';
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    client: io,
});

window.io = io;
