<template>
    <div class="foot">
        Projeto de Estudos em VueJs 3 e APIs - por <strong>Douglas Moura</strong> | 2023
    </div>
</template>

<script>
    export default {
        name: 'RodapeSite'
    }
</script>

<style lang="scss" scoped>
    .foot {
        box-sizing: border-box;
        width: calc(85% - 3rem);
        margin: 2rem 1.5rem;
        position: relative;
        text-align: center;
        color: $cinza_4;
        float: right;
    }
</style>