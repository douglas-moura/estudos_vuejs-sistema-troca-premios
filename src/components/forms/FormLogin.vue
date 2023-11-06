<template>
    <div class="formLogin entrar">
        <TituloComp tituloTexto="Faça Login" />
        <form @submit.prevent="login">
            <div class="linhaForm">
                <InputCampo
                    largura=100
                    labelText="E-mail"
                    inputType="text"
                    inputName="email"
                    placeCampo="seuemail@email.com.br"
                    v-model="email_user"
                />
            </div>
            <div class="linhaForm">
                <InputCampo
                    largura=100
                    labelText="Senha"
                    inputType="password"
                    inputName="password"
                    placeCampo="************" 
                    v-model="senha_user"
                />
            </div>
            <button type="submit" class="btnPrim">Entrar</button>
            <br>
            <p>Não está cadastrado? <a class="link" @click="$emit('mudarForm')">Cadastre-se aqui</a></p>
        </form>
    </div>
</template>

<script>
    import TituloComp from '../layout/TituloComp.vue';
    import InputCampo from './InputCampo.vue';

    export default {
        name: 'FormLogin',
        data() {
            return {
                email_user: '',
                senha_user: '',
                backend: 'https://jsonplaceholder.typicode.com/users'
            }
        },
        methods: {
            async login() {
                // busca os dados da url(backend) da API com o FETCH e coloca na variavel RESPOSTA
                const resposta = await fetch(this.backend)

                // pega o resultado que esta em resposta e coloca em JSON (Array de OBJETOS)
                const usuarios = await resposta.json()
                
                usuarios.map((user) => {
                    if(user.email == this.email_user && user.address.city == this.senha_user) {
                        window.location.href = window.location.origin
                    }
                })
            }
        },
        emits: [
            'mudarForm'
        ],
        components: {
            TituloComp,
            InputCampo
        }
    }
</script>

<style lang="scss">
    .entrar {
        animation-name: voltar;
        animation-duration: .5s;

        @keyframes voltar {
            from {
                opacity: 0;
                margin-right: 200px;
                margin-left: -200px;
            }

            to {
                opacity: 1;
                margin-right: 0;
                margin-left: 0;
            }
        }
    }
</style>