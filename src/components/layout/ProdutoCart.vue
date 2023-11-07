<template>
    <tr class="produtoBoxCart" v-show="this.quantidade > 0">
        <td class="prodNome">
            {{ nomeProduto }}
        </td>
        <td class="prodQtd" selected="false">
            <button @click="atualizaQtd('menos', idtProduto, codProd, nomeProduto, valorProduto)">-</button>
            <span>{{ this.quantidade }}</span>
            <button @click="atualizaQtd('mais', idtProduto, codProd, nomeProduto, valorProduto)">+</button>
        </td>
        <td class="prodValor">{{ (valorProduto.toLocaleString('pt-BR') * this.quantidade).toFixed(3) }}</td>
        <td class="prodExcluir">
            <mdicon @click="removerItem(idtProduto)" name="TrashCanOutline" size="20" />
        </td>
    </tr>
</template>

<script>
    import { useCookies } from 'vue3-cookies';

    export default {
        name: 'ProdutoCart',
        setup() {
            const { cookies } = useCookies();
            return { cookies };
        },
        props: {
            idtProduto: Number,
            codProd: Number,
            nomeProduto: String,
            valorProduto: String,
            qtdProduto: Number
        },
        data() {
            return {
                usuario: JSON.parse(sessionStorage.getItem('authUser')),
                quantidade: this.qtdProduto,
            }
        },
        methods: {
            atualizaQtd(operador, id, cod, nome, valor) {
                if(operador == 'menos') {
                    if(this.quantidade > 1) {
                        this.quantidade -= 1
                        this.atualizaCarrinho(id, cod, nome, valor, this.quantidade)
                    } else {
                        setTimeout(() => {
                            this.removerItem(id)
                        }, 500)
                    }
                } else {
                    this.quantidade += 1
                    this.atualizaCarrinho(id, cod, nome, valor, this.quantidade)
                }
            },
            atualizaCarrinho(id, cod, nome, valor, qtdNova) {
                let cart = JSON.parse(this.cookies.get('carrinhoProds-user_' + this.usuario.id))
                let index = null
                let qtd = qtdNova
                let valorTot = (valor * this.quantidade).toFixed(3)
                
                for(const key in cart) {
                    if(cart[key].id_prod == id) {
                        // adiciona Qtd a produto existente 
                        index = key
                        break
                    }
                }
                cart[index] = {user_id: 1, id_prod: id, cod_prod: cod, nome_prod: nome, valor_prod: valor, valor_total: valorTot, qtd_prod: qtd}
                this.cookies.set('carrinhoProds-user_' + this.usuario.id, JSON.stringify(cart))
            },
            removerItem(id) {
                let cart = JSON.parse(this.cookies.get('carrinhoProds-user_' + this.usuario.id))
                let index = null
                for(const key in cart) {
                    if(cart[key].id_prod == id) {
                        // adiciona Qtd a produto existente 
                        index = key
                        break
                    }
                }
                cart.splice(index, 1);
                if(cart.length == 0) {
                    cart = null
                }
                this.quantidade = 0
                this.cookies.set('carrinhoProds-user_' + this.usuario.id, JSON.stringify(cart))
            }
        }
    }
</script>

<style lang="scss" scoped>
    .produtoBoxCart {
        height: 4rem;

        td {
            padding: .5rem 0;
        }

        .prodNome {
            font-weight: 700 !important;
            overflow: hidden;
            
            strong {
                margin-top: .5rem;
            }
        }

        .prodQtd {
            text-align: center;
            box-sizing: border-box;
            box-sizing: border-box;
            width: 20%;

            button, span {
                background-color: $cinza_2;
                display: inline;
                margin: 0;
                padding: .5rem .75rem;
                border: 0;
            }

            button {
                cursor: pointer;

                &:hover {
                    color: $corPrim;
                }
            }
        }
        
        .prodValor {
            width: 25%;
            text-align: center;
        }

        .prodExcluir {
            width: 25%;
            text-align: center;

            span {
                color: rgb(143, 0, 0);
                cursor: pointer;
            }
        }
    }
</style>