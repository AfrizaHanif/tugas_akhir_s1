<script>
    (function() {
        "use strict";

        const select = (el, all = false) => {
            el = el.trim()
            if (all) {
                return [...document.querySelectorAll(el)]
            } else {
                return document.querySelector(el)
            }
        }

        /**
         * Preloader
         */
        const removePreloader = () => preloader.remove();
        let preloader = select(".preloader");
        if (preloader) {
            window.addEventListener("load", () => setTimeout(removePreloader, 400));
        }
    })()
</script>
