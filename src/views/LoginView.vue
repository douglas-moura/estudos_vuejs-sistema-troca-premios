<template>
    <main class="loginPagina">
        <div v-if="cadSucesso == false" class="loginBox">
            <section>
                <FormLogin
                    v-if="loginTela"
                    @mudar-form="trocarFormulario"
                />
                <FormRegistro
                    v-else
                    @mudar-form="trocarFormulario"
                    @cadastro-sucesso="telaParabens"
                />
            </section>
            <section>
                <img src="../assets/img/pexels-caio-93532.jpg" alt="">
            </section>
        </div>
        <CadastroSucesso v-else @cadastro-sucesso="telaParabens" />
    </main>
</template>

<script>
    import FormLogin from '../components/FormLogin.vue';
    import FormRegistro from '../components/FormRegistro.vue';
    import CadastroSucesso from '../components/CadastroSucesso.vue';

    export default {
        name: 'LoginView',
        components: {
            FormLogin,
            FormRegistro,
            CadastroSucesso
        },
        data() {
            return {
                loginTela: true,
                cadSucesso: false
            }
        },
        methods: {
            trocarFormulario() {
                return this.loginTela = !this.loginTela
            },
            telaParabens() {
                this.cadSucesso = !this.cadSucesso
                this.loginTela = true
            }
        },
    }
</script>

<style lang="scss">
    .loginPagina {
        width: 100%;
        margin: 0;
        height: 100%;
        position: absolute;
        display: flex;
        background-color: $cinza_1;

        .loginBox {
            display: flex;
            box-sizing: border-box;
            width: 50%;
            margin: auto;
            aspect-ratio: 1 / .65;
            background-color: #fff;
            box-shadow: 0 2rem 2rem rgba($color: #000000, $alpha: .2);

            section {
                overflow: hidden;
                width: 50%;
                display: flex;
                align-items: center;
                box-sizing: border-box;

                &:first-of-type {
                    padding: 3rem;

                    .formLogin {
                        width: 100%;

                        form {
                            .linhaForm {
                                div {
                                    label {
                                        font-size: .8rem;
                                    }
                                }
                            }

                            p, a {
                                font-size: .7rem;
                            }

                            a {
                                cursor: pointer;
                            }
                        }
                    }
                }

                &:not(:first-of-type) {
                    justify-content: center;

                    img {
                        height: 100%;
                    }
                }
            }
        }
    }
</style>