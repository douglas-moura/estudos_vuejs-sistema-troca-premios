<template>
    <div class="formLogin entrar">
        <TituloComp tituloTexto="Faça Login" />
        <AlertaBloco v-show="this.error_form.tipo != null" v-if="!email_user && error_form.tipo != null" :classeBloco=this.error_form.tipo :alerta=this.error_form.msg />
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
                    :inputType=inputSenhaTipo
                    inputName="password"
                    placeCampo="************" 
                    v-model="senha_user"
                    @trocar-input="senhatipo"
                />
            </div>
            <button type="submit" class="btnPrim">Entrar</button>
            <br>
            <p>Não está cadastrado? <a class="link" @click="$emit('mudarForm')">Cadastre-se aqui</a></p>
        </form>
    </div>
</template>

<script>
    import TituloComp from './layout/TituloComp.vue';
    import AlertaBloco from './layout/AlertaBloco.vue';
    import InputCampo from './layout/InputCampo.vue';
    import { useCookies } from 'vue3-cookies';
    import api from '../services/api';
    import $ from 'jquery'

    export default {
        name: 'FormLogin',
        setup() {
            const { cookies } = useCookies();
            return { cookies };
        },
        beforeCreate() {
            // faz o index.php buscar os usuários no DB e colocar em db.json
            $.ajax({
                method: 'POST',
                url: 'http://localhost/VueJS/5_vue_proj/src/services/backend/db.php',
                success: () => {
                    // Após o sucesso de atualização do banco de dados, busca os usuários
                    this.buscarUsuarios()
                }
            })
        },
        data() {
            return {
                email_user: '',
                senha_user: '',
                error_form: {
                    tipo: null,
                    msg: null
                },
                inputSenhaTipo: 'password',
                usuariosBanco: [],
                userLogado: []
            }
        },
        methods: {
            async login() {
                // pega o resultado que esta em RESPOSTA e coloca em JSON (Array de OBJETOS)
                const usuarios = await this.usuariosBanco
                const results = []
                
                // valida o usuario e senha nos dados que vem do JSON
                for (const key in usuarios) {
                    if(`${usuarios[key]['email']}` == this.email_user &&`${usuarios[key]['senha']}` == this.senha_user) {
                        results.push(true)
                        this.userLogado = usuarios[key]
                    } else {
                        results.push(false)
                    }
                }

                // caso haja o email entre os usuarios, realiza o login, caso contratio, exibe erro e limpa o form
                if (results.includes(true)) {
                    this.$router.push({name:'home'})
                    this.userLogado.senha = '******'
                    sessionStorage.setItem('authUser', JSON.stringify(this.userLogado))
                    this.pegarUser()
                } else {
                    this.error_form = {
                        tipo: 'erro',
                        msg: 'E-mail ou senha incorretos'
                    }
                    // limpa o formulario
                    this.email_user = '';
                    this.senha_user = '';
                }
            },
            senhatipo() {
                if(this.inputSenhaTipo == 'password') {
                    return this.inputSenhaTipo = 'text'
                } else {
                    return this.inputSenhaTipo = 'password'
                }
            },
            pegarUser() {
                // emite para o App
                this.$emitter.emit('pegar-user')
            },

            async buscarUsuarios() {
                try {
                    // Faz a chamada para obter os usuários do DB
                    const response = await api.get('usuarios');
                    
                    // Atualiza this.usuariosBanco com os usuários obtidos
                    this.usuariosBanco = response.data;

                    // Exibe os usuários após a atualização
                    console.log('Usuários atualizados:', this.usuariosBanco);
                } catch (error) {
                    console.error('Erro ao obter usuários:', error);
                    // Lógica de tratamento de erro
                }
            }
        },
        emits: [
            'mudarForm',
        ],
        components: {
            TituloComp,
            InputCampo,
            AlertaBloco
        }
    }
</script>

<style lang="scss" scoped>
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