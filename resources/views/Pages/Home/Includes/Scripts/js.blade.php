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
         * NOTE: Change setTimeout to set delay timer after load complete. Or remove timer for
         * instant without delay
         */
        const removePreloader = () => preloader.remove();
        let preloader = select(".preloader");
        if (preloader) {
            window.addEventListener("load", () => setTimeout(removePreloader, 400));
        }
    })()
</script>
