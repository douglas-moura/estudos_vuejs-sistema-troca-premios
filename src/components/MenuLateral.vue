<template>
    <nav>
        <div id="projetoTitulo">
            <mdicon name="YoutubeGaming" size="30" />
            <h3>Projeto VueJS</h3> 
        </div>
        <div class="links">
            <router-link to="/"><mdicon name="HomeVariantOutline" size="25" />Home</router-link>
            <router-link to="/"><mdicon name="GiftOpenOutline" size="25" />Prêmios</router-link> 
            <router-link to="/"><mdicon name="StarOutline" size="25" />Pontos</router-link> 
            <router-link to="/"><mdicon name="CartOutline" size="25" />Meus Pedidos</router-link> 
            <router-link to="/"><mdicon name="AccountOutline" size="25" />Perfil</router-link> 
            <router-link to="/"><mdicon name="BellBadgeOutline" size="25" />Notificações</router-link>    
            <router-link to="/login"><mdicon name="LogoutVariant" size="25" />Sair</router-link>
        </div>
        <div class="links_suporte">
            <h3>Ajuda</h3>
            <router-link to="/"><mdicon name="CogOutline" size="25" />Configurações</router-link>
            <router-link to="/"><mdicon name="AccountMultipleOutline" size="25" />Suporte</router-link>
        </div>
    </nav>
</template>

<script>

</script>

<style lang="scss">
    nav {
        box-sizing: border-box;
        background-color: $preto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        width: 15%;
        position: fixed;
        height: 100%;

        #projetoTitulo {
            display: flex;
            align-items: center;
            margin: 2rem .5rem;
            color: $corPrim;

            svg {
                background-color: $corPrim;
                fill: #fff;
                padding: .5rem;
                border-radius: 50px;
                margin-right: 1rem;
            }
        }

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