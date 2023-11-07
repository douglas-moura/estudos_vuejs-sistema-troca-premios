<template>
    <main class="cadastro-sucesso bloco">
        <mdicon name="CheckDecagramOutline" size="150" />
        <TituloComp tituloTexto="Cadastro realizado com sucesso!!" />
        <br>
        <a class="btnPrim" @click="$emit('cadastroSucesso')">Fazer login</a>
        <br><br>
        <LogoTipo
            estilo="transparente"
            icon_tam="20"
            text_tam=".8"
        />
    </main>
</template>

<script>
    import TituloComp from './layout/TituloComp.vue';
    import LogoTipo from './layout/LogoTipo.vue';
    import api from '../services/api';

    export default {
        name: 'CadastroSucesso',
        components: {
            TituloComp,
            LogoTipo
        },
        data() {
            return {
                users_cadastrados: null
            }
        },
        mounted() {
            api.get('usuarios').then(response => {
                console.log(response.data)
                this.users_cadastrados = response.data
            })
        },
        emits: [
            'cadastroSucesso'
        ]
    }
</script>

<style lang="scss" scoped>
    .cadastro-sucesso {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 100%;

        span {            
            color: #59AA1F;
        }

        a {
            cursor: pointer;
        }
    }
</style>