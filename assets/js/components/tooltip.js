import Commons from "./commons.min.js"

class Tooltip extends Commons {

    constructor() {
        super();
        this.init();
        this.timeouts = {};
    }

    init() {
        let _thisClass = this

        this.waitForElementToDisplay(".tooltip-container", function () {
            setTimeout(function () {
                let tooltipContainers = document.querySelectorAll(".tooltip-container");
                let i = 0;
                tooltipContainers.forEach(function (tooltipContainer) {
                    tooltipContainer.addEventListener("click", function () {
                        if (tooltipContainer.classList.contains("active")) {
                            tooltipContainer.classList.remove("active");
                        } else {
                            let timeout = this.timeouts?.[i];
                            if (timeout) {
                                clearTimeout(timeout);
                            }

                            tooltipContainer.classList.add("active");

                            this.timeouts[i] = setTimeout(function () {
                                tooltipContainer.classList.remove("active");
                            }, 5000);
                        }

                        i++;
                    })

                    _thisClass.addClickOutsideListener(tooltipContainer, function () {
                        if (tooltipContainer.classList.contains("active")) {
                            tooltipContainer.classList.remove("active");
                        }
                    })
                })
            }, 200)
        });
    }
}

new Tooltip()

export {}
