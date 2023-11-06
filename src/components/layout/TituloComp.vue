<template>
    <h1>{{ tituloTexto }}</h1>
</template>

<script>
    export default {
        name: 'TituloComp',
        props: {
            tituloTexto: String
        }
    }
</script>

<style lang="scss">
    h1 {
        font-size: 2rem;
        margin-bottom: 1.5rem !important;
        color: $corPrim;
    }
</style>