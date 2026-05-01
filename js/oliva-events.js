(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var container = document.getElementById('oliva-events');

        if (!container) {
            return;
        }

        container.classList.add('oliva-events-loaded');
    });
}());
