import Commons from "./components/commons.js";

console.log("General JS has been loaded.")

class General extends Commons{
    constructor() {
        super()

        this._prepareSwipers()
        this._prepareSlicks()
    }

    _prepareSwipers() {
        if(document.querySelector(".swiper-latest-events")) {
            const swiperLatestEvents = new Swiper('.swiper-latest-events', {
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
            });
        }
    }

    _prepareSlicks() {
        jQuery(document).ready(function () {
            let slider = jQuery('.slick-highlights');
            let prevArrow = document.querySelector('#blogHighlights .slick-arrow.prev');
            let nextArrow = document.querySelector('#blogHighlights .slick-arrow.next');
            if(slider.length) {
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
}

new General()

export {}