class EventBusEvent extends Event {
    constructor(pData) {
        let {type, data} = pData
        super(type)
        this.data = data
    }
}

class EventBus extends EventTarget {
    static _instance

    static getInstance() {
        if (!this._instance) this._instance = new EventBus()
        return this._instance
    }

    emit(type, data) {
        this.dispatchEvent(new EventBusEvent({type, data}))
    }
}

export default EventBus.getInstance()