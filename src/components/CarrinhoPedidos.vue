<template>
    <aside v-bind:class="estadoCarrinho ? 'aberto' : 'fechado'">
        <div class="carrinho">
            <div class="listPedidos">
                <div class="cabecalho">
                    <TituloComp tituloTexto="Carrinho" />
                    <mdicon name="Close" size="30" @click="fecharCarrinho()" />
                </div>
                {{ console.log('Prods', produtosDoCarrinho) }}
                <div class="produtos bloco" v-if="produtosDoCarrinho !== null && produtosDoCarrinho.length > 0">
                    <table>
                        <thead>
                            <th class="produtNome">Produto</th>
                            <th class="quant">Qtd.</th>
                            <th class="valor">Pontos</th>
                            <th class="excluir"></th>
                        </thead>
                        <tbody>
                            <ProdutoCart
                                v-for="(item, id) in produtosDoCarrinho" v-bind:key="id"
                                :idtProduto=item.id_prod
                                :codProd=item.cod_prod
                                :nomeProduto=item.nome_prod
                                :valorProduto=item.valor_prod
                                :qtdProduto=item.qtd_prod
                            />
                        </tbody>
                    </table>
                </div>
                <p class="no-data bloco" v-else>Seu carrinho está vazio</p>
                <div class="resumoPedido">
                    <h2>Endereço de Entrega:</h2>
                    <ul v-if="enderecoUser">
                        <li>Endereço: {{ enderecoUser.endereco }}, {{ enderecoUser.numero }} {{ '- ' + enderecoUser.compl }}</li>
                        <li>CEP {{ enderecoUser.cep }} - {{ enderecoUser.bairro }}</li>
                        <li>{{ enderecoUser.cidade }} - {{ enderecoUser.uf }}</li>
                    </ul>
                    <p v-else>Nenhum endereço não cadastrado</p>
                    <h2>Resumo:</h2>
                    <table>
                        <tr>
                            <td>Subtotal:</td>
                            <td>{{ (subTotal).toFixed(3) }} pontos</td>
                        </tr>
                        <tr>
                            <td>Frete:</td>
                            <td>{{ this.frete.toFixed(3) }} pontos</td>
                        </tr>
                        <tr class="vTotal">
                            <td>Total Pedido:</td>
                            <td v-if="totalGeral.toFixed(3) > 1.5">{{ totalGeral.toFixed(3) }} pontos</td>
                            <td v-else> </td>
                        </tr>
                    </table>
                    <button
                        class="btnPrim"
                        v-bind:class="verificaSaldo ? null : 'btnBlock'"
                        @click="realizarPedido(enderecoUser.user_id, enderecoUser, totalGeral.toFixed(3))">
                            Finalizar Pedido
                    </button>
                </div>
            </div>
        </div>
        <div class="pelicula" @click="fecharCarrinho()" ></div>
    </aside>
</template>

