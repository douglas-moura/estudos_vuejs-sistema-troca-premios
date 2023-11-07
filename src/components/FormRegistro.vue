<template>
    <div class="formLogin registro">
        <TituloComp tituloTexto="Registre-se" />
        <AlertaBloco v-show="this.error_form.tipo != null" :classeBloco=this.error_form.tipo :alerta=this.error_form.msg />
        <form @submit.prevent="cadastrar">
            <div class="linhaForm">
                <div style="width: 38%">
                    <InputCampo
                        labelText="Nome"
                        inputType="text"
                        inputName="fname"
                        placeCampo="José"
                        v-model="v$.fname.$model"
                    />
                    <!-- Validação -->
                    <div class="input-errors" v-for="error of v$.fname.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <div style="width: 58%">
                    <InputCampo
                        largura=58
                        labelText="Sobrenome"
                        inputType="text"
                        inputName="lname"
                        placeCampo="Souza" 
                        v-model="v$.lname.$model"
                    />
                    <!-- Validação -->
                    <div class="input-errors" v-for="error of v$.lname.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <div class="linhaForm">
                <div style="width: 100%">
                    <InputCampo
                        labelText="E-mail"
                        inputType="email"
                        inputName="email"
                        placeCampo="seuemail@email.com.br" 
                        v-model="v$.email.$model"
                    />
                    <!-- Validação -->
                    <div class="input-errors" v-for="error of v$.email.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <div class="linhaForm">
                <div style="width: 100%">
                    <InputCampo
                        labelText="Telefone"
                        inputType="numeric"
                        inputName="tel"
                        placeCampo="(__) _____-____" 
                        v-model="v$.tel.$model"
                        mascara="(##) #####-####"
                    />
                    <!-- Validação -->
                    <div class="input-errors" v-for="error of v$.tel.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <div class="linhaForm">
                <div style="width: 48%">
                    <InputCampo
                        labelText="Senha"
                        inputType="password"
                        inputName="a_password"
                        placeCampo="********" 
                        v-model="v$.a_password.$model"
                    />
                    <!-- Validação -->
                    <div class="input-errors" v-for="error of v$.a_password.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <div style="width: 48%">
                    <InputCampo
                        labelText="Confirme"
                        inputType="password"
                        inputName="c_password"
                        placeCampo="********" 
                        v-model="v$.c_password.$model"
                    />
                    <!-- Validação -->
                    <div class="input-errors" v-for="error of v$.c_password.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btnPrim">Cadastrar</button>
            <br>
            <a class="link" @click="$emit('mudarForm')"><mdicon name="ArrowLeft" size="15" /> Voltar</a>
        </form>
    </div>
</template>

<script>
    import { required, minLength, email, numeric, sameAs, helpers } from '@vuelidate/validators'
    import TituloComp from './layout/TituloComp.vue'
    import InputCampo from './layout/InputCampo.vue'
    import AlertaBloco from './layout/AlertaBloco.vue'
    import { useVuelidate } from '@vuelidate/core'
    import { useCookies } from 'vue3-cookies';
    import api from '../services/api';
    import $ from 'jquery'

    export default {
        name: 'FormRegistro',
        components: {
            TituloComp,
            InputCampo,
            AlertaBloco
        },
        emits: [
            'mudarForm',
            'cadastroSucesso'
        ],
        setup() {
            const { cookies } = useCookies();
            return { cookies, v$: useVuelidate() };
        },
        data() {
            return {
                fname: '',
                lname: '',
                email: '',
                tel: '',
                a_password: '',
                c_password: '',
                error_form: {
                    tipo: null,
                    msg: null
                },
                users_cadastrados: null
            }
        },
        validations() {
            return {
                fname: {
                    required: helpers.withMessage('Campo obrigatório', required)
                },
                lname: {
                    required: helpers.withMessage('Campo obrigatório', required)
                },
                email: {
                    required: helpers.withMessage('Campo obrigatório', required),
                    email: helpers.withMessage('O valor não tem um formato de e-mail válido', email)
                },
                tel: {
                    numeric: helpers.withMessage('', numeric),
                    minLength: helpers.withMessage('', minLength(11))
                },
                a_password: {
                    required: helpers.withMessage('Campo obrigatório', required),
                    minLength: helpers.withMessage('Mínimo de 6 caracteres', minLength(6))
                },
                c_password: {
                    required: helpers.withMessage('', required),
                    sameAsPassword: helpers.withMessage('As senhas devem ser iguais', sameAs(this.a_password))
                },
            }
        },
        methods: {
            cadastrar() {
                let user_existente = null
                // dados do cadastro em OBJ
                const formData = {
                    nome: this.fname,
                    sobrenome: this.lname,
                    email: this.email,
                    telefone: this.tel.replace(/\D/g,''),
                    senha: this.a_password
                }

                let usuario_novo = JSON.stringify(formData)

                for(const key in this.users_cadastrados) {
                    if(this.users_cadastrados[key].email == formData.email) {
                        user_existente = true
                    }
                }

                if(user_existente != true) {
                    $.ajax({
                        method: 'POST',
                        url: 'http://localhost/VueJS/5_vue_proj/src/services/backend/insert.php',
                        data: {
                            tabela: 'usuarios',
                            data: usuario_novo
                        }
                    })
                    this.$emit('cadastroSucesso')
                } else {                    
                    this.error_form = {
                        tipo: 'erro',
                        msg: 'E-mail já cadastrado'
                    }
                }
            }
        },
        mounted() {
            api.get('usuarios').then(response => {
                console.log(response.data)
                this.users_cadastrados = response.data
            })
        }
    }
</script>

<style lang="scss" scoped>
    .registro {
        animation-name: entrada;
        animation-duration: .5s;

        .input-errors {
            margin-top: -1rem;
            margin-bottom: .5rem;
            font-size: .6rem;
            background-color: #fff;

            .error-msg {
                color: red;
            }
        }

        svg {
            fill: $corPrim;
        }

        .link {
            text-align: center;
        }

        @keyframes entrada {
            from {
                opacity: 0;
                margin-left: 200px;
                margin-right: -200px;
            }

            to {
                opacity: 1;
                margin-left: 0;
                margin-right: 0;
            }
        }
    }
</style>