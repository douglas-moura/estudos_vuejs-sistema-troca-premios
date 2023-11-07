<template>
    <nav>
        <LogoTipo estilo="claro" />
        <div class="links">
            <router-link to="/"><mdicon name="HomeVariantOutline" size="25" />Início</router-link>
            <router-link to="/premios"><mdicon name="GiftOpenOutline" size="25" />Prêmios</router-link>
            <router-link to="/pedidos"><mdicon name="CartOutline" size="25" />Meus Pedidos</router-link>
            <!--<router-link to="/pontos"><mdicon name="StarOutline" size="25" />Pontuação</router-link> -->
            <router-link to="/desempenho"><mdicon name="Finance" size="25" />Desempenho</router-link> 
            <router-link to="" @click="logOut"><mdicon name="LogoutVariant" size="25" />Sair</router-link>
        </div>
        <div class="links_suporte">
            <h3>Ajuda</h3>
            <router-link to="/"><mdicon name="AccountMultipleOutline" size="25" />Suporte</router-link>
            <router-link to="/"><mdicon name="HelpCircleOutline" size="25" />FAQ</router-link>
        </div>
    </nav>
</template>

<script>
    import LogoTipo from './layout/LogoTipo.vue'

    export default {
        name: 'MenuLateral',
        data() {
            return {
                
            }
        },
        components: {
            LogoTipo
        },
        methods: {
            logOut() {
                sessionStorage.removeItem('authUser')
                this.$router.push({name:'login'})
            }
        }
    }
</script>

<style lang="scss">
    nav {
        box-sizing: border-box;
        z-index: 9997;
        background-color: $preto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        width: 15%;
        position: fixed;
        height: 100%;

        .links_suporte,
        .links {
            margin-bottom: 3rem;

            h3 {
                padding: .5rem;
                color: #fff;
                margin-bottom: 1rem !important;
            }

            a {
                color: #fff;
                text-decoration: none;
                display: flex;
                align-items: center;
                margin: .25rem 0;
                padding: .5rem;
                border-radius: .5rem;

                &:hover {
                    background-color: $cinza_5;
                }

                svg {
                    margin-right: 2rem;
                    stroke-width: 1%;
                }
            }
        }
    }
</style>