import mitt from 'mitt'
const emitter = mitt()

export default {
    install: (app) => {
        // Adicione o objeto emitter ao contexto global do aplicativo Vue
        app.config.globalProperties.$emitter = emitter;
    }
}