<template>
    <MenuLateral v-if="notLogado" />
    <TopBar v-if="notLogado" />
	<router-view id="conteudo" class="conteudo" />
</template>

<script>
    import MenuLateral from './components/MenuLateral.vue'
    import TopBar from './components/TopBar.vue';

    export default {
        components: {
            MenuLateral,
            TopBar
        },
        computed: {
            notLogado() {
                return this.$route.name !== 'login'
            }
        }
    }
</script>

<style lang="scss">
    .conteudo {
        box-sizing: border-box;
        width: calc(85% - 3rem);
        margin: 1.5rem;
        position: relative;
        float: right;
        background-color: $cinza_1;
        padding: 2rem;
        border-radius: .5rem;
    }
</style>
