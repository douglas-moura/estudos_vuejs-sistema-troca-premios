<template>
    <header class="topbar">
        <router-link to="/perfil" id="perfil">
            <div class="usuario-info">
                <picture>
                    <img :src="'/src/assets/img/usuarios/perfil_' + this.id + '.jpg'" alt="" srcset="">
                    <img class="default-perfil" :src="'/src/assets/img/usuarios/default.png'" alt="" srcset="">
                </picture>
                <p>Olá, {{ nome }}</p>
            </div>
        </router-link>
        <div class="notificacoes">
            <mdicon
                v-if="notif_check || comuni_vistos"
                class="icone_topbar"
                @click="abrirNotificacoes()"
                name="BellOutline"
                size="20"
            />
            <mdicon
                v-else
                class="icone_topbar"
                @click="abrirNotificacoes()"
                name="BellBadgeOutline"
                size="20"
            />
            <Transition name="fade">
                <div v-show="this.dropDrown_notif" v-on:mouseleave="abrirNotificacoes()" class="comunicados sombra">
                    <ul v-if="this.comunicados.length > 0">
                        <ComunicadoItemList
                        v-for="(item, id) in this.comunicados"
                        v-bind:key="id"
                        :msg=item
                    />
                    </ul>
                    <ul v-else class="comunicados-vazio">
                        <li>
                            <p class="no-data bloco">Sem notificações no momento...</p>
                        </li>
                    </ul>
                </div>
            </Transition>          
            <mdicon class="icone_topbar" name="CartOutline" size="20" @click="abrirCarrinho()"/>
        </div>
        <div class="resumoPontos">
            <ul>
                <li><strong>Pontos Totais:</strong> {{ totalPontos }}</li>
                <li><strong>Pontos Trocados:</strong> {{ tradePontos }}</li>
                <li><strong>Saldo:</strong> {{ saldoPontos }}</li>
            </ul>
        </div>
    </header>
</template>

<script>
    import ComunicadoItemList from './layout/ComunicadoItemList.vue'

    export default {
        name: 'TopBar',
        data() {
            return {
                notif_check: false,
                dropDrown_notif: false
            }
        },
        components: {
            ComunicadoItemList
        },
        props: {
            id: Number,
            nome: String,
            comunicados: Array,
            totalPontos: Number,
            tradePontos: Number,
            saldoPontos: Number,
            comunicados_qtd: Number
        },
        methods: {
            abrirCarrinho() {
                // emitindo para App e Carrinho
                this.$emitter.emit('abrir-cart')
            },
            abrirNotificacoes() {
                this.dropDrown_notif = !this.dropDrown_notif
                this.notif_check = true
                sessionStorage.setItem('notif_check', true)
            }
        },
        mounted() {
            if(this.comunicados_qtd > 2) {
                this.nova_notif = true
            }
        },
        computed: {
            novaNotificacao() {
                let comunicados = this.comunicados
                let result = null
                if(this.notif_check == true || sessionStorage.getItem("notif_check")) {
                    result = false
                } else {
                    for(const key in comunicados) {
                        result = comunicados[key].visto == 0 ? true : false
                        break
                    }
                }
                return result
            },
            comuni_vistos() {
                return sessionStorage.getItem('notif_check')
            }
        }
    }
</script>

<style lang="scss" scoped>
    .topbar {
        //background-image: linear-gradient(rgba(#fff, 1) 40%, rgba(#fff, .6));
        //backdrop-filter: blur(.5rem);
        //background-color: red;
        border-bottom: .1rem solid $corPrim_0;
        flex-direction: row-reverse;
        background-color: white;
        box-sizing: border-box;
        padding: .5rem 6rem;
        align-items: center;
        text-align: right;
        position: fixed;
        z-index: 9996;
        display: flex;
        width: 85%;
        right: 0;

        a {
            color: $preto !important;
            text-decoration: none;
        }

        .usuario-info {
            box-sizing: border-box;
            display: flex;
            align-items: center;
            padding: .25rem;
            border-radius: 50rem;
            cursor: pointer;
            
            picture {
                display: flex;
                width: 2rem;
                border-radius: 50%;
                overflow: hidden;
                aspect-ratio: 1 / 1;
                margin-right: .5rem;
                border: .2rem solid white;
                
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
            
            span {
                margin-right: .5rem;
            }            
            
            p {
                margin-right: .5rem !important;
            }

            &:hover {
                background-color: $corPrim_1;
            }
        }

        .notificacoes {
            display: flex;
            margin-right: 1rem;

            .icone_topbar {
                display: flex;
                aspect-ratio: 1 / 1;
                padding: .75rem;
                border-radius: 5rem;
                cursor: pointer;
                margin-right: .25rem;

                &:hover {
                    background-color: $corPrim_1;
                }

            }

            .comunicados {
                position: fixed;
                background-color: white;
                margin-top: 3.5rem;
                width: 30rem;
                margin-left: -13.5rem;
                max-height: 30rem;
                text-align: left;
                overflow-x: hidden;
                
                @extend %barra-rolagem;

                ul {
                    padding: 0;
                    margin: 0;
                }

                .comunicados-vazio {
                    li {
                        padding: 1rem !important;

                        p {
                            min-height: 10rem !important;
                        }
                    }
                }
            }

            .pelicula {
                background-color: black;
                opacity: .7;
            }
        }

        .resumoPontos {
            left: 6rem;
            position: absolute;

            ul {
                padding: 0;

                li {
                    display: inline;
                    list-style-type: none;
                    margin-right: 3rem;
                    color: $cinza_4;
                    font-size: .8rem;

                    strong {
                        color: $cinza_5;
                    }
                }
            }
        }

        .fade-enter-from {
            height: 0;
        }
        .fade-enter-to {
            height: 30rem;
        }
        .fade-enter-active {
            transition: all .3s ease;
        }        
        .fade-leave-from {
            height: 30rem;
        }
        .fade-leave-to {
            height: 0rem;
        }
        .fade-leave-active {
            transition: all .3s ease;
        }
    }
</style>