<template>
    <main>
        <section class="pedidoFeito bloco">
            <div class="cabecalho">
                <mdicon name="CheckCircleOutline" size="100" />
                <h1>Pedido Realizado com Sucesso!</h1>
            </div>
            <table class="pedido">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Data do Pedido</th>
                        <th>Status</th>
                        <th>Qtd. Itens</th>
                        <th>Valor</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>nº {{ this.pedidoResumo.codigo }}</td>
                        <td>{{ this.pedidoResumo.data_pedido }}</td>
                        <td>Solicitado</td>
                        <td>{{ this.pedidoResumo.qtd_itens }}</td>
                        <td>{{ this.pedidoResumo.valor }} pontos</td>
                        <td id="botao">
                            <button class="btnPrim">Ver Pedidos</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</template>

<script>
    import $ from 'jquery'
    
    export default {
        name: 'PedidoFeitoView',
        data() {
            return {
                pedidoResumo: []
            }
        },
        mounted() {
            this.$emitter.on('pedido-feito', (evt) => {
                this.pedidoResumo = evt.conteudo
                this.pedidoResumo.valor = this.pedidoResumo.valor.toLocaleString('pt-BR')
            }),
            $.ajax({
                method: 'POST',
                url: 'http://localhost/VueJS/5_vue_proj/src/services/backend/db.php'
            })
        }
    }
</script>

<style lang="scss" scoped>
    .pedidoFeito {
        background-color: white;
        display: flex;
        flex-direction: column;
        padding: 5rem 0 !important;

        .cabecalho {
            text-align: center;
            width: 100%;
            margin: 3rem 0;
            color: #59AA1F !important;

            h1 {
                margin-top: 1rem;
                font-size: 2rem;
                color: #59AA1F !important;
            }
        }

        .pedido {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            margin-bottom: 3rem;

            th, td {
                border: .1rem solid $cinza_3;
                width: calc(100% / 6);
                height: 2rem !important;
                text-align: center;
            }

            th {
                background-color: $cinza_2;
            }

            #botao {
                padding: .5rem;

                button {
                    width: 100%;
                }
            }
        }
    }
</style>