<script>
    import ProdutoCart from './layout/ProdutoCart.vue';
    import TituloComp from './layout/TituloComp.vue';
    import { useCookies } from 'vue3-cookies';
    import $ from 'jquery'

    export default {
        name: 'CarrinhoPedidos',
        components: {
            ProdutoCart,
            TituloComp
        },
        props: {
            produtosDoCarrinho: Array,
            endereco: Array,
            pedidosFeitos: Array,
            saldoPontos: Number
        },
        setup() {
            const { cookies } = useCookies();
            return { cookies };
        },
        data() {
            return {
                frete: 1.5,
                estadoCarrinho: false,
                usuario: JSON.parse(sessionStorage.getItem('authUser')),
            }
        },
        methods: {
            resumoPedido(value) {
                this.$emitter.emit('pedido-feito', { conteudo: value })
                this.$emitter.emit('atualiza-pedidos')
            },
            realizarPedido(usuarioID, endereco, valor_pedido) {
                if(this.verificaSaldo == true) {
                    let itens_pedido = JSON.parse(this.cookies.get('carrinhoProds-user_' + this.usuario.id))
                    let pedidoFinal = {}
                    let qtdTotal = 0

                    let data = new Date()
                    let cod_hr = data.getHours().toString().length == 1 ? '0' + data.getHours().toString() : data.getHours().toString()
                    let cod_mn = data.getMinutes().toString().length == 1 ? '0' + data.getMinutes().toString() : data.getMinutes().toString()
                    let cod_sg = data.getSeconds().toString().length == 1 ? '0' + data.getSeconds().toString() : data.getSeconds().toString()
                    let cod_dt = data.toLocaleDateString("pt-BR").replace(/\//g,'').substring(0, 2) + '-' + data.toLocaleDateString("pt-BR").replace(/\//g,'').substring(2, 4)

                    let novaID = usuarioID + cod_hr + cod_mn + cod_sg + cod_dt.toString()

                    for(const key in itens_pedido) {
                        qtdTotal += itens_pedido[key].qtd_prod
                        itens_pedido[key].valor_prod = parseInt(itens_pedido[key].valor_prod * 1000)
                        itens_pedido[key].valor_total = parseInt(itens_pedido[key].valor_total * 1000)
                    }

                    pedidoFinal.codigo = novaID
                    pedidoFinal.user_id = usuarioID
                    pedidoFinal.qtd_itens = qtdTotal
                    pedidoFinal.valor = parseInt(valor_pedido * 1000)
                    pedidoFinal.frete = 1500
                    pedidoFinal.data_pedido = ((data.getDate() )) + "/" + ((data.getMonth() + 1)) + "/" + data.getFullYear()

                    pedidoFinal.entrega = endereco
                    pedidoFinal.itens = itens_pedido

                    // Pega os dados do JSON e passa pro PHP
                    $.ajax({
                        method: 'POST',
                        url: 'http://localhost/VueJS/5_vue_proj/src/services/backend/insert.php',
                        data: {
                            tabela: 'pedidos',
                            data: JSON.stringify(pedidoFinal)
                        }
                    })

                    console.log('Pedido Feito', pedidoFinal)

                    this.fecharCarrinho()

                    setTimeout(() => {
                        this.$router.push({name:'solicitado'})
                    }, 1500)

                    setTimeout(() => {
                        this.cookies.remove('carrinhoProds-user_' + this.usuario.id)
                    }, 1510)

                    setTimeout(() => {
                        this.resumoPedido(pedidoFinal)
                    }, 1600)
                }
            },
            fecharCarrinho() {
                // abre e fecha o Carrinho
                this.estadoCarrinho = !this.estadoCarrinho
                if(this.estadoCarrinho == false) {
                    // emite para App
                    this.$emitter.emit('limpa-cart')
                }
            }
        },
        computed: {
            enderecoUser() {
                let index = this.endereco.length - 1
                return this.endereco[index]
            },
            subTotal() {
                let produtos = this.produtosDoCarrinho
                let soma = 0
                if(produtos != null) {
                    for(let i = 0; i < produtos.length; i++) {
                        soma += parseFloat(produtos[i].valor_total)
                    }
                }
                return soma
            },
            totalGeral() {
                return this.frete + this.subTotal
            },
            verificaSaldo() {
                let pontosUsuario = this.saldoPontos
                let pontosPedido = this.totalGeral
                let estado = false
                if(pontosUsuario >= pontosPedido && pontosPedido > 1.5) {
                    estado = true
                }
                return estado
            }
        },
        mounted() {
            this.$emitter.on('abrir-cart', () => {
                // recebendo emitt de TopBar
                this.fecharCarrinho()
            })
        }
    }
</script>

<style lang="scss" scoped>
    aside {
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 9998;
        
        .carrinho {
            z-index: 9998;
            box-sizing: border-box;
            position: fixed;
            right: 0;
            width: 30%;
            height: 100%;
            background-color: #fff;
            box-shadow: 0 0 2rem rgba($cinza_5, .3);
            padding: 2rem;
            transition: .25s;

            .cabecalho {
                display: flex;
                justify-content: space-between;
                margin-bottom: 2rem;
                
                h1 {
                    display: flex;
                    align-items: center;
                    -webkit-touch-callout: none;
                    -webkit-user-select: none;
                    -khtml-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;

                    span {
                        margin-right: .5rem;
                    }
                }

                span {
                    cursor: pointer;
                    color: $corPrim;
                }
            }

            .produtos {
                position: absolute;
                width: calc(100% - 4rem);
                box-sizing: border-box;
                max-height: 40%;
                overflow-y: scroll;
                background-color: $cinza_1;
                    
                @extend %barra-rolagem;

                table {
                    width: 100%;
                    border-collapse: collapse;

                    thead {
                        border-bottom: .1rem solid $cinza_3;

                        th {
                            text-align: left;
                            font-size: 1rem;
                            padding-bottom: 1rem;
                            color: $corPrim_7;
                        }

                        .produtNome {
                            width: 35%;
                        }

                        .quant {
                            width: 30%;
                            text-align: center !important;
                        }

                        .valor {
                            width: 20%;
                            text-align: center !important;
                        }

                        .excluir {
                            width: 15%;
                        }
                    }
                }
            }

            .resumoPedido {
                background-color: #fff;
                box-sizing: border-box;
                position: absolute;
                bottom: 2rem;
                height: auto;
                display: flex;
                flex-direction: column;
                text-align: left;
                align-items: flex-start;
                justify-content: flex-end;
                width: calc(100% - 4rem);

                h2 {
                    margin: 1rem 0;
                    width: 100%;
                }

                ul {
                    margin-top: 0;
                    padding: 0;

                    li {
                        list-style-type: none;
                    }
                }

                table {
                    width: 100%;

                    tr {
                        td {
                            padding-bottom: .5rem;

                            &:first-of-type {
                                width: 40%;
                            }

                            &:last-of-type {
                                width: 60%;
                                text-align: right;
                                font-weight: 700 !important;
                            }
                        }
                    }

                    .vTotal {
                        font-size: 1rem;

                        td {
                            border-top: .1rem solid $cinza_3;
                            padding-top: 1rem !important;

                            &:last-of-type {
                                font-size: 1.2rem;
                            }
                        }
                    }
                }

                button {
                    margin-top: 1rem;
                }
            }
        } 

        .pelicula {
            background-color: black;
            opacity: .7;
        }
    }
    
    .aberto {
        .carrinho {
            margin-right: 0%;
        }

        .pelicula {
            height: 100%;
        }
    }

    .fechado {
        width: 0%;

        div {
            margin-right: -40%;
        }

        .pelicula {
            display: none !important;
        }
    }
</style>