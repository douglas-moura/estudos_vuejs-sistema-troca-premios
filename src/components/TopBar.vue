<template>
    <header class="topbar">
        <router-link to="/" id="perfil"><mdicon name="AccountCircle" size="30" /></router-link>
        <router-link to="/"><mdicon name="Magnify" size="20" /></router-link>
        <router-link to="/"><mdicon name="Web" size="20" /></router-link>
        <router-link to="/"><mdicon name="MessageTextOutline" size="20" /></router-link>
    </header>
</template>

<script>
    export default {
        name: 'TopBar'
    }
</script>

<style lang="scss">
    .topbar {
        background-color: $cinza_1;
        padding: 1rem 2rem;
        text-align: right;
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        border-bottom: .05rem solid $cinza_2;

        #perfil {
            margin-left: 2rem;
        }

        a {
            color: $preto !important;
            margin-left: 1rem;
        }
    }
</style>