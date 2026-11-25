document.addEventListener('alpine:init', () => {
    const initialDir = document.documentElement.getAttribute('data-kalles-dir') || 'ltr';
    const bootstrapLtr = document.documentElement.getAttribute('data-bootstrap-ltr') || '';
    const bootstrapRtl = document.documentElement.getAttribute('data-bootstrap-rtl') || '';
    const appLtr = document.documentElement.getAttribute('data-app-ltr') || '';
    const appRtl = document.documentElement.getAttribute('data-app-rtl') || '';

    Alpine.store('appStore', {
        dir: initialDir,

        toggleDir() {
            // Direction is owned by Laravel locale switching; no client toggle.
        },

        handleUrl() {
            const bootstrapLinkEle = document.getElementById('bootstrap-style');
            const appLinkEle = document.getElementById('app-style');

            if (!bootstrapLinkEle || !appLinkEle) {
                return;
            }

            if (this.dir === 'rtl') {
                bootstrapLinkEle.setAttribute('href', bootstrapRtl);
                appLinkEle.setAttribute('href', appRtl);
            } else {
                bootstrapLinkEle.setAttribute('href', bootstrapLtr);
                appLinkEle.setAttribute('href', appLtr);
            }
        },
    });

    Alpine.store('appStore').handleUrl();
});
