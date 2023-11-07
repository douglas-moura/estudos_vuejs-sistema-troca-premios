<template>
    <div class="participante-box bloco">
        <picture>
            Teste
            <img src="" alt="">
        </picture>
        <div>
            <h3>Nome Participante</h3>
            <p>Desempenho: 152%</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'BoxParticipante'
    }
</script>

<style lang="scss" scoped>
    .participante-box {
        display: flex;
        width: calc(95% / 5);
        background-color: $corPrim_0;

        picture {
            background-color: white;
            aspect-ratio: 1 / 1;
            width: 40%;
            margin-right: 1rem;
        }
    }
</style>