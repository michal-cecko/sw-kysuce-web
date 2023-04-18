import Commons from "./components/commons";

class Admin extends Commons {
    constructor() {
        super()
        console.log("Admin JS has been loaded.")
        //Functions
        this._prepareSubmittedFormRemoval()
    }

    // Submitted form delete ---- START

    async _prepareSubmittedFormRemoval() {
        let __this = this

        let deleteIcons = document.querySelectorAll('.remove-submitted-row')
        if (deleteIcons.length) {
            deleteIcons.forEach(icon => {
                icon.addEventListener("click", () => {
                    let id = icon.dataset.id
                    if (!id) return

                    deleteForm(id);
                })
            })
        }

        async function deleteForm(id) {

            console.log(__this.ajaxURL)

            if (!confirm('Naozaj chcete odstrániť túto registráciu?')) return

            //Data
            let data = new FormData();
            data.append("id", id)
            data.append("action", "delete_form")
            data.append("nonce", __this.nonce)

            //fetch
            await fetch(__this.ajaxURL, {
                method: 'POST',
                credentials: 'same-origin',
                body: data,
            })
            .then((response) => {
                let data = response.json()
                console.log(data)

                let row = document.querySelector("[data-id='" + id + "']")
                if (row) row.closest(".row")?.remove()
            })
            .catch((error) => {
                console.error(error);
            });
        }
    }

    // Submitted form delete ---- END
}

new Admin

export {};