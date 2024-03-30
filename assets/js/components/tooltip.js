import Commons from "./commons.min.js"

class Tooltip extends Commons {

    constructor() {
        super();
        this.init();
    }

    init() {
        this.waitForElementToDisplay(".tooltip-container", function () {
            setTimeout(function () {
                let tooltipContainers = document.querySelectorAll(".tooltip-container");
                tooltipContainers.forEach(function (tooltipContainer) {
                    tooltipContainer.addEventListener("click", function () {
                        tooltipContainer.classList.toggle("active");
                    })
                })
            }, 200)
        });
    }
}

new Tooltip()

export {}
