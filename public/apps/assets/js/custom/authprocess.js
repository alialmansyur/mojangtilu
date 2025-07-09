$(document).ready(function () {
    $('#loginForm').on('submit', function (event) {
        event.preventDefault();
        let formData = new FormData(this);

        const fingerprint = {
            user_agent: navigator.userAgent,
            language: navigator.language || navigator.userLanguage,
            platform: navigator.platform,
            cpu_cores: navigator.hardwareConcurrency || null,
            device_memory: navigator.deviceMemory || null,
            screen_width: screen.width,
            screen_height: screen.height,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            touch_support: ('ontouchstart' in window) ? 1 : 0
        };

        formData.append('fingerprint', JSON.stringify(fingerprint));
        fetch( AppConfig.initGlobal + 'authprocess', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    localStorage.setItem('active_menu', 7);
                    localStorage.setItem('jwt_token', data.token);
                    window.location.href =  AppConfig.initGlobal + "home"
                } else {
                    let errorMessage = data.messages;
                    if (typeof errorMessage === 'object') {
                        errorMessage = Object.values(errorMessage).join('<br>');
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'error',
                        title: errorMessage,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            })
    });
});