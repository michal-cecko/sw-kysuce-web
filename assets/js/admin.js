import Commons from "./components/commons.js";

class Admin extends Commons {
    constructor() {
        super()
        console.log("Admin JS has been loaded.")
        //Functions
        this._prepareSubmittedFormRemoval()
        this._prepareExportBtn()
    }

    _prepareSubmittedFormRemoval() {
        let __this = this


        let deleteIcons = document.querySelectorAll('.remove-submitted-row')
        if (deleteIcons.length) {
            deleteIcons.forEach(icon => {
                icon.addEventListener("click", () => {
                    let id = icon.dataset.id
                    let formID = icon.dataset.form_id
                    if (!id || !formID) return

                    deleteForm(id, formID);
                })
            })
        }

        async function deleteForm(id, formID) {

            console.log(__this.ajaxURL)

            if (!confirm('Naozaj chcete odstrániť túto registráciu?')) return

            //Data
            let data = new FormData();
            data.append("id", id)
            data.append("form_id", formID)
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

    _prepareExportBtn() {
        let _this = this;
        let params = this.getCurrentURLParams();
        console.log(params);
        this.waitForElementToDisplay("#sw-submitted_forms", function () {
            let btn = document.createElement('a');
            btn.href = _this.ajaxURL + "?action=export_submissions&form_id=" + params['post'];
            btn.target = "_blank";
            btn.classList.add("button", "button-primary", "button-large")
            btn.style.marginRight = "8px"
            btn.innerHTML = 'Export';

            document.querySelector("#sw-submitted_forms .postbox-header").appendChild(btn);
        }, 9000, 10)
    }

}

new Admin

export {};