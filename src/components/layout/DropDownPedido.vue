<template>
    <div class="tabelaPedido" @click="abrirLista()">
        <table>
            <tr>
                <td><strong>nª Pedido:</strong> {{ pedido.codigo }}</td>
                <td><strong>Data Pedido:</strong> {{ dataResumida }}</td>
                <td><strong>Quantidade:</strong> {{ pedido.qtd_itens }}{{ pedido.qtd_itens > 1 ? ' itens' : ' item' }}</td>
                <td><strong>Valor Total:</strong> {{ pedido.valor.toLocaleString('pt-BR') }}</td>
                <td id="iconeSeta">
                    <mdicon v-if="!this.estadoLista" name="ChevronRight" size="25" />
                    <mdicon v-else name="ChevronDown" size="25" />
                </td>
            </tr>
        </table>
        <div class="detalhesPedidos" v-bind:class="this.estadoLista ? 'exposto' : 'oculto'">
            <div class="endereco">
                <h2>Endereço de Entrega</h2>
                <li>{{ entregaSelecionado.endereco }} - {{ entregaSelecionado.numero }} - {{ entregaSelecionado.bairro }}</li>
                <li>{{ entregaSelecionado.compl }} - CEP {{ entregaSelecionado.cep }}</li>
                <li>{{ entregaSelecionado.cidade }} - {{ entregaSelecionado.uf }}</li>
                <h2>Resumo Pedido</h2>
                <li><strong>Frete:</strong> {{ pedido.frete.toLocaleString('pt-BR') }} pontos</li>
                <li><strong>Subtotal:</strong> {{ (pedido.valor - pedido.frete).toLocaleString('pt-BR') }} pontos</li>
            </div>
            <div class="lista-itens">
                <PedidoDash
                    @click="abrirLista()"
                    v-for="(item, id) in itens" v-bind:key="id"
                    :dataProd=data_pedido
                    :Item=item
                />
            </div>
        </div>
    </div>
</template>

<script>
    import PedidoDash from './PedidoDash.vue';

    export default {
        name: 'DropDownPedido',
        components: {
            PedidoDash
        },
        data() {
            return {
                estadoLista: false
            }
        },
        props: {
            enderecoEntrega: Array,
            pedido: Array,
            itens: Array,
        },
        methods: {
            abrirLista() {
                this.estadoLista = !this.estadoLista
            }
        },
        computed: {
            itensOrdem() {
                return this.itens
            },
            dataResumida() {
                const data = new Date(this.pedido.data_pedido + "T00:00:00")
                const dia = String(data.getDate()).padStart(2, '0')
                const mes = String(data.getMonth() + 1).padStart(2, '0')
                const ano = String(data.getFullYear()).slice(-2)
                let representacaoResumida = `${dia}/${mes}/${ano}`;
                return representacaoResumida 
            },
            entregaSelecionado() {
                let enderecos = this.enderecoEntrega
                let enderecoSelect = null
                for(const key in enderecos) {
                    if(enderecos[key].id == this.pedido.end_entrega) {
                        enderecoSelect = enderecos[key]
                    }
                }
                return enderecoSelect
            }
        }
    }
</script>

<style lang="scss" scoped>
    .tabelaPedido {
        border: .1rem solid rgba($cinza_1, 1);
        //background-color: $cinza_2;
        box-sizing: border-box;
        padding: 1rem 2rem;
        overflow: hidden;
        cursor: pointer;
        width: 100%;

        &:hover {
            border: .1rem solid $corPrim;
        }
        
        table {
            width: 100%;

            tr {
                td {
                    &:not(:last-of-type) {
                        width: 24% !important;
                    }

                    &:not(:first-of-type) {
                        text-align: center;
                    }

                    span {
                        color: $corPrim;
                    }
                }

                #iconeSeta {
                    text-align: right !important;
                }
            }
        }

        .detalhesPedidos {
            margin-top: 2rem !important;
            display: flex;
            overflow: hidden;

            div {
                box-sizing: border-box;
                margin-bottom: 1rem;
            }

            .endereco {
                width: 50%;
                padding-right: 3rem;

                h2:last-of-type {
                    margin-top: 2rem;
                }

                li {
                    list-style-type: none;
                    margin: .5rem 0;
                }
            }

            .lista-itens {
                display: block;
                width: 50%;
                padding: .1rem .5rem .1rem 3rem;
                border-left: .1rem solid $cinza_3;
                border-radius: 0 !important;
            }
        }

        .oculto {
            display: none;
        }
    }
</style>