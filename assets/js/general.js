import Commons from "./components/commons.min.js";

console.log("General JS has been loaded.")

class General extends Commons {
    constructor() {
        super()

        this._prepareSwipers()
        this._prepareSlicks()
        this._prepareSmoothScrolling()
        this._prepareCopyToClipboard()
        this._prepareGutenbergGalleries()

        setTimeout(() => {
            refreshFsLightbox();
        }, 100)
    }

    _prepareSwipers() {

        this.createSwiper(".swiper-sponsors", {
            slidesPerView: 3.25,
            spaceBetween: 16,
            navigation: false,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: 4,
                    spaceBetween: 16,
                },
                1000: {
                    slidesPerView: 5,
                    spaceBetween: 24,
                },
                1400: {
                    slidesPerView: 6,
                    spaceBetween: 32,
                },
            }
        })

        this.createSwiper(".swiper-latest-events", {
            slidesPerView: 1.4,
            spaceBetween: 20,
            navigation: {
                nextEl: '.event-arrow.next',
                prevEl: '.event-arrow.prev'
            },
            breakpoints: {
                768: {
                    slidesPerView: 2.2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 2.7,
                    spaceBetween: 20,
                },
            }
        })
    }

    _prepareSlicks() {
        jQuery(document).ready(function () {
            let slider = jQuery('.slick-highlights');
            let prevArrow = document.querySelector('#blogHighlights .slick-arrow.prev');
            let nextArrow = document.querySelector('#blogHighlights .slick-arrow.next');
            if (slider.length) {
                const slickOptions = {
                    centerMode: true,
                    centerPadding: '30px',
                    slidesToShow: 3,
                    prevArrow: prevArrow,
                    nextArrow: nextArrow,
                    touchMove: false,
                    responsive: [
                        {
                            breakpoint: 900,
                            settings: {
                                touchMove: true,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                centerPadding: 0,
                                slidesToShow: 1,
                            }
                        }
                    ]
                };
                slider.slick(slickOptions);
            }
        })
    }

    _prepareCopyToClipboard() {
        let _thisClass = this

        function fallbackCopyTextToClipboard(text) {
            let textArea = document.createElement("textarea");
            textArea.value = text;

            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successful' : 'unsuccessful';
                console.log('Fallback: Copying text command was ' + msg);
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }

            document.body.removeChild(textArea);
        }

        function copyTextToClipboard(text) {
            if (!navigator.clipboard) {
                fallbackCopyTextToClipboard(text);
                return;
            }
            navigator.clipboard.writeText(text).then(function () {
                _thisClass.notify("Skopírované do schránky.")
            }, function (err) {
                _thisClass.notify("Nepodarilo sa skopírovať.", "error")
            });
        }

        let copyBtns = document.querySelectorAll(".js-copy_to_clipboard")
        copyBtns.forEach(btn => {
            btn.addEventListener("click", function () {
                let copiedText = btn.dataset.copy
                if (!_thisClass.empty(copiedText)) copyTextToClipboard(copiedText)
                else _thisClass.notify("Nepodarilo sa skopírovať.", "error")
            })
        })
    }

    _prepareSmoothScrolling() {
        let anchorlinks = document.querySelectorAll('a[href^="#"]')

        for (let item of anchorlinks) {
            console.log(item)
            item.addEventListener('click', (e) => {
                let hashval = item.getAttribute('href')
                if (!hashval.length) return;

                let target = document.querySelector(hashval)
                if (!target) return;

                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                })
                history.pushState(null, null, hashval)
                e.preventDefault()
            })
        }
    }


    _prepareGutenbergGalleries() {
        let galleries = document.querySelectorAll(".wp-block-image");

        galleries.forEach(gallery => {
            let images = gallery.querySelectorAll("img");
            if (images.length) {
                images.forEach(image => {
                    this.setImageLightbox(image, `gallery`)
                })
            }
        })
    }


    setImageLightbox(imageNode, galleryName) {
        let anchorElement = document.createElement('a');
        anchorElement.setAttribute('href', imageNode.src);
        anchorElement.setAttribute('data-fslightbox', galleryName);
        let clonedImg = imageNode.cloneNode(true);
        anchorElement.appendChild(clonedImg);
        imageNode.parentNode.replaceChild(anchorElement, imageNode);
    }


    createSwiper(selector, options) {
        let node = document.querySelector(selector);
        if (!node) return;

        const swiper = new Swiper(selector, options);
    }
}

new General()

export {}