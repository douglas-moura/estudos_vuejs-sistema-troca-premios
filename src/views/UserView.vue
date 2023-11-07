<template>
    <main>
        <section class="user-pagina bloco">
            <TituloComp tituloTexto="Perfil" />
            {{ console.log(endreco_cadastrado) }}
            <div class="informacoes-user">
                <div class="info-pessoal bloco">
                    <div class="imagem-perfil">
                        <picture class="sombra">
                            <img :src="'/src/assets/img/usuarios/perfil_' + this.usuario.id + '.jpg'" alt="" srcset="">
                            <img class="default-perfil" :src="'/src/assets/img/usuarios/default.png'" alt="" srcset="">
                        </picture>
                        <span>{{ this.usuario.nome }}</span>
                        <p>{{ this.usuario.cargo }}</p>
                    </div>
                    <div class="dados-cadastro">
                        <strong>
                            <h2><mdicon name="AccountCircle" size="25" />Dados Pessoais</h2>
                            <!--<a @click="editarInfos('perfil')" class="link"><mdicon name="PencilOutline" size="15" />Editar</a>-->
                        </strong>
                        <table>
                            <tr>
                                <td>Nome Completo:</td>
                                <td>{{ this.usuario.nome + ' ' + this.usuario.sobrenome }}</td>
                            </tr>
                            <tr>
                                <td>E-mail:</td>
                                <td>{{ this.usuario.email }}</td>
                            </tr>
                            <tr>
                                <td>Telefone:</td>
                                <td>
                                    {{
                                        this.tel_atualizado == null ?
                                        this.usuario.telefone.substr(0, 2) + ' ' +
                                        this.usuario.telefone.substr(2, 5) + '-' +
                                        this.usuario.telefone.substr(7, 4) : 
                                        this.tel_atualizado
                                    }}
                                    <mdicon @click="showPopup = !showPopup, editInfo('telefone')" class="link" alt="Editar" name="PencilOutline" size="15" />
                                </td>
                            </tr>
                            <tr>
                                <td>Nascimento:</td>
                                <td>{{ formatarData(this.usuario.nasc) }}</td>
                            </tr>
                        </table>
                        <strong>
                            <h2>Senha</h2>
                        </strong>
                        <a class="link" @click="showPopup = !showPopup, editInfo('senha')">Alterar Senha</a>
                    </div>
                </div>
                <div class="endereco-pessoal bloco">
                    <strong>
                        <h2><mdicon name="HomeCircle" size="25" />Endereço Residêncial</h2>
                        <a @click="showPopup = !showPopup, editInfo('endereco')" class="link"><mdicon name="PencilOutline" size="15" />Editar</a>
                    </strong>
                    <table v-if="endreco_cadastrado.uf">
                        <tr>
                            <td>Endereço:</td>
                            <td>{{ endreco_cadastrado.endereco + ', nº ' + endreco_cadastrado.numero }}</td>
                        </tr>
                        <tr>
                            <td>Complemento:</td>
                            <td>{{ endreco_cadastrado.compl }}</td>
                        </tr>
                        <tr>
                            <td>Bairro:</td>
                            <td>{{ endreco_cadastrado.bairro }}</td>
                        </tr>
                        <tr>
                            <td>CEP:</td>
                            <td>{{ endreco_cadastrado.cep }}</td>
                        </tr>
                        <tr>
                            <td>Cidade:</td>
                            <td>{{ endreco_cadastrado.cidade + ' - ' + endreco_cadastrado.uf }}</td>
                        </tr>
                    </table>
                    <p v-else>Nenhum endereço cadastrado</p>
                </div>
                <Teleport to="#popups_box">
                    <div v-if="showPopup" class="popup-tela">
                        <div class="popup-caixa bloco">
                            <mdicon name="Close" size="25" @click="showPopup = !showPopup" />

                            <div v-show="this.formEdit == 'telefone'" class="popup-conteudo">
                                <h2>Atualizar Telefone</h2><br>
                                <AlertaBloco v-show="this.error_form.tipo != null" :classeBloco=this.error_form.tipo :alerta=this.error_form.msg />
                                <form>
                                    <InputCampo
                                        labelText="Telefone"
                                        inputType="numeric"
                                        inputName="tel"
                                        placeCampo="(__) _____-____" 
                                        v-model="v$.tel.$model"
                                        mascara="(##) #####-####"
                                    />
                                </form>
                                <button @click="atualizarDados(this.tel, 'telefone')" class="btnPrim">Atualizar</button>
                            </div>

                            <div v-show="this.formEdit == 'senha'" class="popup-conteudo">
                                <h2>Trocar Senha</h2><br>
                                <AlertaBloco v-show="this.error_form.tipo != null" :classeBloco=this.error_form.tipo :alerta=this.error_form.msg />
                                <InputCampo
                                    labelText="Senha"
                                    inputType="password"
                                    inputName="a_password"
                                    placeCampo="********" 
                                    v-model="v$.a_password.$model"
                                />
                                <InputCampo
                                    labelText="Confirme"
                                    inputType="password"
                                    inputName="c_password"
                                    placeCampo="********"
                                    v-model="v$.c_password.$model"
                                />
                                <button @click="atualizarDados(this.c_password, 'senha')" class="btnPrim">Atualizar</button>
                            </div>
                            <div v-show="this.formEdit == 'endereco'" class="popup-conteudo">
                                <h2>Inserir Endereço</h2><br>
                                <AlertaBloco v-show="this.error_form.tipo != null" :classeBloco=this.error_form.tipo :alerta=this.error_form.msg />
                                <div class="linhaForm">
                                    <div style="width: 68%">
                                        <InputCampo
                                            labelText="Endereço"
                                            inputType="text"
                                            inputName="endereco_rua"
                                            placeCampo="Rua Francisco Morato" 
                                            v-model="this.end_null.endereco"
                                        />
                                    </div>
                                    <div style="width: 28%">
                                        <InputCampo
                                            labelText="nº"
                                            inputType="text"
                                            inputName="endereco_num"
                                            placeCampo="123" 
                                            v-model="this.end_null.numero"
                                        />
                                    </div>
                                </div>
                                <div class="linhaForm">
                                    <div style="width: 100%">
                                        <InputCampo
                                            labelText="Complemento"
                                            inputType="text"
                                            inputName="endereco_compl"
                                            placeCampo="Casa 2" 
                                            v-model="this.end_null.compl"
                                        />
                                    </div>
                                </div>
                                <div class="linhaForm">
                                    <div style="width: 58%">
                                        <InputCampo
                                            labelText="Bairro"
                                            inputType="text"
                                            inputName="endereco_bairro"
                                            placeCampo="Vila Mariana" 
                                            v-model="this.end_null.bairro"
                                        />
                                    </div>
                                    <div style="width: 38%">
                                        <InputCampo
                                            labelText="CEP"
                                            inputType="numeric"
                                            inputName="endereco_cep"
                                            placeCampo="01234-567" 
                                            v-model="this.end_null.cep"
                                            mascara="#####-###"
                                        />
                                    </div>
                                </div>
                                <div class="linhaForm">
                                    <div style="width: 78%">
                                        <InputCampo
                                            labelText="Cidade"
                                            inputType="text"
                                            inputName="endereco_cidade"
                                            placeCampo="São Paulo" 
                                            v-model="this.end_null.cidade"
                                        />
                                    </div>
                                    <div style="width: 18%">
                                        <InputCampo
                                            labelText="UF"
                                            inputType="text"
                                            inputName="endereco_estado"
                                            placeCampo="SP" 
                                            v-model="this.end_null.uf"
                                        />
                                    </div>
                                </div>
                                <button @click="atualizarDados(this.end_null, 'endereco')" class="btnPrim">Salvar</button>
                            </div>
                        </div>
                    </div>
                </Teleport>
            </div>
        </section>
    </main>
