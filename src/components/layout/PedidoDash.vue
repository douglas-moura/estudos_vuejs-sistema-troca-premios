<template>
    <a v-bind:class="this.$route.name == 'pedidos' ? 'detalhado' : 'resumido'" class="pedido-dashboard bloco" @click="mostrarProgresso()" >
        <div class="resumo">
            <picture class="img-pedido">
                <img :src="caminhoImg" alt="">
            </picture>
            <div class="info-pedido">
                <div>
                    <h3>{{ Item.nome_prod }}</h3>
                    <p v-show="paginaAtual != 'pedidos'"><strong>Data Pedido:</strong> {{ formatarData(this.dataProd) }}</p>
                </div>
                <p class="valor" v-show="paginaAtual == 'pedidos'">Valor: {{ Item.valor.toLocaleString('pt-BR') }}</p>
                <p class="status">Status:<br>{{ Item.status_atual }}</p>
            </div>
        </div>
        <div v-bind:class="!this.telaProgresso ? 'fechado' : 'aberto'" v-show="paginaAtual == 'pedidos'" class="detalhes-entrega" >
            <table v-if="Item.cancelado == 0">
                <tr>
                    <td class="icon" rowspan="4">
                        <div>
                            <mdicon v-bind:style="{ color: '#59AA1F' }" v-for="n in calculaEtapa" v-bind:key="n" name="CheckCircleOutline" size="25" />
                            <mdicon v-for="n in 4 - calculaEtapa" v-bind:key="n" name="CheckboxBlankCircleOutline" size="25"/>
                            <div v-bind:class="'barra_' + calculaEtapa" class="barra barra_progresso"></div>
                            <div class="barra barra_cinza"></div>
                        </div>
                    </td>
                    <td class="etapaAtiva">Produto solicitado</td>
                </tr>
                <tr>
                    <td v-bind:class="this.Item.status_processado ? 'etapaAtiva' : null">
                        {{ this.Item.status_processado ? formatarData(this.Item.status_processado) + ' -' : null }}
                        Seu pedido foi processado
                    </td>
                </tr>
                <tr>
                    <td v-bind:class="this.Item.status_em_rota ? 'etapaAtiva' : null">
                        {{ this.Item.status_em_rota ? formatarData(this.Item.status_em_rota) + ' -' : null }}
                        Em rota de entrega
                    </td>
                </tr>
                <tr>
                    <td v-bind:class="this.Item.status_entrega ? 'etapaAtiva' : null">
                        {{ this.Item.status_entrega ? formatarData(this.Item.status_entrega) + ' -' : null }}
                        Entregue
                    </td>
                </tr>
            </table>
            <p class="cancel" v-else><mdicon name="CloseCircleOutline" size="25"/>Produto cancelado</p>
        </div>
    </a>
</template>

<script>
    export default {
        name: 'PedidoDash',
        props: {
            Item: Array,
            dataProd: String
        },
        data() {
            return {
                telaProgresso: false
            }
        },
        methods: {
            mostrarProgresso() {
                this.telaProgresso = !this.telaProgresso
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
        computed: {
            calculaEtapa() {
                let etapa = 0
                if(this.Item.status_entrega != null) {
                    etapa = 4
                } else if (this.Item.status_em_rota != null) {
                    etapa = 3
                } else if (this.Item.status_processado != null) {
                    etapa = 2
                } else {
                    etapa = 1
                }
                return etapa
            },
            caminhoImg() {
                return '/src/assets/img/produtos/' + this.Item.produto_codigo + '.jpg'
            },
            paginaAtual() {
                return this.$route.name
            }
        }
    }
</script>

<style lang="scss" scoped>
    .pedido-dashboard {
        background-color: $corPrim_0;
        flex-direction: column;
        cursor: pointer;
        transition: .2s;
        display: flex;
        width: 100%;
        
        &:hover {
            transition: .2s;
        }

        &:not(:last-of-type) {
            margin-bottom: .75rem;
        }

        .resumo {
            box-sizing: border-box;
            display: flex;

            .img-pedido {
                width: 8%;
                margin-right: 1rem;
                padding: .5rem;
                background-color: white;
                border-radius: .25rem !important;

                img {
                    width: 100%;
                    aspect-ratio: 1 / 1;
                }
            }

            .info-pedido {
                width: 80%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;

                div {
                    display: flex;
                    width: 100%;
                    justify-content: space-between;

                    h3 {
                        margin-bottom: .5rem;
                    }

                    p {
                        font-size: .6rem !important;
                    }
                }

                .status {
                    font-size: .6rem !important;
                }
            }
        }

        .detalhes-entrega {
            background-color: white;
            border-radius: .5rem !important;
            overflow: hidden;
            margin-top: 2rem;

            table {
                width: 60%;
                border-collapse: collapse;

                tr {
                    color: $cinza_3;

                    td {
                        padding: 1rem;
                        //border: .1rem solid black;
                    }

                    .icon {
                        padding: 0 !important;

                        div {
                            position: relative;
                            height: 100%;
                            display: flex;
                            flex-direction: column;
                            justify-content: space-between;
                            text-align: center;
                            align-items: center;

                            span {
                                margin: 1rem 0;
                                z-index: 20;
                                background-color: white;
                            }

                            .barra {
                                position: absolute;
                                width: .05rem;
                                margin: 1rem auto;
                                height: calc(75% - 2rem);
                            }

                            .barra_1 { height: 0%; }
                            .barra_2 { height: 30%; }
                            .barra_3 { height: 60%; }
                            .barra_4 { height: 80%; }

                            .barra_progresso {
                                background-color: #59AA1F;
                                z-index: 10;
                            }

                            .barra_cinza {
                                height: calc(100% - 2rem);
                                background-color: $cinza_3;
                                z-index: 5;
                            }
                        }
                    }
                }
                
                .etapaAtiva {
                    color: #59AA1F !important;
                }
            }

            .cancel {
                align-items: center;
                color: rgb(173, 0, 0);

                span {
                    margin-right: 1rem;
                }
            }
        }

        .fechado {
            height: 0rem;
            transition: .2s;
            transition-delay: .01s;
            transition-property: margin-top;
            padding: 0 1rem !important;
            margin-top: 0;
        }

        .aberto {
            height: auto;
            transition: .2s;
            transition-delay: .01s;
            transition-property: height, padding;
            margin-top: 1rem;
            padding: 1rem;
        }
    }

    /*
    .detalhado {
        .resumo{
            .img-pedido {
                box-sizing: border-box;
                overflow: hidden;
                width: 10%;
                margin-right: 1rem;
                background-color: #fff;
                border-radius: .25rem;
                padding: .5rem;

                img {
                    width: 100%;
                    aspect-ratio: 1 / 1;
                }
            }

            .info-pedido {
                h4 {
                    font-size: 1.2rem !important;
                }   
            }
        }
    }
    */
</style>