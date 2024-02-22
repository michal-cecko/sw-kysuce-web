import Commons from "./commons.js"

export default class extends Commons {
    constructor() {
        super();

        this.skeletonClasses = ['preloader--display'];
    }

    loaded() {
        this.skeletonClasses.forEach((skeletonClass) => {
            document.querySelectorAll(`.${skeletonClass}`).forEach(skeletonNode => {
                skeletonNode.classList.remove(skeletonClass)
            })
        })
    }
}