</template>

<script>
    import { required, minLength, email, numeric, sameAs, helpers } from '@vuelidate/validators'
    import AlertaBloco from '../components/layout/AlertaBloco.vue'
    import TituloComp from '../components/layout/TituloComp.vue';
    import InputCampo from '../components/layout/InputCampo.vue';
    import { useVuelidate } from '@vuelidate/core'
    import { ref } from 'vue';
    import $ from 'jquery'

    export default {
        name: 'UserView',
        setup() {
            const showPopup = ref(false)
            return {
                showPopup,
                v$: useVuelidate()
            }
        },
        data() {
            return {
                tel: '',
                tel_atualizado: sessionStorage.getItem('tel_updt') ? sessionStorage.getItem('tel_updt') : null,
                end_banco: this.usuario.endereco[this.usuario.endereco.length - 1],
                end_novo: sessionStorage.getItem('end_updt') ? sessionStorage.getItem('end_updt') : null,
                end_null: {
                    endereco: '',
                    numero: '',
                    compl: '',
                    bairro: '',
                    cep: '',
                    cidade: '',
                    uf: ''
                },
                a_password: '',
                c_password: '',
                error_form: {
                    tipo: null,
                    msg: null
                },
                formEdit: null
            }
        },
        computed: {
            endreco_cadastrado() {
                let enderecoUser = this.end_null

                if(this.end_novo) {
                    enderecoUser = this.end_novo
                } else {
                    if(this.end_banco) {
                        enderecoUser = JSON.parse(this.end_banco)
                    }
                }
                console.log(enderecoUser)
                return enderecoUser
            }
        },
        components: {
            TituloComp,
            InputCampo,
            AlertaBloco
        },
        props: {
            usuario: Array
        },
        methods: {
            editInfo(value) {
                switch (value) {
                    case 'telefone':
                        this.formEdit = 'telefone'
                        break
                    case 'senha':
                        this.formEdit = 'senha'
                        break
                    case 'endereco':
                        this.formEdit = 'endereco'
                        break
                }
            },
            atualizarDados(value, destino) {
                let dados = {
                    id: this.usuario.id,
                    tipo: destino,
                    info: value
                }

                let tab = destino == 'endereco' ? 'endereco' : 'perfil'                
                let url_api = destino == 'endereco' ? 'http://localhost/VueJS/5_vue_proj/src/services/backend/insert.php' : 'http://localhost/VueJS/5_vue_proj/src/services/backend/update.php'

                $.ajax({
                    method: 'POST',
                    url: url_api,
                    data: {
                        tabela: tab,
                        data: JSON.stringify(dados)
                    }
                })
                
                switch (destino) {
                    case 'telefone':               
                        this.error_form = {
                            tipo: 'sucesso',
                            msg: 'Telefone atualizao com sucesso!'
                        }
                        this.tel_atualizado = value
                        sessionStorage.setItem('tel_updt', value)
                        break
                    case 'senha':               
                        this.error_form = {
                            tipo: 'sucesso',
                            msg: 'Senha atualizada'
                        }
                        sessionStorage.removeItem('authUser')
                        setTimeout(() => {
                            this.$router.push({name:'login'})
                        }, 1500)
                        break
                    case 'endereco':               
                        this.error_form = {
                            tipo: 'sucesso',
                            msg: 'Endereço cadastrado com sucesso!'
                        }
                        this.end_atualizado = value
                        sessionStorage.setItem('end_updt', JSON.stringify(value))
                        break
                }

                setTimeout(() => {
                    this.showPopup = false
                }, 2000)
            },
            formatarData(dat) {
                const data = new Date(dat + "T00:00:00")
                const dia = String(data.getDate()).padStart(2, '0')
                const mes = String(data.getMonth() + 1).padStart(2, '0')
                const ano = String(data.getFullYear()).slice(-2)
                let representacaoResumida = `${dia}/${mes}/${ano}`;
                return representacaoResumida 
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
        }
    }
</script>

<style lang="scss" scoped>
    .user-pagina {
        background-color: white;

        .informacoes-user {
            display: flex;
            justify-content: space-between;

            div {                
                &:first-of-type {
                    span {
                        font-size: 1rem;
                        font-weight: 800;
                    }
                }
            }

            .info-pessoal {
                width: 59.5%;
                display: flex;

                .imagem-perfil {
                    box-sizing: border-box;
                    width: 35%;

                    picture {
                        display: flex;
                        width: 80%;
                        border-radius: 50%;
                        overflow: hidden;
                        background-color: white;
                        aspect-ratio: 1 / 1;
                        margin-bottom: 1rem;
                        
                        img {
                            object-fit: cover;
                            margin: auto;
                            width: 100%;
                            height: 100%;
                        }

                        .default-perfil {
                            opacity: .8;
                        }
                    }

                    p {
                        font-size: .6rem;
                    }
                }

                .dados-cadastro {
                    width: 65%;
                }
            }

            .endereco-pessoal {
                width: 39.5%;
            }
    
            div {
                background-color: $corPrim_0;

                strong {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1rem;
                    justify-content: space-between;

                    h2 {
                        display: flex;
                        align-items: center;
    
                        span {
                            margin-right: .5rem;
                        }
                    }

                    a {
                        margin-right: 1rem !important;
                    }
                }

                table {
                    margin-bottom: 2rem;
                    border-collapse: collapse;
                    width: 100%;

                    tr {
                        td {
                            padding: .5rem 0;
    
                            &:nth-of-type(1) {
                                font-weight: 700;
                                width: 25%;
                            }
                            
                            &:nth-of-type(2) {
                                width: 60%;
                            }
                            
                            &:nth-of-type(3) {
                                width: 15%;
                            }
                        }
                    }
                }                
            }
        }
    }    
</style>