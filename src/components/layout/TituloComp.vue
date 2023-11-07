<template>
    <h1 class="titulo-pagina">{{ tituloTexto }}</h1>
</template>

<script>
    export default {
        name: 'TituloComp',
        props: {
            tituloTexto: String
        }
    }
</script>

<style lang="scss" scoped>   
    .titulo-pagina {
        font-size: 2rem;
        margin: 0 0 1.5rem 0 !important;
        color: $preto !important;
    }
</style>